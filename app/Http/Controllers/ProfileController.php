<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $profile = $user->patientProfile;
        
        return view('patient.profile.show', compact('user', 'profile'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        $profile = $user->patientProfile;
        
        return view('patient.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['required', 'string', 'max:500'],
            'national_id' => ['nullable', 'string', 'max:50', 'unique:patient_profiles,national_id,' . auth()->user()->patientProfile->id],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'chronic_conditions' => ['nullable', 'string', 'max:1000'],
            'current_medications' => ['nullable', 'string', 'max:1000'],
            'past_surgeries' => ['nullable', 'string', 'max:1000'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],
        ]);

        auth()->user()->patientProfile->update($validated);

        return redirect()->route('patient.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}