@extends('layouts.app')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        @if($transaction->status === 'settlement')
            <div class="mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-green-600 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-slate-500 text-lg">E-Ticket Anda akan dikirim ke email dalam beberapa saat.</p>
        @elseif($transaction->status === 'pending')
            <div class="mb-6">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-yellow-600 mb-2">Pembayaran Tertunda</h1>
            <p class="text-slate-500 text-lg">Silakan selesaikan pembayaran Anda</p>
        @else
            <div class="mb-6">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-red-600 mb-2">Pembayaran Gagal</h1>
            <p class="text-slate-500 text-lg">Silakan coba kembali</p>
        @endif
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm mb-8">
        <h3 class="text-xl font-bold mb-6 border-b pb-4">Detail Pesanan</h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-slate-600">Order ID</span>
                <span class="font-bold">{{ $transaction->order_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Event</span>
                <span class="font-bold">{{ $transaction->event?->title ?? 'Event tidak tersedia' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Nama Pemesan</span>
                <span class="font-bold">{{ $transaction->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Email</span>
                <span class="font-bold">{{ $transaction->customer_email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">No. WhatsApp</span>
                <span class="font-bold">{{ $transaction->customer_phone }}</span>
            </div>
            <div class="flex justify-between pt-4 border-t">
                <span class="text-slate-600">Total Bayar</span>
                <span class="font-bold text-orange-600 text-lg">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Status</span>
                <span class="font-bold text-sm px-3 py-1 rounded-full {{ $transaction->status === 'settlement' ? 'bg-green-100 text-green-700' : ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="text-center">
        <p class="text-slate-500 mb-4">Informasi lebih lanjut telah dikirim ke email Anda</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('ticket', ['transaction_id' => $transaction->id]) }}"
               class="inline-block px-8 py-4 bg-orange-50 text-orange-700 border border-orange-200 rounded-2xl font-bold hover:bg-orange-100 transition">
                Buka E-Tiket
            </a>

            <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition">
                Kembali ke Homepage
            </a>
        </div>
    </div>
</main>
@endsection
