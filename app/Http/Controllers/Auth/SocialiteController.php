<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle(Request $request)
    {
        if ($request->filled('redirect')) {
            session(['login_redirect' => $request->get('redirect')]);
        } else {
            session()->forget('login_redirect');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback(Request $request)
    {
        // Cek jika ada error dari Google (user membatalkan)
        if ($request->has('error')) {
            return redirect()->route('login')
                ->with('error', 'Login Google dibatalkan.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());

            $errorMsg = 'Gagal terhubung ke Google. ';

            if (str_contains($e->getMessage(), 'redirect_uri_mismatch')) {
                $errorMsg .= 'Redirect URI tidak cocok. Pastikan Authorized redirect URIs di Google Cloud Console sudah termasuk: http://127.0.0.1:8000/auth/google/callback';
            } else {
                $errorMsg .= $e->getMessage();
            }

            return redirect()->route('login')
                ->with('error', $errorMsg);
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'Gagal mendapatkan data email dari Google.');
        }

        // Cari user berdasarkan provider_id terlebih dahulu, baru email
        $user = User::where('provider_id', $googleUser->getId())->first();

        if (!$user) {
            // Cari berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User sudah ada, link dengan Google
                $user->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Buat user baru — role 'user' untuk pengguna biasa
                $user = User::create([
                    'name' => $googleUser->getName()
                        ?? $googleUser->getNickname()
                        ?? 'User Google',
                    'email' => $googleUser->getEmail(),
                    'password' => null,
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'user',
                ]);
            }
        } else {
            // Update avatar dan nama jika berubah
            $user->update([
                'avatar' => $googleUser->getAvatar(),
                'name' => $googleUser->getName() ?? $user->name,
            ]);
        }

        // Login user
        Auth::login($user, true);
        $request->session()->regenerate();

        $redirectTarget = session('login_redirect', route('home'));
        session()->forget('login_redirect');

        // Redirect berdasarkan role
        if ($user->isSuperAdmin()) {
            return redirect()->to(
                $redirectTarget === route('home')
                    ? route('admin.dashboard')
                    : $redirectTarget
            );
        }

        if ($user->isOrganizer()) {
            return redirect()->to(
                $redirectTarget === route('home')
                    ? route('admin.organizer.dashboard')
                    : $redirectTarget
            );
        }

        // User biasa
        return redirect()->to($redirectTarget);
    }
}