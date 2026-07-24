<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'tier',
        'tier_name',
        'price',
        'stock',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if this pricing tier is currently active based on dates
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) return false;

        $now = now();

        if ($this->start_date && $this->start_date->gt($now)) return false;
        if ($this->end_date && $this->end_date->lt($now)) return false;

        return true;
    }
}

