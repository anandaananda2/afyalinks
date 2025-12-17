<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Default dashboard route - redirects based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    return match($user->role) {
        'patient' => redirect()->route('patient.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'health_worker' => redirect()->route('health-worker.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => abort(403, 'Invalid role'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Patient Routes
Route::middleware(['auth', 'verified', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Patient\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\Patient\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Patient\ProfileController::class, 'update'])->name('profile.update');
    
    // Browse Doctors
    Route::get('/doctors', [\App\Http\Controllers\Patient\DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [\App\Http\Controllers\Patient\DoctorController::class, 'show'])->name('doctors.show');
    
    // Appointments
    Route::get('/appointments', [\App\Http\Controllers\Patient\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [\App\Http\Controllers\Patient\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [\App\Http\Controllers\Patient\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [\App\Http\Controllers\Patient\AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [\App\Http\Controllers\Patient\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    
    // Payments
    Route::get('/payments', [\App\Http\Controllers\Patient\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [\App\Http\Controllers\Patient\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/appointments/{appointment}/payment', [\App\Http\Controllers\Patient\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/appointments/{appointment}/payment', [\App\Http\Controllers\Patient\PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/{payment}/receipt', [\App\Http\Controllers\Patient\PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
});

// Doctor Routes
Route::middleware(['auth', 'verified', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Doctor\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\Doctor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Doctor\ProfileController::class, 'update'])->name('profile.update');
    
    // Availability Management
    Route::resource('availability', \App\Http\Controllers\Doctor\AvailabilityController::class);
    Route::get('/availability-calendar', [\App\Http\Controllers\Doctor\AvailabilityController::class, 'calendar'])->name('availability.calendar');
    
    // Appointments
    Route::get('/appointments', [\App\Http\Controllers\Doctor\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [\App\Http\Controllers\Doctor\AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/status', [\App\Http\Controllers\Doctor\AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::post('/appointments/{appointment}/notes', [\App\Http\Controllers\Doctor\AppointmentController::class, 'addNotes'])->name('appointments.addNotes');
});

// Health Worker Routes
Route::middleware(['auth', 'verified', 'role:health_worker'])->prefix('health-worker')->name('health-worker.')->group(function () {
    Route::get('/dashboard', function () {
        return view('health-worker.dashboard');
    })->name('dashboard');
    
    // Incident Reports
    Route::resource('incidents', \App\Http\Controllers\HealthWorker\IncidentReportController::class);
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Profile Routes (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/patient/profile/edit', [ProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::put('/patient/profile', [ProfileController::class, 'update'])->name('patient.profile.update');
    Route::put('/patient/profile/password', [ProfileController::class, 'updatePassword'])->name('patient.profile.password');
});

require __DIR__.'/auth.php';