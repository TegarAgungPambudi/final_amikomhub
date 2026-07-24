<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckinScannerController extends Controller
{
    /**
     * Show the scanner page
     */
    public function index()
    {
        $user = auth()->user();

        // Filter transactions based on user role (multi-tenant)
        $query = Transaction::whereIn('status', ['settlement', 'success']);

        if ($user->isOrganizer()) {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            });
        }

        $totalTickets = $query->count();
        $usedTickets = (clone $query)->where('is_used', true)->count();
        $validTickets = $totalTickets - $usedTickets;

        // Recent check-ins
        $recentCheckins = (clone $query)
            ->where('is_used', true)
            ->whereNotNull('used_at')
            ->with('event')
            ->latest('used_at')
            ->take(10)
            ->get();

        return view('admin.checkin-scanner', compact(
            'totalTickets', 'usedTickets', 'validTickets', 'recentCheckins'
        ));
    }

    /**
     * Verify a ticket by QR code (order_id)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $qrCode = $request->input('qr_code');

        // Find transaction by order_id or qr_code
        $transaction = Transaction::with('event')
            ->where(function ($q) use ($qrCode) {
                $q->where('order_id', $qrCode)
                  ->orWhere('qr_code', $qrCode);
            })
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan!',
            ], 404);
        }

        // Check if ticket is in success/settlement status
        if (!in_array($transaction->status, ['settlement', 'success'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket belum lunas! Status: ' . $transaction->status,
                'transaction' => $transaction,
            ], 400);
        }

        // Check if ticket already used
        if ($transaction->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah digunakan pada ' . ($transaction->used_at ? $transaction->used_at->format('d M Y H:i') : 'waktu sebelumnya') . '!',
                'transaction' => $transaction,
            ], 400);
        }

        // Multi-tenant: organizer only can check-in their own events
        if (auth()->user()->isOrganizer()) {
            $orgId = auth()->user()->organization_id;
            if ($transaction->event->organization_id !== $orgId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ini bukan untuk event organisasi Anda!',
                ], 403);
            }
        }

        // Mark as used
        $transaction->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        Log::info('Ticket checked in successfully', [
            'order_id' => $transaction->order_id,
            'event' => $transaction->event->title,
            'customer' => $transaction->customer_name,
            'checked_by' => auth()->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil! Selamat datang di acara.',
            'transaction' => [
                'customer_name' => $transaction->customer_name,
                'customer_email' => $transaction->customer_email,
                'event_title' => $transaction->event->title,
                'event_date' => $transaction->event->date ? $transaction->event->date->format('d M Y H:i') : '-',
                'quantity' => $transaction->quantity,
                'used_at' => now()->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Manually check-in a ticket by order ID (for panitia)
     */
    public function manualCheckin(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $transaction = Transaction::with('event')
            ->where('order_id', $request->input('order_id'))
            ->first();

        if (!$transaction) {
            return back()->with('error', 'Tiket dengan Order ID "' . $request->input('order_id') . '" tidak ditemukan!');
        }

        if (!in_array($transaction->status, ['settlement', 'success'])) {
            return back()->with('error', 'Tiket ini belum lunas (Status: ' . $transaction->status . ').');
        }

        if ($transaction->is_used) {
            return back()->with('error', 'Tiket ini sudah digunakan pada ' . ($transaction->used_at ? $transaction->used_at->format('d M Y H:i') : '-' ) . '.');
        }

        // Mark as used
        $transaction->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return back()->with('success', 'Check-in berhasil untuk ' . $transaction->customer_name . ' (Order: ' . $transaction->order_id . ')');
    }
}

