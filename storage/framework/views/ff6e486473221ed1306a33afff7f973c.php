<?php $__env->startSection('content'); ?>
    <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-6">
                <img src="<?php echo e($event->poster_url); ?>" alt="<?php echo e($event->title); ?>"
                    class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover">

                <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-slate-800">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold">
                            <?php echo e($event->organization ? strtoupper(substr($event->organization->name, 0, 2)) : ($event->category ? strtoupper(substr($event->category->name, 0, 2)) : '??')); ?>

                        </div>
                        <div>
                            <?php if($event->organization): ?>
                                <a href="<?php echo e(route('organizer.profile', $event->organization->slug)); ?>" class="font-bold text-slate-800 hover:text-orange-600 transition">
                                    <?php echo e($event->organization->name); ?>

                                </a>
                                <p class="text-xs text-slate-500">
                                    Verified Event ·
                                    <a href="<?php echo e(route('organizer.profile', $event->organization->slug)); ?>" class="text-orange-500 hover:underline">Lihat Profil</a>
                                </p>
                            <?php else: ?>
                                <p class="font-bold text-slate-800"><?php echo e($event->category->name ?? 'Kategori tidak ditemukan'); ?></p>
                                <p class="text-xs text-slate-500">Verified Event</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span class="inline-flex px-4 py-1.5 bg-orange-100 text-orange-700 rounded-full text-sm font-bold uppercase tracking-wider">
                    <?php echo e($event->category->name ?? 'Kategori tidak ditemukan'); ?>

                </span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight text-slate-900"><?php echo e($event->title); ?></h1>
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span><?php echo e($event->date ? $event->date->format('l, d M Y') : 'Tanggal belum diatur'); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span><?php echo e($event->location); ?></span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4 text-slate-900">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed">
                    <?php echo e($event->description); ?>

                </p>
            </div>

            <?php $isPast = $event->date && $event->date->isPast(); ?>
        <div class="bg-orange-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-orange-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-orange-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-4xl md:text-5xl font-black">
                            Rp <?php echo e(number_format($event->price, 0, ',', '.')); ?>

                            <span class="text-lg font-medium text-orange-200">/ orang</span>
                        </h2>
                        <p class="mt-4 text-orange-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline"><?php echo e($event->stock); ?> Tiket lagi!</span>
                        </p>
                    </div>
                    <div class="w-full md:w-auto">
                        <?php if($isPast): ?>
                            <div class="inline-flex items-center justify-center px-10 py-5 bg-white/90 text-orange-600 rounded-2xl font-black text-xl shadow-xl relative z-20">
                                Event Telah Selesai
                            </div>
                        <?php else: ?>
                            <button type="button" id="pesanSekarangBtn"
                                class="w-full md:w-auto inline-flex items-center justify-center px-10 py-5 bg-white text-orange-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl cursor-pointer relative z-20">
                                Pesan Sekarang
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-orange-400 opacity-20 rounded-full"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold text-slate-900">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <svg class="w-5 h-5 text-rose-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>

            <div class="space-y-8" id="reviews">
                <h3 class="text-2xl font-black text-slate-900">Ulasan & Penilaian</h3>

                <?php if(session('success')): ?>
                    <div class="bg-green-100 text-green-700 p-4 rounded-xl font-bold text-sm">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="bg-red-100 text-red-600 p-4 rounded-xl font-bold text-sm">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="text-center">
                            <div class="w-32 h-32 rounded-full bg-orange-50 flex items-center justify-center flex-col">
                                <span class="text-4xl font-black text-orange-600"><?php echo e(number_format($avgRating, 1)); ?></span>
                                <div class="flex items-center gap-0.5 mt-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-4 h-4 <?php echo e($i <= round($avgRating) ? 'text-yellow-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 font-medium"><?php echo e($totalReviews); ?> ulasan</p>
                            </div>
                        </div>

                        <div class="flex-1 w-full space-y-2">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <?php
                                    $count = $ratingCounts[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                ?>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-slate-600 w-4"><?php echo e($i); ?></span>
                                    <svg class="w-4 h-4 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: <?php echo e($percentage); ?>%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-500 w-8 text-right"><?php echo e($count); ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <?php if($event->date < now()): ?>
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <h4 class="font-bold text-lg mb-4">
                                <?php echo e($userReview ? 'Edit Ulasan Anda' : 'Berikan Ulasan Anda'); ?>

                            </h4>
                            <form action="<?php echo e(route('reviews.store', $event->id)); ?>" method="POST" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                                    <div class="flex gap-1" id="star-rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <span class="star-btn cursor-pointer transition-all duration-150 <?php echo e($userReview && $userReview->rating >= $i ? 'text-yellow-400' : 'text-slate-300'); ?>"
                                                  data-value="<?php echo e($i); ?>"
                                                  onclick="setRating(<?php echo e($i); ?>)"
                                                  onmouseenter="hoverRating(<?php echo e($i); ?>)"
                                                  onmouseleave="resetRating()">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </span>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="rating" id="rating-input" value="<?php echo e($userReview->rating ?? ''); ?>">
                                    <?php
                                        $initialRating = $userReview->rating ?? 0;
                                    ?>
                                    <script>
                                        var selectedRating = <?php echo e($initialRating); ?>;
                                        function updateStars(rating) {
                                            document.querySelectorAll('.star-btn').forEach(function(btn) {
                                                var val = parseInt(btn.dataset.value);
                                                if (val <= rating) {
                                                    btn.classList.remove('text-slate-300');
                                                    btn.classList.add('text-yellow-400');
                                                } else {
                                                    btn.classList.remove('text-yellow-400');
                                                    btn.classList.add('text-slate-300');
                                                }
                                            });
                                            document.getElementById('rating-input').value = rating;
                                        }
                                        function setRating(rating) {
                                            selectedRating = rating;
                                            updateStars(rating);
                                        }
                                        function hoverRating(rating) {
                                            updateStars(rating);
                                        }
                                        function resetRating() {
                                            if (selectedRating > 0) {
                                                updateStars(selectedRating);
                                            } else {
                                                document.querySelectorAll('.star-btn').forEach(function(btn) {
                                                    btn.classList.remove('text-yellow-400');
                                                    btn.classList.add('text-slate-300');
                                                });
                                                document.getElementById('rating-input').value = '';
                                            }
                                        }
                                        if (selectedRating > 0) {
                                            updateStars(selectedRating);
                                        }
                                    </script>
                                    <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Komentar</label>
                                    <textarea name="comment" rows="4"
                                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium"
                                        placeholder="Bagikan pengalaman Anda menghadiri event ini..."><?php echo e($userReview->comment ?? ''); ?></textarea>
                                    <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <button type="submit"
                                    class="px-8 py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition shadow-lg shadow-orange-200">
                                    <?php echo e($userReview ? 'Perbarui Ulasan' : 'Kirim Ulasan'); ?>

                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 rounded-3xl border border-slate-200 p-8 text-center">
                            <p class="text-slate-500 font-medium">Ulasan dapat diberikan setelah event selesai dilaksanakan.</p>
                            <p class="text-sm text-slate-400 mt-1">Event ini akan berlangsung pada <?php echo e($event->date->format('d M Y')); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-slate-50 rounded-3xl border border-slate-200 p-8 text-center">
                        <p class="text-slate-500 font-medium mb-4">Ingin memberikan ulasan?</p>
                        <a href="<?php echo e(route('auth.google')); ?>"
                           class="inline-flex items-center gap-3 px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl hover:border-orange-300 hover:bg-slate-50 transition-all font-bold text-slate-700">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"></path>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"></path>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
                            </svg>
                            Login dengan Google
                        </a>
                    </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <h4 class="font-bold text-lg text-slate-900">Ulasan Pengguna (<?php echo e($totalReviews); ?>)</h4>
                    <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm shrink-0">
                                    <?php echo e(strtoupper(substr($review->user->name ?? 'U', 0, 1))); ?>

                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1 flex-wrap">
                                        <p class="font-bold text-slate-800"><?php echo e($review->user->name ?? 'Anonymous'); ?></p>
                                        <div class="flex items-center gap-0.5">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-3.5 h-3.5 <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-xs text-slate-400"><?php echo e($review->created_at->diffForHumans()); ?></span>
                                    </div>
                                    <?php if($review->comment): ?>
                                        <p class="text-slate-600"><?php echo e($review->comment); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="font-medium">Belum ada ulasan untuk event ini.</p>
                            <p class="text-sm mt-1">Jadilah yang pertama memberikan ulasan!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <div id="emailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Masukkan Email Anda</h3>
                <p class="text-slate-500 mb-6 text-sm">E-Ticket akan dikirim ke email ini setelah pembayaran berhasil.</p>
                <input type="email" id="modalEmailInput" placeholder="contoh@gmail.com"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium text-center"
                    required>
                <p class="text-red-500 text-sm mt-2 hidden" id="modalEmailError">Email tidak valid.</p>
                <div class="flex gap-3 mt-4">
                    <button type="button" id="modalBatalBtn"
                        class="flex-1 py-4 border-2 border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="button" id="modalLanjutBtn"
                        class="flex-1 py-4 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pesanSekarangBtn = document.getElementById('pesanSekarangBtn');
    const emailModal = document.getElementById('emailModal');
    const modalEmailInput = document.getElementById('modalEmailInput');
    const modalEmailError = document.getElementById('modalEmailError');
    const modalBatalBtn = document.getElementById('modalBatalBtn');
    const modalLanjutBtn = document.getElementById('modalLanjutBtn');

    if (!emailModal || !modalEmailInput || !modalEmailError || !modalBatalBtn || !modalLanjutBtn) {
        return;
    }

    if (pesanSekarangBtn) {
        pesanSekarangBtn.addEventListener('click', function() {
            emailModal.classList.remove('hidden');
            emailModal.classList.add('flex');
            modalEmailInput.value = '';
            modalEmailError.classList.add('hidden');
            modalEmailInput.classList.remove('border-red-500');
            modalEmailInput.classList.add('border-slate-200');
            setTimeout(function() {
                modalEmailInput.focus();
            }, 100);
        });
    }

    modalBatalBtn.addEventListener('click', function() {
        emailModal.classList.add('hidden');
        emailModal.classList.remove('flex');
    });

    emailModal.addEventListener('click', function(e) {
        if (e.target === emailModal) {
            emailModal.classList.add('hidden');
            emailModal.classList.remove('flex');
        }
    });

    modalEmailInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            modalLanjutBtn.click();
        }
    });

    modalLanjutBtn.addEventListener('click', function() {
        const email = modalEmailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email || !emailRegex.test(email)) {
            modalEmailError.classList.remove('hidden');
            modalEmailInput.classList.remove('border-slate-200');
            modalEmailInput.classList.add('border-red-500');
            return;
        }

        modalEmailError.classList.add('hidden');
        modalEmailInput.classList.remove('border-red-500');
        modalEmailInput.classList.add('border-slate-200');

        const checkoutUrl = '<?php echo e(url("checkout/" . $event->id)); ?>?email=' + encodeURIComponent(email);
        window.location.href = checkoutUrl;
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/event-detail.blade.php ENDPATH**/ ?>