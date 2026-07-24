@extends('layouts.app')

@section('content')
<main id="checkoutPage" class="max-w-6xl mx-auto px-6 py-12 lg:py-20"
      data-event-price="{{ (int) $currentPrice }}"
      data-checkout-route="{{ route('checkout.process', $event) }}"
      data-payment-status-route="{{ route('payment.status', ['transaction' => ':id']) }}"
      data-demo="{{ config('midtrans.is_demo') ? '1' : '0' }}"
      data-is-past="{{ $event->date && $event->date->isPast() ? '1' : '0' }}">
    <div class="mb-10">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-orange-600 font-bold mb-6 hover:text-orange-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-black text-slate-900">Checkout</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-8">
        <div class="bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <h3 class="text-xl font-bold text-slate-900">Pesanan Anda</h3>
                <span class="text-sm font-semibold text-orange-600">Reservasi Instan</span>
            </div>
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full md:w-32 h-32 rounded-2xl object-cover shadow-sm">
                <div class="flex-1">
                    <h4 class="font-extrabold text-lg text-slate-900">{{ $event->title }}</h4>
                    <p class="text-slate-500 mt-1">
                        {{ $event->date ? $event->date->format('d M Y H:i') : 'Tanggal belum diatur' }} • {{ $event->location }}
                    </p>
                    <p class="text-orange-600 font-bold mt-3" id="ticketSummaryLine">1 x Rp {{ number_format($currentPrice, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 space-y-3">
                <div class="flex justify-between text-slate-600">
                    <span>Harga Tiket</span>
                    <span id="ticketPriceText">Rp {{ number_format($currentPrice, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Layanan</span>
                    <span id="serviceFeeText">Rp 5.000</span>
                </div>
                <div class="flex justify-between text-slate-600 hidden" id="discountRow">
                    <span>Diskon</span>
                    <span class="text-emerald-600 font-bold" id="discountValueText">- Rp 0</span>
                </div>
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t border-slate-100">
                    <span>Total Bayar</span>
                    <span class="text-orange-600" id="totalPriceText">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-orange-100 p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Data Pemesan</h3>
                @auth
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-100 px-3 py-1 text-sm font-semibold text-orange-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Login aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"></path></svg>
                        Belum login
                    </span>
                @endauth
            </div>
            <form id="checkoutForm" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas"
                        class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium"
                        required>
                    <span class="text-red-500 text-sm error-message" id="error-customer_name"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="contoh@gmail.com"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium"
                            required>
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                        <span class="text-red-500 text-sm error-message" id="error-customer_email"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxx"
                        class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium"
                        required>
                    <span class="text-red-500 text-sm error-message" id="error-customer_phone"></span>
                </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Jumlah Tiket</label>
                    <input type="number" name="quantity" value="1" min="1"
                        class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium"
                        required>
                    <span class="text-red-500 text-sm error-message" id="error-quantity"></span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Diskon (Opsional)</label>
                    <div class="flex gap-3">
                        <input type="hidden" id="eventIdInput" value="{{ $event->id }}">
                        <input type="text" name="coupon_code" id="couponCodeInput" placeholder="Masukkan kode kupon"
                            class="flex-1 px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-medium">
                        <button type="button" id="applyCouponButton"
                            class="px-5 py-4 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition">Gunakan</button>
                    </div>
                    <p class="text-sm text-slate-400 mt-2" id="couponMessage">Masukkan kode promo untuk mendapatkan diskon.</p>
                    <span class="text-red-500 text-sm error-message" id="error-coupon_code"></span>
                </div>

                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-2xl">
                    <p class="text-sm font-bold text-orange-900 mb-2">Metode pembayaran</p>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="qris" checked class="w-4 h-4 text-orange-600">
                            <span class="text-sm font-medium text-orange-800">QRIS</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="va" class="w-4 h-4 text-orange-600">
                            <span class="text-sm font-medium text-orange-800">Virtual Account</span>
                        </label>
                    </div>
                </div>

                @if(config('midtrans.is_demo'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-2xl">
                    <p class="text-sm font-bold text-blue-900 mb-3">Pilih status pembayaran (demo):</p>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_status" value="success" checked class="w-4 h-4 text-green-600">
                            <span class="text-sm font-medium text-green-700">Pembayaran Berhasil (Settlement)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_status" value="pending" class="w-4 h-4 text-yellow-600">
                            <span class="text-sm font-medium text-yellow-700">Pembayaran Tertunda (Pending)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_status" value="failed" class="w-4 h-4 text-red-600">
                            <span class="text-sm font-medium text-red-700">Pembayaran Gagal (Failed)</span>
                        </label>
                    </div>
                </div>
                @endif

                @if($event->date && $event->date->isPast())
                    <div class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-6 py-5 text-center font-bold">
                        Event ini telah selesai. Pembelian tiket sudah ditutup, namun Anda tetap bisa memberikan ulasan di halaman event.
                    </div>
                @else
                    <button type="button" id="payButton"
                        class="block text-center w-full py-5 bg-orange-500 text-white rounded-2xl font-black text-xl shadow-xl shadow-orange-200 hover:bg-orange-600 active:scale-95 transition-all">
                        Bayar Sekarang
                    </button>
                @endif
                <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>
    </div>
</main>

@if(!config('midtrans.is_demo'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script src="{{ asset('assets/checkout.js') }}"></script>
@endsection
