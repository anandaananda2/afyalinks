<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $profile = $user->doctorProfile;
        
        return view('doctor.profile.show', compact('user', 'profile'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        $profile = $user->doctorProfile;
        
        return view('doctor.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'specialization' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:100', 'unique:doctor_profiles,license_number,' . auth()->user()->doctorProfile->id],
            'qualifications' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'consultation_duration' => ['required', 'integer', 'min:15', 'max:180'],
            'work_start_time' => ['required', 'date_format:H:i'],
            'work_end_time' => ['required', 'date_format:H:i', 'after:work_start_time'],
            'break_start_time' => ['nullable', 'date_format:H:i'],
            'break_end_time' => ['nullable', 'date_format:H:i', 'after:break_start_time'],
            'working_days' => ['required', 'array', 'min:1'],
            'working_days.*' => ['integer', 'between:0,6'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if (auth()->user()->doctorProfile->profile_photo) {
                Storage::delete(auth()->user()->doctorProfile->profile_photo);
            }
            
            $validated['profile_photo'] = $request->file('profile_photo')->store('doctor-photos', 'public');
        }

        auth()->user()->doctorProfile->update($validated);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}