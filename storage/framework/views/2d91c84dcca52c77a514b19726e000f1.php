<?php $__env->startSection('content'); ?>
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Kelola Partner</h1>
            <p class="text-slate-500 font-medium">Atur dan kelola mitra platform digital Anda di sini.</p>
        </div>
        <a href="<?php echo e(route('admin.partners.create')); ?>"
            class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
            + Tambah Partner Baru
        </a>
    </header>

    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <form method="GET" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="<?php echo e($search); ?>"
                       placeholder="Cari nama partner..."
                       class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Cari
                </button>
                <?php if($search): ?>
                    <a href="<?php echo e(route('admin.partners.index')); ?>" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300 transition">
                        Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Logo</th>
                        <th class="px-8 py-4">Nama Partner</th>
                        <th class="px-8 py-4">Bergabung</th>
                        <th class="px-8 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    <?php $__empty_1 = true; $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400"><?php echo e(($partners->currentPage() - 1) * $partners->perPage() + $index + 1); ?></td>
                        <td class="px-8 py-6">
                            <img src="<?php echo e($partner->logo_url); ?>" alt="<?php echo e($partner->name); ?>" 
                                 class="w-16 h-16 rounded-xl object-contain shadow-sm bg-slate-100 p-1"
                                 onerror="this.onerror=null;this.src='<?php echo e(asset('assets/partner-placeholder.svg')); ?>'">
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-800"><?php echo e($partner->name); ?></p>
                            <p class="text-xs text-slate-400">ID: <?php echo e($partner->id); ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm text-slate-600"><?php echo e($partner->created_at->format('d M Y')); ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('admin.partners.edit', $partner->id)); ?>"
                                    class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition"
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="<?php echo e(route('admin.partners.destroy', $partner->id)); ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus partner ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400">
                            <p class="font-medium">Tidak ada partner yang ditemukan</p>
                        </td>
                    </tr>
        
        <?php if($partners->hasPages()): ?>
        <div class="px-8 py-6 border-t">
            <?php echo e($partners->links()); ?>

        </div>
        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/admin/partners/index.blade.php ENDPATH**/ ?>