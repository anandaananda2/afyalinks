<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Today's appointments
$todayAppointments = $user->doctorAppointments()
    ->whereDate('appointment_date', now()->toDateString())
    ->with('patient')
    ->orderBy('appointment_time')
    ->get();
        
        // Upcoming appointments (next 7 days)
        $upcomingAppointments = $user->doctorAppointments()
            ->whereBetween('appointment_date', [
                now()->addDay()->toDateString(),
                now()->addDays(7)->toDateString()
            ])
            ->where('status', '!=', 'cancelled')
            ->with('patient')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();
        
        // Statistics
        $stats = [
            'today_total' => $todayAppointments->count(),
            'today_completed' => $todayAppointments->where('status', 'completed')->count(),
            'today_pending' => $todayAppointments->whereIn('status', ['pending', 'confirmed'])->count(),
            'upcoming_week' => $upcomingAppointments->count(),
        ];
        
        return view('doctor.dashboard', compact(
            'user',
            'todayAppointments',
            'upcomingAppointments',
            'stats'
        ));
    }
}
