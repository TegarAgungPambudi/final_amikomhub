

<?php $__env->startSection('page_title', 'Transaksi Saya'); ?>
<?php $__env->startSection('page_subtitle', 'Data transaksi penjualan tiket'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Order ID</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 font-mono text-sm text-slate-500"><?php echo e($trx->order_id); ?></td>
                        <td class="px-8 py-6">
                            <p class="font-bold"><?php echo e($trx->customer_name); ?></p>
                            <p class="text-xs text-slate-400"><?php echo e($trx->customer_email); ?></p>
                        </td>
                        <td class="px-8 py-6 font-medium text-slate-600 max-w-[200px] truncate"><?php echo e($trx->event->title ?? '-'); ?></td>
                        <td class="px-8 py-6">
                            <?php if($trx->status === 'settlement' || $trx->status === 'success'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                            <?php elseif($trx->status === 'pending'): ?>
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase"><?php echo e($trx->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 font-black text-indigo-600">Rp <?php echo e(number_format($trx->total_price, 0, ',', '.')); ?></td>
                        <td class="px-8 py-6 text-slate-500 text-sm"><?php echo e($trx->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-slate-500">Belum ada transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if(method_exists($transactions, 'links')): ?>
        <div class="p-4 border-t"><?php echo e($transactions->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/admin/organizer-transactions.blade.php ENDPATH**/ ?>