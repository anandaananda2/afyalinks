<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $availabilities = auth()->user()->doctorAvailabilities()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20);
        
        return view('doctor.availability.index', compact('availabilities'));
    }

    public function create(): View
    {
        return view('doctor.availability.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'type' => ['required', 'in:regular,special,leave'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Check for overlapping availability
        $overlap = auth()->user()->doctorAvailabilities()
            ->where('date', $validated['date'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['time' => 'This time slot overlaps with existing availability.'])->withInput();
        }

        $validated['doctor_id'] = auth()->id();
        $validated['is_available'] = $validated['type'] !== 'leave';

        DoctorAvailability::create($validated);

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Availability added successfully!');
    }

    public function edit(DoctorAvailability $availability): View
    {
        // Ensure doctor can only edit their own availability
        if ($availability->doctor_id !== auth()->id()) {
            abort(403);
        }

        return view('doctor.availability.edit', compact('availability'));
    }

    public function update(Request $request, DoctorAvailability $availability): RedirectResponse
    {
        // Ensure doctor can only update their own availability
        if ($availability->doctor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'type' => ['required', 'in:regular,special,leave'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Check for overlapping availability (excluding current record)
        $overlap = auth()->user()->doctorAvailabilities()
            ->where('id', '!=', $availability->id)
            ->where('date', $validated['date'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['time' => 'This time slot overlaps with existing availability.'])->withInput();
        }

        $validated['is_available'] = $validated['type'] !== 'leave';
        $availability->update($validated);

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Availability updated successfully!');
    }

    public function destroy(DoctorAvailability $availability): RedirectResponse
    {
        // Ensure doctor can only delete their own availability
        if ($availability->doctor_id !== auth()->id()) {
            abort(403);
        }

        // Check if there are appointments for this slot
        $hasAppointments = auth()->user()->doctorAppointments()
            ->where('appointment_date', $availability->date)
            ->whereBetween('appointment_time', [$availability->start_time, $availability->end_time])
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->exists();

        if ($hasAppointments) {
            return back()->withErrors(['delete' => 'Cannot delete availability with existing appointments.']);
        }

        $availability->delete();

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Availability deleted successfully!');
    }

    // API endpoint for calendar view
    public function calendar(Request $request): JsonResponse
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end = $request->input('end', now()->endOfMonth()->toDateString());

        $availabilities = auth()->user()->doctorAvailabilities()
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function($availability) {
                return [
                    'id' => $availability->id,
                    'title' => $availability->type === 'leave' ? 'Leave' : 'Available',
                    'start' => $availability->date->format('Y-m-d') . 'T' . Carbon::parse($availability->start_time)->format('H:i:s'),
                    'end' => $availability->date->format('Y-m-d') . 'T' . Carbon::parse($availability->end_time)->format('H:i:s'),
                    'color' => $availability->type === 'leave' ? '#ef4444' : ($availability->type === 'special' ? '#3b82f6' : '#10b981'),
                    'type' => $availability->type,
                ];
            });

        return response()->json($availabilities);
    }
}
