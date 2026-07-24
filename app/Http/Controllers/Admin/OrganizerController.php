<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizerController extends Controller
{
    /**
     * Display list of all organizers (superadmin only)
     */
    public function index()
    {
        $organizers = User::whereIn('role', ['organizer', 'admin'])
            ->with('organization')
            ->latest()
            ->get();

        $organizations = Organization::all();

        return view('admin.organizers.index', compact('organizers', 'organizations'));
    }

    /**
     * Show form to create new organizer
     */
    public function create()
    {
        $organizations = Organization::all();
        return view('admin.organizers.create', compact('organizations'));
    }

    /**
     * Store a new organizer account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required|in:organizer,admin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'organization_id' => $validated['organization_id'],
        ]);

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Akun organizer berhasil dibuat.');
    }

    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        $organizations = Organization::all();
        return view('admin.organizers.edit', compact('user', 'organizations'));
    }

    /**
     * Update organizer account
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required|in:organizer,admin',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'organization_id' => $validated['organization_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Akun organizer berhasil diperbarui.');
    }

    /**
     * Delete organizer account
     */
    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus akun Superadmin.');
        }

        $user->delete();
        return redirect()->route('admin.organizers.index')
            ->with('success', 'Akun organizer berhasil dihapus.');
    }
}

