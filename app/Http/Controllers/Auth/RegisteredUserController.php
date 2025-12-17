<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatientProfile;
use App\Models\DoctorProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'role' => ['required', 'in:patient,doctor,health_worker'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Create profile based on role
        if ($user->isPatient()) {
            PatientProfile::create([
                'user_id' => $user->id,
            ]);
        } elseif ($user->isDoctor()) {
            DoctorProfile::create([
                'user_id' => $user->id,
                'specialization' => 'General Practice', // Default
                'license_number' => 'TEMP-' . $user->id, // Temporary, to be updated
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        return match($user->role) {
            'patient' => redirect()->route('patient.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'health_worker' => redirect()->route('health-worker.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}