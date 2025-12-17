<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'phone_verified',
        'phone_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class, 'reported_by');
    }

    public function doctorAvailabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    // Helper Methods
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHealthWorker(): bool
    {
        return $this->role === 'health_worker';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}