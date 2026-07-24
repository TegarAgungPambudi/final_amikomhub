<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'quantity',
        'status',
        'snap_token',
        'is_used',
        'used_at',
        'qr_code',
        'reserved_until',
        'coupon_code',
        'discount_amount',
        'original_price',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'reserved_until' => 'datetime',
    ];

    // Relasi: Satu transaksi memiliki satu event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
