

<?php $__env->startSection('page_title', 'Event Saya'); ?>
<?php $__env->startSection('page_subtitle', 'Daftar event milik organisasi Anda'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Kategori</th>
                    <th class="px-8 py-4">Tanggal</th>
                    <th class="px-8 py-4">Harga</th>
                    <th class="px-8 py-4">Stok</th>
                    <th class="px-8 py-4">Rating</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 font-bold"><?php echo e($event->title); ?></td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold">
                                <?php echo e($event->category->name ?? '-'); ?>

                            </span>
                        </td>
                        <td class="px-8 py-6 text-slate-600"><?php echo e($event->date ? $event->date->format('d M Y') : '-'); ?></td>
                        <td class="px-8 py-6 font-black text-indigo-600">Rp <?php echo e(number_format($event->price, 0, ',', '.')); ?></td>
                        <td class="px-8 py-6"><?php echo e($event->stock); ?></td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-1">
                                <?php $avg = $event->averageRating(); ?>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $avg): ?>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php else: ?>
                                        <svg class="w-4 h-4 text-slate-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="text-xs text-slate-400 ml-1">(<?php echo e($event->reviews()->count()); ?> ulasan)</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-slate-500">Belum ada event</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if(method_exists($events, 'links')): ?>
        <div class="p-4 border-t"><?php echo e($events->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/admin/organizer-events.blade.php ENDPATH**/ ?>