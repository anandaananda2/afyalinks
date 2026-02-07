<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'consultation_fee',
        'reason',
        'type',
        'duration',
        'payment_status',
        'cancellation_reason',
        'cancelled_at',
        'checked_in_at',
        'started_at',
        'completed_at',
        'ai_trend',
        'ai_confidence'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'checked_in_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function canBeCancelled(): bool
    {
        if ($this->status === 'cancelled' || $this->status === 'completed') {
            return false;
        }

        $start = $this->appointment_date->copy()->setTime(
            $this->appointment_time->hour,
            $this->appointment_time->minute
        );

        return now()->addHours(2)->lte($start);
    }

    public function getEndTimeAttribute()
    {
        if (!$this->appointment_time || !$this->duration) {
            return null;
        }
        return $this->appointment_time->copy()->addMinutes($this->duration);
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'blue',
            'checked_in' => 'indigo',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            'no_show' => 'gray',
            'pending' => 'yellow',
            default => 'gray'
        };
    }
}
