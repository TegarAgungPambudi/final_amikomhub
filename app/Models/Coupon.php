<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'event_id',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if coupon is valid and applicable for given amount
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isValid(int $orderAmount = 0, ?int $eventId = null): bool
    {
        if (!$this->is_active) return false;

        if ($this->event_id && $eventId !== null && $this->event_id !== $eventId) {
            return false;
        }

        // Check max uses
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) return false;

        // Check expiry
        if ($this->expires_at && $this->expires_at->isPast()) return false;

        // Check start date
        if ($this->starts_at && $this->starts_at->isFuture()) return false;

        // Check minimum order amount
        if ($this->min_order_amount > 0 && $orderAmount < $this->min_order_amount) return false;

        return true;
    }

    /**
     * Calculate discount amount for given order total
     */
    public function calculateDiscount(int $orderAmount): int
    {
        if ($this->discount_type === 'percent') {
            return (int) round($orderAmount * $this->discount_value / 100);
        }

        // Fixed discount
        return min($this->discount_value, $orderAmount);
    }
}

