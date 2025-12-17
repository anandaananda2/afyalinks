<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'qualifications',
        'bio',
        'profile_photo',
        'consultation_fee',
        'consultation_duration',
        'work_start_time',
        'work_end_time',
        'break_start_time',
        'break_end_time',
        'working_days',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'consultation_fee' => 'decimal:2',
            'consultation_duration' => 'integer',
            'working_days' => 'array',
            'is_available' => 'boolean',
            'work_start_time' => 'datetime:H:i',
            'work_end_time' => 'datetime:H:i',
            'break_start_time' => 'datetime:H:i',
            'break_end_time' => 'datetime:H:i',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id', 'user_id');
    }

    // Helper Methods
    public function isWorkingDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->working_days ?? []);
    }

    public function getWorkingDaysTextAttribute()
    {
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $workingDays = array_map(fn($day) => $days[$day], $this->working_days ?? []);
        return implode(', ', $workingDays);
    }
}
