

<?php $__env->startSection('page_title', 'Check-in Scanner'); ?>
<?php $__env->startSection('page_subtitle', 'Scan QR Code tiket peserta untuk verifikasi check-in.'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Scanner & Stats -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm text-center">
                <p class="text-2xl font-black text-indigo-600"><?php echo e($totalTickets); ?></p>
                <p class="text-xs text-slate-500 font-bold uppercase">Total Tiket Valid</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm text-center">
                <p class="text-2xl font-black text-green-600"><?php echo e($validTickets); ?></p>
                <p class="text-xs text-slate-500 font-bold uppercase">Belum Check-in</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm text-center">
                <p class="text-2xl font-black text-amber-600"><?php echo e($usedTickets); ?></p>
                <p class="text-xs text-slate-500 font-bold uppercase">Sudah Check-in</p>
            </div>
        </div>

        <!-- QR Scanner Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-lg font-black mb-2">Scan QR Code</h3>
            <p class="text-sm text-slate-500 mb-6">Arahkan kamera ke QR Code pada E-Ticket.</p>
            
            <div id="reader" class="w-full max-w-md mx-auto rounded-2xl overflow-hidden border-2 border-dashed border-slate-200"></div>
            
            <div id="scan-result" class="mt-6 hidden">
                <div id="scan-loading" class="text-center py-8">
                    <div class="animate-spin w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full mx-auto mb-3"></div>
                    <p class="text-slate-500 font-medium">Memverifikasi tiket...</p>
                </div>
                <div id="scan-success" class="hidden bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h4 class="text-2xl font-black text-green-700 mb-2">✓ Check-in Berhasil!</h4>
                    <div id="success-details" class="text-left bg-white rounded-xl p-4 mt-4 space-y-2 text-sm">
                        <p><span class="font-bold">Nama:</span> <span id="s-name"></span></p>
                        <p><span class="font-bold">Email:</span> <span id="s-email"></span></p>
                        <p><span class="font-bold">Event:</span> <span id="s-event"></span></p>
                        <p><span class="font-bold">Tanggal:</span> <span id="s-date"></span></p>
                        <p><span class="font-bold">Jumlah:</span> <span id="s-qty"></span> tiket</p>
                        <p><span class="font-bold">Waktu Scan:</span> <span id="s-time"></span></p>
                    </div>
                </div>
                <div id="scan-error" class="hidden bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h4 class="text-2xl font-black text-red-700 mb-2">✗ Verifikasi Gagal</h4>
                    <p id="error-message-text" class="text-red-600 font-medium"></p>
                </div>
            </div>
        </div>

        <!-- Manual Check-in -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-lg font-black mb-2">⌨️ Manual Check-in</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan Order ID tiket untuk check-in manual.</p>
            
            <form action="<?php echo e(route('admin.checkin.manual')); ?>" method="POST" class="flex gap-3">
                <?php echo csrf_field(); ?>
                <input type="text" name="order_id" placeholder="Masukkan Order ID (contoh: ORDER-20260706-xxxxxx)" 
                       class="flex-1 px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium font-mono text-sm"
                       required>
                <button type="submit" 
                        class="px-6 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Verifikasi
                </button>
            </form>
            
            <?php if(session('success')): ?>
                <div class="mt-4 bg-green-100 text-green-700 p-4 rounded-xl font-bold text-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mt-4 bg-red-100 text-red-600 p-4 rounded-xl font-bold text-sm">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Recent Check-ins -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sticky top-32">
            <h3 class="font-black mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Check-in Terbaru
            </h3>
            <div class="space-y-3 max-h-[500px] overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $recentCheckins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="font-bold text-sm text-slate-800 truncate"><?php echo e($checkin->customer_name); ?></p>
                        <p class="text-xs text-slate-400 truncate"><?php echo e($checkin->event->title ?? '-'); ?></p>
                        <p class="text-[10px] text-green-600 font-bold mt-1">
                            <?php echo e($checkin->used_at ? $checkin->used_at->format('H:i:s') : '-'); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada check-in</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const html5QrCode = new Html5Qrcode("reader");
    let isScanning = false;

    function onScanSuccess(decodedText, decodedResult) {
        if (isScanning) return;
        isScanning = true;

        // Show result container
        const resultDiv = document.getElementById('scan-result');
        resultDiv.classList.remove('hidden');
        document.getElementById('scan-loading').classList.remove('hidden');
        document.getElementById('scan-success').classList.add('hidden');
        document.getElementById('scan-error').classList.add('hidden');

        // Stop scanner temporarily
        html5QrCode.stop().catch(() => {});

        // Send to server for verification
        fetch('<?php echo e(route("admin.checkin.verify")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('scan-loading').classList.add('hidden');

            if (data.success) {
                document.getElementById('scan-success').classList.remove('hidden');
                document.getElementById('s-name').textContent = data.transaction.customer_name;
                document.getElementById('s-email').textContent = data.transaction.customer_email;
                document.getElementById('s-event').textContent = data.transaction.event_title;
                document.getElementById('s-date').textContent = data.transaction.event_date;
                document.getElementById('s-qty').textContent = data.transaction.quantity;
                document.getElementById('s-time').textContent = data.transaction.used_at;

                // Play success sound
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.frequency.value = 800;
                    gain.gain.value = 0.3;
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.15);
                    setTimeout(() => {
                        const osc2 = audioCtx.createOscillator();
                        const gain2 = audioCtx.createGain();
                        osc2.connect(gain2);
                        gain2.connect(audioCtx.destination);
                        osc2.frequency.value = 1200;
                        gain2.gain.value = 0.3;
                        osc2.start();
                        osc2.stop(audioCtx.currentTime + 0.2);
                    }, 150);
                } catch(e) {}

                // Auto restart scanner after 3 seconds
                setTimeout(() => {
                    resultDiv.classList.add('hidden');
                    startScanner();
                    isScanning = false;
                }, 3000);
            } else {
                document.getElementById('scan-error').classList.remove('hidden');
                document.getElementById('error-message-text').textContent = data.message;

                // Show transaction details if available
                if (data.transaction) {
                    document.getElementById('error-message-text').innerHTML += 
                        '<br><span class="text-sm">' + 
                        (data.transaction.customer_name ? 'Nama: ' + data.transaction.customer_name : '') + 
                        '</span>';
                }

                // Auto restart scanner after 2.5 seconds
                setTimeout(() => {
                    resultDiv.classList.add('hidden');
                    startScanner();
                    isScanning = false;
                }, 2500);
            }
        })
        .catch(error => {
            document.getElementById('scan-loading').classList.add('hidden');
            document.getElementById('scan-error').classList.remove('hidden');
            document.getElementById('error-message-text').textContent = 'Koneksi error: ' + error.message;
            isScanning = false;
        });
    }

    function startScanner() {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        ).catch(err => {
            console.warn('Camera error:', err);
            document.getElementById('reader').innerHTML = `
                <div class="text-center py-8 text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <p>Tidak dapat mengakses kamera.</p>
                    <p class="text-sm mt-1">Gunakan form manual check-in di bawah.</p>
                </div>
            `;
        });
    }

    startScanner();
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/admin/checkin-scanner.blade.php ENDPATH**/ ?>