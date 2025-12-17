<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Get upcoming appointments
        $upcomingAppointments = $user->patientAppointments()
            ->where('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->with('doctor.doctorProfile')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
        
        // Get recent appointments
        $recentAppointments = $user->patientAppointments()
            ->where('appointment_date', '<', now()->toDateString())
            ->with('doctor.doctorProfile')
            ->orderBy('appointment_date', 'desc')
            ->limit(5)
            ->get();
        
        // Get pending payments
        $pendingPayments = $user->payments()
            ->where('status', 'pending')
            ->with('appointment')
            ->get();
        
        return view('patient.dashboard', compact(
            'user',
            'upcomingAppointments',
            'recentAppointments',
            'pendingPayments'
        ));
    }
}
