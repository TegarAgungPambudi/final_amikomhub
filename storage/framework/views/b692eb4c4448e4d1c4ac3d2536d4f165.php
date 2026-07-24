<?php $__env->startSection('content'); ?>

    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-orange-100 text-orange-700 rounded-full text-sm font-bold uppercase tracking-wider">#1
                Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-orange-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan
                Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#category-section"
                    class="px-8 py-4 bg-orange-500 text-white rounded-2xl font-bold text-lg shadow-xl shadow-orange-200 hover:bg-orange-600 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#"
                    class="px-8 py-4 border-2 border-orange-100 rounded-2xl font-bold text-lg text-orange-700 hover:border-orange-400 hover:bg-orange-50 hover:text-orange-800 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <img src="<?php echo e(asset('assets/concert.png')); ?>" alt="Concert"
                class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
        </div>

        <div id="category-section" class="mb-12 flex flex-wrap gap-3 justify-center items-center px-4 py-8 bg-gradient-to-r from-slate-50 to-orange-50 rounded-2xl border border-slate-200">
            <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">Filter by:</span>
            
            <a href="/" 
               class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 transform hover:scale-105 <?php echo e(!request('category') ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-white text-slate-700 border border-slate-300 hover:border-orange-400 hover:bg-orange-50'); ?>">
                ✓ Semua Kategori
            </a>
            
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="/?category=<?php echo e($cat->slug); ?>" 
                    class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 transform hover:scale-105 <?php echo e(request('category') === $cat->slug ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-white text-slate-700 border border-slate-300 hover:border-orange-400 hover:bg-orange-50'); ?>">
                    <?php echo e($cat->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($upcomingEvents->count() > 0): ?>
            <h3 class="text-2xl font-black mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Akan Datang
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.event-card', ['event' => $event], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-slate-50 rounded-3xl mb-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-xl font-bold text-slate-400 mb-2">Belum ada event mendatang</p>
                <p class="text-slate-400">Tetap pantau terus untuk event-event seru selanjutnya!</p>
            </div>
        <?php endif; ?>

        <?php if($pastEvents->count() > 0): ?>
            <h3 class="text-2xl font-black mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Event Sebelumnya
                <span class="text-sm font-medium text-slate-400 font-normal">(Lihat ulasan & rating)</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $pastEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.event-card', ['event' => $event], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Jelajahi Penyelenggara -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Jelajahi Penyelenggara</h2>
                <p class="text-slate-500 font-medium">Lihat rekam jejak dan rating dari berbagai organisasi penyelenggara event</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('organizer.profile', $org->slug)); ?>" class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-orange-200 transition-all duration-300 p-8 block">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform">
                            <?php echo e(strtoupper(substr($org->name, 0, 2))); ?>

                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg group-hover:text-orange-600 transition truncate"><?php echo e($org->name); ?></h3>
                            <p class="text-xs text-slate-400"><?php echo e($org->total_events); ?> Event</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <div class="flex items-center gap-1">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-4 h-4 <?php echo e($i <= round($org->avg_rating) ? 'text-yellow-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm font-bold <?php echo e($org->total_reviews > 0 ? 'text-orange-600' : 'text-slate-400'); ?>">
                            <?php echo e($org->total_reviews > 0 ? number_format($org->avg_rating, 1) . ' (' . $org->total_reviews . ' ulasan)' : 'Belum ada ulasan'); ?>

                        </span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-400 font-medium">Belum ada penyelenggara terdaftar</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Mitra Terpercaya Kami</h2>
                <p class="text-slate-500 font-medium">Kerjasama strategis dengan berbagai organisasi terkemuka di Indonesia</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-orange-200 transition-all duration-300 p-6 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-xl mb-4 flex items-center justify-center overflow-hidden group-hover:bg-orange-50 transition p-2">
                        <img src="<?php echo e($partner->logo_url); ?>" 
                             alt="<?php echo e($partner->name); ?>"
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                             onerror="this.onerror=null;this.src='<?php echo e(asset('assets/partner-placeholder.svg')); ?>'">
                    </div>
                    
                    <h3 class="font-bold text-slate-800 mb-1"><?php echo e($partner->name); ?></h3>
                    
                    <p class="text-xs text-slate-400 mb-3">
                        Bergabung <?php echo e($partner->created_at->format('M Y')); ?>

                    </p>
                    
                    <div class="pt-3 border-t w-full">
                        <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Partner Resmi</p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-400 font-medium">Belum ada mitra yang terdaftar</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/welcome.blade.php ENDPATH**/ ?>