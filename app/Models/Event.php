<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'category_id', 
        'title', 
        'description', 
        'date',
        'location', 
        'price', 
        'stock', 
        'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function getPosterUrlAttribute()
    {
        // Jika poster_path kosong, gunakan gambar default
        if (!$this->poster_path) {
            return asset('assets/concert.png');
        }

        // Ambil nama file dari poster_path (misal: "concert.png" atau "posters/concert.png" jadi "concert.png")
        $filename = basename($this->poster_path);

        // PRIORITAS 1: Cek di public/assets/ (gambar statis)
        $assetsPath = 'assets/' . $filename;
        if (file_exists(public_path($assetsPath))) {
            return asset($assetsPath);
        }

        // PRIORITAS 2: Cek di storage/public/ (upload via admin)
        if (Storage::disk('public')->exists($this->poster_path)) {
            return route('media.storage', ['path' => $this->poster_path]);
        }

        // Fallback
        return asset('assets/concert.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function ticketPricings()
    {
        return $this->hasMany(TicketPricing::class);
    }

    /**
     * Get the current active pricing tier
     */
    public function getCurrentPricing()
    {
        $now = now();

        // Check for active tiered pricing
        $activePricing = $this->ticketPricings()
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->orderByRaw("FIELD(tier, 'early_bird', 'presale_1', 'presale_2', 'regular') ASC")
            ->first();

        return $activePricing;
    }

    /**
     * Get current effective price (from tiered pricing or fallback to base price)
     */
    public function getCurrentPrice(): int
    {
        $pricing = $this->getCurrentPricing();
        return $pricing ? (int) $pricing->price : (int) $this->price;
    }

    /**
     * Get all available pricing tiers for display
     */
    public function getAvailableTiers()
    {
        return $this->ticketPricings()
            ->where('is_active', true)
            ->orderByRaw("FIELD(tier, 'early_bird', 'presale_1', 'presale_2', 'regular') ASC")
            ->get();
    }

    /**
     * Hitung rata-rata rating
     */
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Hitung jumlah rating per bintang
     */
    public function ratingCounts(): array
    {
        $counts = [];
        for ($i = 5; $i >= 1; $i--) {
            $counts[$i] = $this->reviews()->where('rating', $i)->count();
        }
        return $counts;
    }
}
