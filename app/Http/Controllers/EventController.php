<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        $event->loadMissing('category');
        $categories = \App\Models\Category::all();

        // Load reviews for the view
        $event->load('reviews.user');
        $reviews = $event->reviews()->with('user')->latest()->get();
        $totalReviews = $reviews->count();
        $avgRating = $event->averageRating();
        $ratingCounts = $event->ratingCounts();
        $userReview = auth()->check() ? $reviews->where('user_id', auth()->id())->first() : null;

        return view('event-detail', compact(
            'event', 'categories', 'reviews', 'totalReviews',
            'avgRating', 'ratingCounts', 'userReview'
        ));
    }


    public function ticket(Request $request)
    {
        // Update: tampilkan transaksi terbaru yang baru-baru ini dibuat user melalui query string
        // Jika ada ?transaction_id=... gunakan itu, kalau tidak fallback ke transaction terakhir.
        $transactionId = $request->query('transaction_id');

        $transactionQuery = \App\Models\Transaction::with('event');

        if ($transactionId) {
            $transaction = $transactionQuery->where('id', $transactionId)->latest()->first();
        } else {
            $transaction = $transactionQuery->latest()->first();
        }

        // Jika tidak ada transaksi, redirect ke home dengan pesan error
        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Tiket tidak ditemukan. Silakan lakukan pemesanan terlebih dahulu.');
        }

        return view('ticket', compact('transaction'));
    }
}

