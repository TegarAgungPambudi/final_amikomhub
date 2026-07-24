<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizerRegistrationController extends Controller
{
    public function showForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login', ['redirect' => route('organizer.register')]);
        }

        $user = auth()->user();

        if ($user->isOrganizer() || $user->isSuperAdmin()) {
            return redirect()->route($user->isSuperAdmin() ? 'admin.dashboard' : 'admin.organizer.dashboard');
        }

        return view('organizer.register');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->isOrganizer() || $user->isSuperAdmin()) {
            return redirect()->route($user?->isSuperAdmin() ? 'admin.dashboard' : 'admin.organizer.dashboard');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:organizations,slug'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        $baseSlug = $slug;
        $counter = 2;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $organization = Organization::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'owner_id' => $user->id,
            'is_active' => true,
        ]);

        $user->update([
            'role' => 'organizer',
            'organization_id' => $organization->id,
        ]);

        return redirect()->route('admin.organizer.dashboard')
            ->with('success', 'Organisasi berhasil dibuat. Anda sekarang bisa mengelola acara dan melihat dashboard khusus organisasi.');
    }
}
