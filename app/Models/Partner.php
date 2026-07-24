<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    protected $fillable = ['name', 'logo_url'];

    public function getLogoUrlAttribute($value): string
    {
        if (!$value) {
            return asset('assets/partner-placeholder.svg');
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        $path = ltrim($value, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if (Storage::disk('public')->exists($path)) {
            return route('media.storage', ['path' => $path]);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('assets/partner-placeholder.svg');
    }
}
