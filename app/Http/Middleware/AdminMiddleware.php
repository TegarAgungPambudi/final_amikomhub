<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        if (!$user || ($user->role ?? null) !== 'admin') {
            // Jika user bukan superadmin, arahkan ke login admin.
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

