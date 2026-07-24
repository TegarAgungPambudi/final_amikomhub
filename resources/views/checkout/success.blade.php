@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md">
        <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black mb-4">Pembayaran Berhasil!</h2>
        <p class="text-slate-500 mb-4 leading-relaxed">
            Terima kasih! Pesanan <strong>{{ $transaction->order_id }}</strong> telah berhasil diproses.
        </p>
        <p class="text-slate-500 mb-8 leading-relaxed">
            E-Ticket juga telah dikirim ke email <strong>{{ $transaction->customer_email }}</strong>.
        </p>

        <!-- Tombol Lihat E-Ticket -->
        <a href="{{ route('ticket', ['transaction_id' => $transaction->id]) }}" 
           class="block w-full mb-4 px-8 py-5 bg-orange-500 text-white rounded-2xl font-black text-xl shadow-xl shadow-orange-200 hover:bg-orange-600 hover:scale-[1.02] transition-all">
            Lihat E-Ticket Saya
        </a>

        <a href="{{ route('home') }}" class="inline-block px-6 py-3 text-slate-500 font-bold hover:text-orange-600 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection

