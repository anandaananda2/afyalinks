<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Overall Statistics
        $stats = [
            'total_patients' => User::where('role', 'patient')->count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_appointments' => Appointment::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            
            // Today's stats
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'today_revenue' => Payment::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            
            // Pending items
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'critical_incidents' => IncidentReport::where('severity', 'critical')
                ->whereNotIn('status', ['resolved', 'closed'])
                ->count(),
        ];

        // Appointment trends (last 7 days)
        $appointmentTrends = Appointment::where('appointment_date', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Appointment status breakdown
        $appointmentStatusBreakdown = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Revenue trends (last 30 days)
        $revenueTrends = Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top doctors by appointments
        $topDoctors = User::where('role', 'doctor')
            ->withCount(['doctorAppointments' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->orderBy('doctor_appointments_count', 'desc')
            ->limit(5)
            ->get();

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pending incidents
        $pendingIncidents = IncidentReport::with('reporter')
            ->whereIn('status', ['reported', 'acknowledged'])
            ->orderBy('severity', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'appointmentTrends',
            'appointmentStatusBreakdown',
            'revenueTrends',
            'topDoctors',
            'recentAppointments',
            'pendingIncidents'
        ));
    }
}
