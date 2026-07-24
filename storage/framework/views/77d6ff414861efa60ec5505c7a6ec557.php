<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fff7ed 0%, #fffaf5 100%);
        }
    </style>
</head>

<body class="bg-orange-50/70 text-slate-900 flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-orange-600 to-orange-500 text-orange-50 flex flex-col p-6 space-y-8 sticky top-0 h-screen shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-600 font-bold text-xl">
                AH
            </div>
            <span class="text-xl font-bold text-white tracking-tight">AmikomEventHub</span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-orange-100/80 mb-4 px-2">Main Menu</p>

            <?php if(auth()->user()?->isOrganizer()): ?>
                <a href="<?php echo e(route('admin.organizer.dashboard')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.organizer.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.organizer.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4h4v4a1 1 0 001 1h3a1 1 0 001-1V10"></path>
                    </svg>
                    Dashboard Organisasi
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.dashboard') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>

            <?php endif; ?>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <a href="<?php echo e(route('admin.events.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.events.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.events.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Kelola Event
                </a>

                <a href="<?php echo e(route('admin.partners.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.partners.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.partners.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                    </svg>
                    Kelola Partner
                </a>

                <a href="<?php echo e(route('admin.categories.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.categories.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.categories.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    Kelola Kategori
                </a>

                <a href="<?php echo e(route('admin.transactions.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.transactions.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.transactions.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan Transaksi
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('admin.organizer.events')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.organizer.events') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.organizer.events') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Kelola Event
                </a>

                <a href="<?php echo e(route('admin.organizer.transactions')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.organizer.transactions') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.organizer.transactions') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan Transaksi
                </a>
            <?php endif; ?>

            <a href="<?php echo e(route('admin.checkin.scanner')); ?>"
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.checkin.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.checkin.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Check-in Scanner
            </a>

            <a href="<?php echo e(route('admin.coupons.index')); ?>"
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.coupons.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.coupons.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kode Kupon
            </a>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <p class="text-[10px] font-bold uppercase tracking-widest text-orange-100/80 mb-2 mt-6 px-2">Superadmin</p>

                <a href="<?php echo e(route('admin.organizers.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('admin.organizers.*') ? 'bg-white/20 text-white' : 'hover:bg-white/15'); ?> rounded-xl font-bold transition">
                    <svg class="w-5 h-5 <?php echo e(request()->routeIs('admin.organizers.*') ? 'text-orange-100' : 'text-orange-100/80'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Kelola Organizer
                </a>
            <?php endif; ?>
        </nav>

        <div class="pt-6 border-t border-orange-200/50">
            <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-orange-100 hover:text-white transition font-medium text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto w-full">
        <header class="flex justify-between items-center mb-8 w-full col-span-full">
            <div>
                <h1 class="text-3xl font-black"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                <p class="text-slate-500 font-medium"><?php echo $__env->yieldContent('page_subtitle', 'Selamat datang kembali, Admin!'); ?></p>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="font-bold">Admin</p>
                    <p class="text-xs text-slate-400">Penyelenggara Utama</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-orange-100 flex items-center justify-center p-1">
                    <img src="https://ui-avatars.com/api/?name=admin&background=f97316&color=fff" class="rounded-xl" alt="Admin Avatar">
                </div>
            </div>
        </header>

        <?php if(session('success')): ?>
            <div class="bg-orange-50 text-orange-700 p-4 rounded-xl mb-6 font-bold text-sm border border-orange-100">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/layouts/admin.blade.php ENDPATH**/ ?>