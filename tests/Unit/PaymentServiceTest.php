<?php

namespace Tests\Unit;

use App\Services\PaymentService;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    public function test_it_maps_midtrans_settlement_to_successful_local_status(): void
    {
        $service = app(PaymentService::class);

        $this->assertSame('settlement', $service->normalizeStatus('settlement'));
    }

    public function test_it_maps_capture_accept_to_successful_local_status(): void
    {
        $service = app(PaymentService::class);

        $this->assertSame('settlement', $service->normalizeStatus('capture', 'accept'));
    }

    public function test_it_handles_stdclass_midtrans_status_payloads(): void
    {
        $service = app(PaymentService::class);
        $status = (object) [
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ];

        $this->assertSame('settlement', $service->normalizeStatus($status));
    }
}
