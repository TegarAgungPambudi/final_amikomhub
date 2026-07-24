<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Transaction as TransactionModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    protected $isDemo;

    public function __construct()
    {
        $this->isDemo = config('midtrans.is_demo');
        
        // Set Midtrans Configuration (only if not in demo mode)
        if (!$this->isDemo) {
            Config::$serverKey = config('midtrans.server_key');
            Config::$clientKey = config('midtrans.client_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }
    }

    /**
     * Generate Snap Token untuk payment UI
     */
    public function createSnapToken(TransactionModel $transaction, string $paymentMethod = 'qris')
    {
        try {
            if ($this->isDemo) {
                // Demo mode: return mock token. In demo mode, the selected payment method is simulated by the front-end.
                $mockToken = 'DEMO-' . Str::random(20);
                $transaction->update(['snap_token' => $mockToken]);
                return $mockToken;
            }

            $quantity = (int) ($transaction->quantity ?? 1);
            $serviceFee = 5000;
            $originalTotal = (int) ($transaction->original_price ?? ($transaction->event->price * $quantity));
            $discountAmount = (int) ($transaction->discount_amount ?? 0);
            $ticketTotal = max(0, $originalTotal - $discountAmount);

            $enabledPayments = ['qris'];
            if ($paymentMethod === 'va') {
                $enabledPayments = ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->order_id,
                    'gross_amount' => $transaction->total_price,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name,
                    'email' => $transaction->customer_email,
                    'phone' => $transaction->customer_phone,
                ],
                'item_details' => [
                    [
                        'id' => $transaction->event->id,
                        'price' => $ticketTotal,
                        'quantity' => 1,
                        'name' => $quantity > 1
                            ? $transaction->event->title . ' x' . $quantity
                            : $transaction->event->title,
                    ],
                    [
                        'id' => 'SERVICE_FEE',
                        'price' => $serviceFee,
                        'quantity' => 1,
                        'name' => 'Biaya Admin',
                    ],
                ],
                'enabled_payments' => $enabledPayments,
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Snap token creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check transaction status dari Midtrans
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            if ($this->isDemo) {
                // Demo mode: return mock status
                return [
                    'order_id' => $orderId,
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept'
                ];
            }

            return Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error('Check transaction status failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Normalisasi status dari Midtrans ke status lokal yang dipakai aplikasi.
     */
    public function normalizeStatus($transactionStatus = null, $fraudStatus = null): string
    {
        $transactionStatusValue = $this->extractStatusValue($transactionStatus, 'transaction_status');
        $fraudStatusValue = $this->extractStatusValue($fraudStatus, 'fraud_status');

        $transactionStatusValue = strtolower((string) ($transactionStatusValue ?? ''));
        $fraudStatusValue = strtolower((string) ($fraudStatusValue ?? ''));

        if (in_array($transactionStatusValue, ['capture', 'settlement'], true)) {
            return $fraudStatusValue === 'challenge' ? 'pending' : 'settlement';
        }

        if ($transactionStatusValue === 'pending') {
            return 'pending';
        }

        if (in_array($transactionStatusValue, ['deny', 'cancel', 'expire', 'failure', 'failed'], true)) {
            return 'failed';
        }

        return 'pending';
    }

    protected function extractStatusValue($payload, string $key)
    {
        if (is_array($payload)) {
            return $payload[$key] ?? null;
        }

        if (is_object($payload)) {
            return $payload->{$key} ?? null;
        }

        return $payload;
    }

    /**
     * Handle webhook dari Midtrans
     */
    public function handleWebhook($notificationData)
    {
        try {
            $transaction = TransactionModel::where('order_id', $notificationData['order_id'])->first();
            
            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            $transactionStatus = $notificationData['transaction_status'];
            $fraudStatus = $notificationData['fraud_status'] ?? null;
            $normalizedStatus = $this->normalizeStatus($transactionStatus, $fraudStatus);

            if ($normalizedStatus === 'settlement') {
                $transaction->update(['status' => 'settlement']);
            } elseif ($normalizedStatus === 'pending') {
                $transaction->update(['status' => 'pending']);
            } else {
                $transaction->update(['status' => 'failed']);
            }

            return [
                'success' => true,
                'message' => 'Transaction status updated',
                'transaction' => $transaction
            ];
        } catch (\Exception $e) {
            Log::error('Webhook handling failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Demo mode: simulate payment
     */
    public function simulatePayment(TransactionModel $transaction, $status = 'settlement')
    {
        $validStatuses = ['settlement', 'pending', 'failed'];
        
        if (!in_array($status, $validStatuses)) {
            $status = 'settlement';
        }

        $transaction->update(['status' => $status]);

        return [
            'success' => true,
            'status' => $status,
            'message' => "Payment simulated as: " . ucfirst($status)
        ];
    }
}
