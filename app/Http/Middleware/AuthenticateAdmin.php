<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Paksa login dulu
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Validasi role admin
        $user = Auth::user();
        if (($user->role ?? null) !== 'admin') {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

