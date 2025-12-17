<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'type',
        'channel',
        'status',
        'scheduled_for',
        'sent_at',
        'message',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }
}
