<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->doctorAppointments()
            ->with('patient');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        // Ensure doctor can only view their own appointments
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load(['patient.patientProfile', 'payment']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        // Ensure doctor can only update their own appointments
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,checked_in,in_progress,completed,no_show'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = ['status' => $validated['status']];

        // Update timestamps based on status
        if ($validated['status'] === 'checked_in' && !$appointment->checked_in_at) {
            $updateData['checked_in_at'] = now();
        }

        if ($validated['status'] === 'in_progress' && !$appointment->started_at) {
            $updateData['started_at'] = now();
        }

        if ($validated['status'] === 'completed' && !$appointment->completed_at) {
            $updateData['completed_at'] = now();
        }

        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $appointment->update($updateData);

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function addNotes(Request $request, Appointment $appointment): RedirectResponse
    {
        // Ensure doctor can only add notes to their own appointments
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $appointment->update(['notes' => $validated['notes']]);

        return back()->with('success', 'Notes added successfully.');
    }
}