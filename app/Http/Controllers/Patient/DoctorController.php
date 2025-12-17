<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'doctor')
            ->where('is_active', true)
            ->with('doctorProfile');

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->whereHas('doctorProfile', function($q) use ($request) {
                $q->where('specialization', 'like', '%' . $request->specialization . '%');
            });
        }

        // Filter by availability
        if ($request->filled('available_only')) {
            $query->whereHas('doctorProfile', function($q) {
                $q->where('is_available', true);
            });
        }

        $doctors = $query->paginate(12);

        // Get unique specializations for filter
        $specializations = DoctorProfile::distinct()->pluck('specialization')->sort();

        return view('patient.doctors.index', compact('doctors', 'specializations'));
    }

    public function show(User $doctor): View
    {
        // Ensure the user is a doctor
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $doctor->load('doctorProfile');

        // Get upcoming availability (next 14 days)
        $availabilities = $doctor->doctorAvailabilities()
            ->where('date', '>=', now()->toDateString())
            ->where('date', '<=', now()->addDays(14)->toDateString())
            ->where('is_available', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('patient.doctors.show', compact('doctor', 'availabilities'));
    }
}