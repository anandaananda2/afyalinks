<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'appointment_id',
        'user_id',
        'amount',
        'payment_method',
        'status',
        'payment_provider',
        'provider_transaction_id',
        'provider_response',
        'phone_number',
        'notes',
        'paid_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
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
    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'card' => 'Credit/Debit Card',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            default => ucfirst($this->payment_method)
        };
    }

    // Boot method for auto-generating transaction ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->transaction_id)) {
                $payment->transaction_id = 'TXN-' . strtoupper(uniqid());
            }
        });
    }
}
