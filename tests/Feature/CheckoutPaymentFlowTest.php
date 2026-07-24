<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_view_exposes_payment_status_route_for_completion_flow(): void
    {
        $event = new Event([
            'id' => 1,
            'title' => 'Test Event',
            'price' => 150000,
            'stock' => 10,
            'location' => 'Jakarta',
            'date' => now(),
            'poster_path' => null,
        ]);

        $html = view('checkout', [
            'event' => $event,
            'categories' => collect(),
            'currentPrice' => 150000,
            'availableTiers' => collect(),
        ])->render();

        $this->assertStringContainsString('data-payment-status-route', $html);
        $this->assertStringContainsString('/payment-status', $html);
    }

    public function test_payment_status_accepts_transaction_order_id(): void
    {
        $event = Event::create([
            'title' => 'Test Event',
            'description' => 'Desc',
            'price' => 150000,
            'stock' => 10,
            'location' => 'Jakarta',
            'date' => now(),
            'poster_path' => null,
        ]);

        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'ORDER-12345',
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '08123456789',
            'total_price' => 150000,
            'quantity' => 1,
            'status' => 'pending',
            'qr_code' => 'ORDER-12345',
        ]);

        $response = $this->get(route('payment.status', ['transaction' => $transaction->order_id]));

        $response->assertStatus(200);
        $response->assertViewHas('transaction', function ($viewTransaction) use ($transaction) {
            return $viewTransaction->id === $transaction->id;
        });
    }
}
