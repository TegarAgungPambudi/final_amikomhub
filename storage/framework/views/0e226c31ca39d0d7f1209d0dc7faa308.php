<?php $__env->startSection('content'); ?>
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-black">Dashboard Ringkasan</h1>
        <p class="text-slate-500 font-medium">Selamat datang kembali, Admin!</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="text-right hidden md:block">
            <p class="font-bold">Admin Super</p>
            <p class="text-xs text-slate-400">Penyelenggara Utama</p>
        </div>
        <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-orange-100 flex items-center justify-center p-1">
            <img src="https://ui-avatars.com/api/?name=Admin+Super&background=f97316&color=fff" class="rounded-xl">
        </div>
    </div>
</header>

<?php if(isset($organizationCount)): ?>
<div class="bg-orange-50 rounded-3xl border border-orange-100 p-6 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-orange-600 uppercase tracking-wider">Multi-Tenant Overview</p>
            <p class="text-xs text-orange-400 mt-1">Superadmin dapat mengelola seluruh organisasi</p>
        </div>
        <div class="flex gap-6">
            <div class="text-center">
                <span class="text-2xl font-black text-orange-600"><?php echo e($organizationCount); ?></span>
                <p class="text-xs text-orange-500 font-bold">Organisasi</p>
            </div>
            <div class="text-center">
                <span class="text-2xl font-black text-orange-600"><?php echo e($organizerCount); ?></span>
                <p class="text-xs text-orange-500 font-bold">Organizer</p>
            </div>
            <a href="<?php echo e(route('admin.organizers.index')); ?>" class="px-4 py-2 bg-orange-500 text-white rounded-xl font-bold text-sm hover:bg-orange-600 transition self-center">
                Kelola Organizer
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black"><?php echo e(number_format($ticketsSold, 0, ',', '.')); ?></h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black"><?php echo e($activeEvents); ?> Event</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black"><?php echo e($pendingOrders); ?> Pesanan</h3>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-[1.3fr_0.7fr] gap-6 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm">
            <h3 class="font-bold text-sm mb-3">📈 Pendapatan Bulanan</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm">
            <h3 class="font-bold text-sm mb-3">🏆 Event Terpopuler</h3>
            <canvas id="eventsChart" height="120"></canvas>
        </div>

        <?php if(isset($userGrowthData)): ?>
        <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm lg:col-span-2">
            <h3 class="font-bold text-sm mb-3">👥 Pertumbuhan Pengguna</h3>
            <canvas id="userGrowthChart" height="80"></canvas>
        </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-3xl border border-orange-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-black text-sm">💬 Chat Ringkas</h3>
                <p class="text-xs text-slate-500">Area obrolan dibuat lebih padat agar mudah dipantau</p>
            </div>
            <span class="text-[11px] bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full font-semibold">Live</span>
        </div>
        <div class="space-y-3 max-h-[280px] overflow-auto pr-1">
            <div class="rounded-2xl bg-orange-50 p-3 text-sm">
                <div class="text-[10px] uppercase tracking-widest text-orange-500 font-black mb-1">Admin</div>
                <p class="text-slate-600">Tiket pending perlu segera diverifikasi.</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-3 text-sm">
                <div class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-1">System</div>
                <p class="text-slate-600">2 event baru siap dipublikasikan hari ini.</p>
            </div>
            <div class="rounded-2xl bg-orange-50 p-3 text-sm">
                <div class="text-[10px] uppercase tracking-widest text-orange-500 font-black mb-1">Admin</div>
                <p class="text-slate-600">Review penonton menurun, cek promosi acara.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl border border-orange-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-orange-100 flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terakhir</h3>
        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-orange-600 font-bold hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-orange-50 text-slate-500 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-orange-50/60 transition">
                        <td class="px-8 py-6">
                            <p class="font-bold uppercase tracking-wide text-sm truncate max-w-[180px]"><?php echo e($trx->customer_name); ?></p>
                            <p class="text-xs text-slate-400 truncate max-w-[180px]"><?php echo e($trx->customer_email); ?></p>
                        </td>
                        <td class="px-8 py-6 font-medium text-slate-600 max-w-[220px] truncate"><?php echo e($trx->event->title ?? '-'); ?></td>
                        <td class="px-8 py-6">
                            <?php if($trx->status === 'settlement' || $trx->status === 'success'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase ring-1 ring-green-200">Success</span>
                            <?php elseif($trx->status === 'pending'): ?>
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase ring-1 ring-orange-200">Pending</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200"><?php echo e($trx->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 font-black text-orange-600">Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-slate-500">Belum ada transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = {
        orange: '#f97316',
        orangeLight: 'rgba(249, 115, 22, 0.2)',
        green: '#22c55e',
        amber: '#f59e0b',
        rose: '#f43f5e',
        purple: '#8b5cf6',
    };

    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlyLabels ?? []); ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?php echo json_encode($monthlyRevenue ?? []); ?>,
                    backgroundColor: chartColors.orangeLight,
                    borderColor: chartColors.orange,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (value) => 'Rp ' + value.toLocaleString('id-ID') }
                    }
                }
            }
        });
    }

    const eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($eventLabels ?? []); ?>,
                datasets: [{
                    data: <?php echo json_encode($eventData ?? []); ?>,
                    backgroundColor: ['#f97316', '#22c55e', '#f59e0b', '#f43f5e', '#8b5cf6'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10 }, padding: 12 }
                    }
                }
            }
        });
    }

    const userGrowthCtx = document.getElementById('userGrowthChart');
    if (userGrowthCtx) {
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($userGrowthLabels ?? []); ?>,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: <?php echo json_encode($userGrowthData ?? []); ?>,
                    borderColor: chartColors.orange,
                    backgroundColor: chartColors.orangeLight,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: chartColors.orange,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>