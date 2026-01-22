<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->doctorAppointments()
            ->where('status', 'completed')
            ->with(['patient', 'patient.patientProfile']);

        // Optional: Simple search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $completedAppointments = $query->orderBy('completed_at', 'desc')
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('doctor.analytics.index', compact('completedAppointments'));
    }
}
