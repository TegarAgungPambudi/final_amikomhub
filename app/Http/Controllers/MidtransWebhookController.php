<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Webhooks Midtrans Masuk!', $request->all());
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang
        if (in_array($transaction->status, ['settlement', 'success'], true)) {
            return response()->json(['message' => 'Already processed']);
        }

        $paymentService = app(\App\Services\PaymentService::class);
        $normalizedStatus = $paymentService->normalizeStatus($transactionStatus, $fraudStatus);

        // Mapping status Midtrans -> status lokal
        if ($normalizedStatus === 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } elseif ($normalizedStatus === 'failed') {
            $transaction->status = 'failed';
            // RELEASE RESERVED STOCK when payment fails/expires
            $this->releaseReservedStock($transaction);
        } else {
            $transaction->status = 'pending';
        }

        $transaction->save();

        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction): void
    {
        $event = $transaction->event;

        if ($event) {
            // Clear reserved_until since payment is successful
            $transaction->reserved_until = null;
            $transaction->save();

            // Mengirimkan email E-Ticket ke pelanggan
            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                    ->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
            }
        }
    }

    /**
     * Release reserved stock back when transaction fails/expires
     */
    private function releaseReservedStock(Transaction $transaction): void
    {
        $event = $transaction->event;
        if ($event && $transaction->quantity > 0) {
            $event->increment('stock', $transaction->quantity);
            \Illuminate\Support\Facades\Log::info("Released {$transaction->quantity} reserved ticket(s) for failed order {$transaction->order_id}");
        }
    }
}

