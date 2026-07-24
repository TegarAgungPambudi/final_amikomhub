<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-700 via-orange-600 to-orange-900 min-h-screen flex items-center justify-center p-4 md:p-8">
    <div class="w-full max-w-md">
        <div class="bg-white/95 border border-orange-100 rounded-[2rem] shadow-2xl shadow-orange-900/20 p-8 md:p-10">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto mb-5 shadow-xl shadow-orange-500/30">
                    AH
                </div>
                <p class="text-sm uppercase tracking-[0.3em] font-semibold text-orange-500 mb-3">Admin Portal</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2">Selamat Datang</h1>
                <p class="text-slate-500 text-sm">Masuk untuk mengelola event, organizer, dan transaksi.</p>
            </div>

            @if(session('error'))
                <div class="bg-rose-50 text-rose-700 p-4 rounded-2xl mb-6 font-semibold text-sm text-center border border-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 text-rose-700 p-4 rounded-2xl mb-6 font-semibold text-sm text-center border border-rose-200">
                    {{ $errors->first('email') ?? $errors->first('password') ?? $errors->first() }}
                </div>
            @endif

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}"
               class="w-full flex items-center justify-center gap-3 py-3.5 border border-orange-200 rounded-2xl font-bold text-orange-700 text-base hover:bg-orange-50 transition-all duration-200 mb-6">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Masuk dengan Google
            </a>

            <!-- Divider -->
            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0">atau masuk manual</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <!-- Login Form -->
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all duration-200 font-medium text-slate-900 placeholder-slate-400"
                           placeholder="admin@amikom.ac.id" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5" for="password">Password</label>
                    <input id="password" type="password" name="password"
                           class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all duration-200 font-medium text-slate-900 placeholder-slate-400"
                           placeholder="password" required>
                    <label class="mt-2.5 flex items-center gap-2 text-sm font-semibold text-slate-500 cursor-pointer select-none hover:text-slate-700 transition-colors">
                        <input id="showPassword" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-orange-600 focus:ring-orange-500 cursor-pointer">
                        Tampilkan password
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-orange-500 text-white rounded-2xl font-bold text-lg shadow-lg shadow-orange-300/40 hover:bg-orange-600 transition-all duration-200">
                    Masuk
                </button>

                <p class="text-xs text-slate-400 text-center pt-1">Admin default: admin@amikom.ac.id / password</p>
            </form>
        </div>
    </div>

    <script>
        const showPassword = document.getElementById('showPassword');
        const password = document.getElementById('password');
        if (showPassword && password) {
            showPassword.addEventListener('change', function () {
                password.type = this.checked ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>
