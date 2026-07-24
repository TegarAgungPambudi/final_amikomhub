<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tampilkan halaman review untuk event tertentu
     */
    public function index(Event $event)
    {
        $reviews = $event->reviews()->with('user')->latest()->get();
        $categories = \App\Models\Category::all();

        // Hitung statistik rating
        $averageRating = $event->averageRating();
        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $reviews->where('rating', $i)->count();
        }

        // Cek apakah user sudah pernah review
        $userReview = null;
        if (Auth::check()) {
            $userReview = $reviews->where('user_id', Auth::id())->first();
        }

        return view('event-detail', compact(
            'event', 'categories', 'reviews',
            'averageRating', 'ratingCounts', 'userReview'
        ));
    }

    /**
     * Simpan review baru
     */
    public function store(Request $request, Event $event)
    {
        // Cek apakah event sudah lewat (post-event)
        if ($event->date > now()) {
            return back()->with('error', 'Review hanya bisa diberikan setelah event selesai.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user sudah pernah review event ini
        $existingReview = Review::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            // Update review yang sudah ada
            $existingReview->update($validated);
            return back()->with('success', 'Review berhasil diperbarui!');
        }

        // Buat review baru
        Review::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review berhasil dikirim! Terima kasih atas partisipasi Anda.');
    }
}

