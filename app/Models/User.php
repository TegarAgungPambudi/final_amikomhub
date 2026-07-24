<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'provider', 'provider_id', 'avatar', 'organization_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Agar role dapat dibaca/dipakai pada middleware.
    public function getRoleAttribute(): ?string
    {
        return $this->attributes['role'] ?? null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Cek apakah user adalah organizer
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer' && !is_null($this->organization_id);
    }

    // Cek apakah user adalah superadmin
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relasi ke reviews yang diberikan user
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

