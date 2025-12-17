<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'doctor_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'department',
        'notes',
        'status',
        'consultation_fee'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
    ];

    // IMPORTANT: Add this boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = self::generateAppointmentNumber();
            }
        });
    }

    // Generate unique appointment number
    private static function generateAppointmentNumber()
    {
        $lastAppointment = self::orderBy('id', 'desc')->first();
        
        if (!$lastAppointment || empty($lastAppointment->appointment_number)) {
            return 'APT000001';
        }
        
        $lastNumber = intval(substr($lastAppointment->appointment_number, 3));
        $newNumber = $lastNumber + 1;
        
        return 'APT' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
