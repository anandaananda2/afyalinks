<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'reported_by',
        'category',
        'severity',
        'item_name',
        'description',
        'current_stock',
        'minimum_required',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'current_stock' => 'integer',
            'minimum_required' => 'integer',
            'resolved_at' => 'datetime',
        ];
    }

    // Relationships
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Helper Methods
    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    public function isPending(): bool
    {
        return $this->status === 'reported';
    }

    public function isCritical(): bool
    {
        return $this->severity === 'critical';
    }

    public function getSeverityBadgeColorAttribute()
    {
        return match($this->severity) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    // Boot method for auto-generating report number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (empty($report->report_number)) {
                $report->report_number = 'RPT-' . strtoupper(uniqid());
            }
        });
    }
}