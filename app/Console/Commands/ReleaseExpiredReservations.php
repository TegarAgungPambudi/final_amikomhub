<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseExpiredReservations extends Command
{
    protected $signature = 'reservations:release-expired';
    protected $description = 'Release reserved stock for expired pending transactions';

    public function handle(): int
    {
        $now = Carbon::now();

        // Find all pending transactions where reserved_until has passed
        $expiredReservations = Transaction::where('status', 'pending')
            ->where('reserved_until', '<=', $now)
            ->where('reserved_until', '!=', null)
            ->get();

        $releasedCount = 0;

        foreach ($expiredReservations as $transaction) {
            $event = $transaction->event;

            if ($event && $transaction->quantity > 0) {
                // Release the reserved stock back
                $event->increment('stock', $transaction->quantity);

                $this->info("Released {$transaction->quantity} ticket(s) for order {$transaction->order_id} on event {$event->title}");
            }

            // Mark transaction as expired/failed
            $transaction->update([
                'status' => 'expired',
                'reserved_until' => null,
            ]);

            $releasedCount++;
        }

        $this->info("Released {$releasedCount} expired reservation(s).");

        return Command::SUCCESS;
    }
}

