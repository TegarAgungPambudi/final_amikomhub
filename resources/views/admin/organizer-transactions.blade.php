@extends('layouts.admin')

@section('page_title', 'Transaksi Saya')
@section('page_subtitle', 'Data transaksi penjualan tiket')

@section('content')
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
                @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 font-mono text-sm text-slate-500">{{ $trx->order_id }}</td>
                        <td class="px-8 py-6">
                            <p class="font-bold">{{ $trx->customer_name }}</p>
                            <p class="text-xs text-slate-400">{{ $trx->customer_email }}</p>
                        </td>
                        <td class="px-8 py-6 font-medium text-slate-600 max-w-[200px] truncate">{{ $trx->event->title ?? '-' }}</td>
                        <td class="px-8 py-6">
                            @if($trx->status === 'settlement' || $trx->status === 'success')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                            @elseif($trx->status === 'pending')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 font-black text-indigo-600">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td class="px-8 py-6 text-slate-500 text-sm">{{ $trx->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-slate-500">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($transactions, 'links'))
        <div class="p-4 border-t">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection

