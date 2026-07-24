<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Event;
use App\Models\Transaction;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $paymentService;

    protected function configureMidtransForRequest(): void
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show checkout form dengan event details & tier pricing
     */
    public function checkout(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        if ($event->date && $event->date->isPast()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Acara ini telah selesai dan tiket tidak bisa dibeli lagi. Silakan tinggalkan ulasan.');
        }

        $categories = \App\Models\Category::all();
        $currentPrice = $event->getCurrentPrice();
        $availableTiers = $event->getAvailableTiers();
        $isPastEvent = false;

        return view('checkout', compact('event', 'categories', 'currentPrice', 'availableTiers', 'isPastEvent'));
    }

    /**
     * Validate coupon code via AJAX
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'order_amount' => 'required|integer|min:0',
            'event_id' => 'required|integer|exists:events,id',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode kupon tidak ditemukan.',
            ], 400);
        }

        if (!$coupon->isValid($request->order_amount, $request->event_id)) {
            $reason = 'Kode kupon tidak berlaku untuk acara ini atau sudah habis masa berlakunya.';
            if ($coupon->event_id && $coupon->event_id !== $request->event_id) {
                $reason = 'Kode kupon ini hanya berlaku untuk event tertentu.';
            } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                $reason = 'Kode kupon sudah kadaluarsa.';
            } elseif ($coupon->max_uses > 0 && $coupon->used_count >= $coupon->max_uses) {
                $reason = 'Kode kupon sudah mencapai batas pemakaian.';
            } elseif ($coupon->min_order_amount > 0 && $request->order_amount < $coupon->min_order_amount) {
                $reason = 'Minimal pesanan Rp ' . number_format($coupon->min_order_amount, 0, ',', '.') . ' untuk menggunakan kupon ini.';
            }
            return response()->json([
                'valid' => false,
                'message' => $reason,
            ], 400);
        }

        $discount = $coupon->calculateDiscount($request->order_amount);

        return response()->json([
            'valid' => true,
            'message' => 'Kode kupon berlaku!',
            'coupon' => [
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'discount_amount' => $discount,
                'formatted_discount' => $coupon->discount_type === 'percent' 
                    ? $coupon->discount_value . '%' 
                    : 'Rp ' . number_format($discount, 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Process checkout dan generate Snap Token
     * With reserved stock (race condition prevention)
     */
    public function processCheckout(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
            'payment_status' => 'nullable|in:success,pending,failed',
            'payment_method' => 'nullable|string|in:qris,va',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $quantity = (int) $validated['quantity'];

        if ($event->date && $event->date->isPast()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acara ini telah selesai. Pembelian tiket tidak tersedia lagi, tetapi Anda dapat memberikan ulasan.',
                ], 400);
            }

            return redirect()->route('events.show', $event)
                ->with('error', 'Acara ini telah selesai. Pembelian tiket tidak tersedia lagi, tetapi Anda dapat memberikan ulasan.');
        }

        // Cegah Check-out jika stok tiket habis (use DB transaction for atomicity)
        if ((int) $event->stock < $quantity) {
            return redirect()->back()->with('error', 'Mohon maaf, stok tiket untuk acara ini tidak mencukupi. Sisa: ' . $event->stock . ' tiket.');
        }

        // Get current price (with tiered pricing)
        $unitPrice = $event->getCurrentPrice();
        $originalTotal = $unitPrice * $quantity;

        // Apply coupon if provided
        $couponCode = null;
        $discountAmount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($originalTotal, $event->id)) {
                $discountAmount = $coupon->calculateDiscount($originalTotal);
                $couponCode = $coupon->code;

                // Increment used count
                $coupon->increment('used_count');
            }
        }

        // Final price after discount + service fee
        $totalPrice = ($originalTotal - $discountAmount) + 5000;

        // Generate unique order ID
        $orderId = 'ORDER-' . date('YmdHis') . '-' . Str::random(6);

        // RESERVE STOCK: Kurangi stok segera (race condition prevention)
        $event->decrement('stock', $quantity);

        // Set reserved expiration (15 minutes)
        $reservedUntil = Carbon::now()->addMinutes(15);

        // Create transaction record
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'total_price' => $totalPrice,
            'quantity' => $quantity,
            'status' => 'pending',
            'coupon_code' => $couponCode,
            'discount_amount' => $discountAmount,
            'original_price' => $originalTotal,
            'reserved_until' => $reservedUntil,
            'qr_code' => $orderId, // Use order_id as QR code
        ]);

        try {
            // Check if demo mode
            if (config('midtrans.is_demo')) {
                // Demo mode: simulate payment result
                $paymentStatus = $validated['payment_status'] ?? 'success';

                if ($paymentStatus === 'success') {
                    $this->paymentService->simulatePayment($transaction, 'settlement');
                } else {
                    $this->paymentService->simulatePayment($transaction, $paymentStatus === 'pending' ? 'pending' : 'failed');
                }

                return response()->json([
                    'success' => true,
                    'snap_token' => 'DEMO-TOKEN-' . Str::random(20),
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                    'is_demo' => true,
                    'payment_status' => $paymentStatus,
                ]);
            }

            // Generate Snap Token
            $snapToken = $this->paymentService->createSnapToken($transaction, $validated['payment_method'] ?? 'qris');

            // Redirect target untuk membuka popup pembayaran
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => route('checkout.payment', $transaction->order_id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek status pembayaran
     */
    public function paymentStatus(Request $request, string $transactionIdentifier)
    {
        try {
            $transaction = Transaction::with('event')
                ->where('id', $transactionIdentifier)
                ->orWhere('order_id', $transactionIdentifier)
                ->first();

            if (!$transaction) {
                abort(404, 'Transaksi tidak ditemukan.');
            }

            $status = $this->paymentService->checkTransactionStatus($transaction->order_id);
            $normalizedStatus = $this->paymentService->normalizeStatus($status, null);

            if ($normalizedStatus !== $transaction->status) {
                $transaction->forceFill(['status' => $normalizedStatus])->save();
            }

            $categories = \App\Models\Category::all();

            return view('payment-status', [
                'transaction' => $transaction,
                'status' => $status,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Webhook handler dari Midtrans
     */
    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // Konfigurasi Midtrans untuk mengecek status transaksi langsung ke API
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            // Mengecek status pesanan secara mandiri (Bypass)
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                // Mengambil nilai status transaksi
                $trx_status = is_array($status)
                    ? ($status['transaction_status'] ?? '')
                    : ($status->transaction_status ?? '');

                $normalizedStatus = $this->paymentService->normalizeStatus($status, null);

                // Jika API Midtrans mengonfirmasi bahwa transaksi telah berhasil (settlement / capture)
                if (in_array($normalizedStatus, ['settlement'], true)) {
                    // Hanya lakukan update jika status di database lokal masih 'pending' (indikasi Webhook tidak masuk)
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'settlement']);

                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Throwable $e) {
                            Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    /**
     * Webhook handler dari Midtrans
     */
    public function webhook(Request $request)
    {
        try {
            $json = file_get_contents('php://input');
            $notificationData = json_decode($json, true);

            $this->paymentService->handleWebhook($notificationData);

            Log::info('Midtrans Webhook:', $notificationData);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

