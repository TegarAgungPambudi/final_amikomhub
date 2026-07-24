<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fffaf5 0%, #ffffff 100%);
            color: #111827;
            scroll-behavior: smooth;
        }

        .glass {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(10px);
        }

        .bg-indigo-50 { background-color: #fff7ed !important; }
        .bg-indigo-100 { background-color: #ffedd5 !important; }
        .bg-indigo-200 { background-color: #fed7aa !important; }
        .bg-indigo-400 { background-color: #fb923c !important; }
        .bg-indigo-500 { background-color: #f97316 !important; }
        .bg-indigo-600 { background-color: #ea580c !important; }
        .bg-indigo-700 { background-color: #c2410b !important; }
        .bg-indigo-900 { background-color: #7c2d12 !important; }

        .text-indigo-100 { color: #f97316 !important; }
        .text-indigo-200 { color: #fb923c !important; }
        .text-indigo-400 { color: #fb923c !important; }
        .text-indigo-500, .text-indigo-600, .text-indigo-700 { color: #ea580c !important; }
        .text-indigo-900 { color: #7c2d12 !important; }

        .border-indigo-100 { border-color: #ffedd5 !important; }
        .border-indigo-200 { border-color: #fed7aa !important; }
        .border-indigo-400 { border-color: #fb923c !important; }
        .border-indigo-500 { border-color: #f97316 !important; }
        .border-indigo-600 { border-color: #ea580c !important; }

        .hover\:bg-indigo-50:hover { background-color: #fff7ed !important; }
        .hover\:bg-indigo-100:hover { background-color: #ffedd5 !important; }
        .hover\:bg-indigo-600:hover { background-color: #ea580c !important; }
        .hover\:bg-indigo-700:hover { background-color: #c2410b !important; }
        .hover\:text-indigo-600:hover { color: #ea580c !important; }
        .hover\:border-indigo-400:hover { border-color: #fb923c !important; }

        .shadow-indigo-100 { box-shadow: 0 25px 50px -12px rgba(249, 115, 22, 0.15) !important; }
        .shadow-indigo-200 { box-shadow: 0 25px 50px -12px rgba(249, 115, 22, 0.2) !important; }
    </style>
</head>

<body class="bg-orange-50/70 text-slate-900">

    <nav
        class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-orange-100 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    AH</div>
                <span class="text-xl font-bold tracking-tight text-slate-800">AmikomEventHub</span>
            </a>
        </div>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="/" class="text-orange-600 font-semibold">Jelajahi</a>
            <a href="#category-section" class="hover:text-orange-600 transition">Kategori</a>
            <a href="#" class="hover:text-orange-600 transition">Tentang Kami</a>
        </div>
        <div class="flex gap-3 items-center">
            @auth
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isOrganizer())
                    <a href="{{ auth()->user()->isOrganizer() ? route('admin.organizer.dashboard') : route('admin.dashboard') }}"
                       class="px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-orange-200 hover:bg-orange-600 transition text-sm">
                        Dashboard
                    </a>
                @endif

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="" class="w-8 h-8 rounded-full">
                        @else
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-sm font-medium text-slate-700 hidden md:block">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-slate-500 hover:text-orange-600 transition font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="https://wa.me/628977108130?text=Halo%20Admin%2C%20saya%20ingin%20mendaftar%20sebagai%20organizer" target="_blank" rel="noopener noreferrer"
                   class="px-5 py-2.5 rounded-xl border border-orange-200 text-orange-700 hover:bg-orange-100 transition text-sm font-semibold">
                    Daftar Organisasi
                </a>
                @if(!request()->routeIs('login'))
                    <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                       class="px-5 py-2.5 rounded-xl font-semibold hover:bg-orange-100 transition text-sm text-slate-700">
                        Login
                    </a>
                    <a href="{{ route('auth.google', ['redirect' => request()->fullUrl()]) }}"
                       class="px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-orange-200 hover:bg-orange-600 transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                            <path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>
                @endif
            @endauth
        </div>
    </nav>

    @yield('content')

    @yield('scripts')

    <footer class="bg-gradient-to-br from-orange-600 to-orange-500 text-orange-50 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-1">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-600 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-orange-100">Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Category</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="hover:text-white transition">Semua Kategori</a></li>
                @foreach($categories as $cat)
                    <li><a href="/?category={{ $cat->slug }}" class="hover:text-white transition">{{ $cat->name }}</a></li>
                @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="hover:text-white transition">Home</a></li>
                    <li><a href="#" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="#" class="hover:text-white transition">Cara Bayar</a></li>
                    <li><a href="#" class="hover:text-white transition">Entahlah</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-orange-400/40 text-center text-orange-100 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>
