@extends('layouts.admin')

@section('page_title', 'Kelola Kupon')
@section('page_subtitle', 'Buat dan atur kode diskon untuk event Anda.')

@section('content')
<div class="mb-4 text-right">
    <a href="{{ route('admin.coupons.create') }}" 
       class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
        + Tambah Kupon Baru
    </a>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4">Kode</th>
                    <th class="px-8 py-4">Diskon</th>
                    <th class="px-8 py-4">Pemakaian</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Masa Berlaku</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($coupons as $index => $coupon)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400">
                            {{ $coupons->firstItem() + $index }}
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-800 font-mono">{{ $coupon->code }}</p>
                            @if($coupon->name)
                                <p class="text-xs text-slate-400">{{ $coupon->name }}</p>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($coupon->discount_type === 'percent')
                                <span class="font-bold text-indigo-600">{{ $coupon->discount_value }}%</span>
                            @else
                                <span class="font-bold text-indigo-600">Rp {{ number_format($coupon->discount_value, 0, ',', '.') }}</span>
                            @endif
                            @if($coupon->min_order_amount > 0)
                                <p class="text-[10px] text-slate-400 mt-1">Min. Rp {{ number_format($coupon->min_order_amount, 0, ',', '.') }}</p>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-medium">
                                {{ $coupon->max_uses > 0 ? $coupon->used_count . ' / ' . $coupon->max_uses : $coupon->used_count . ' / ∞' }}
                            </p>
                        </td>
                        <td class="px-8 py-6">
                            @if($coupon->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase ring-1 ring-green-200">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs text-slate-500">
                                @if($coupon->starts_at)
                                    {{ $coupon->starts_at->format('d M Y') }}
                                @else
                                    Sejak awal
                                @endif
                                → 
                                @if($coupon->expires_at)
                                    {{ $coupon->expires_at->format('d M Y') }}
                                @else
                                    Tidak terbatas
                                @endif
                            </p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                   class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kupon {{ $coupon->code }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-10 text-center text-slate-500 font-medium">
                            Belum ada kupon diskon. <a href="{{ route('admin.coupons.create') }}" class="text-indigo-600 font-bold hover:underline">Buat kupon pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection

