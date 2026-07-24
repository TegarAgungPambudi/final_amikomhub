<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-orange-600 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <!-- Success Banner -->
        <div class="text-center mb-8">
            <div
                class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
            <p class="text-orange-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        <!-- Ticket Card -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            <!-- Ticket Header -->
            <div class="p-8 bg-orange-50 border-b-4 border-dashed border-orange-100 text-center relative">
<p class="text-orange-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                <h2 class="text-2xl font-black leading-tight">{{ $transaction->event->title ?? 'Event' }}</h2>

                <!-- Ticket Side Cuts -->
                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-orange-500 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-orange-500 rounded-full"></div>
            </div>

            <!-- Ticket Body -->
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                        <p class="font-bold text-lg">{{ $transaction->customer_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                        <p class="font-bold text-lg">{{ $transaction->event->date->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                        <p class="font-bold">{{ $transaction->order_id ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                        <p class="font-bold">{{ $transaction->event->location ?? '-' }}</p>
                    </div>
                </div>

<div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Scan QR untuk Check-in</p>

                    @php
                        $qrPayload = isset($transaction) && $transaction ? (string) $transaction->order_id : 'TICKET-INVALID';
                        try {
                            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(200)
                                ->margin(2)
                                ->errorCorrection('M')
                                ->generate($qrPayload);
                        } catch (\Exception $e) {
                            $qrSvg = '<p style="text-align:center;color:#999;">QR Error</p>';
                        }
                    @endphp

                    <div class="w-48 h-48 bg-white p-2 rounded-xl shadow-inner flex items-center justify-center">
                        <div class="w-full h-full flex items-center justify-center">
                            {!! $qrSvg !!}
                        </div>
                    </div>

                    <p class="mt-4 font-mono font-bold text-slate-800">{{ $transaction->order_id ?? 'TKT-INVALID' }}</p>
                </div>
            </div>

            <div class="px-8 pb-8">
                <button onclick="window.print()"
                    class="w-full py-4 bg-orange-500 text-white rounded-2xl font-bold shadow-lg hover:bg-orange-600 transition">
                    Cetak / Simpan PDF
                </button>
                <a href="/"
                    class="block text-center mt-4 text-slate-500 font-bold hover:text-orange-600">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>

</html>

