<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class OrganizerProfileController extends Controller
{
    public function show($slug)
    {
        $organization = Organization::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil semua event milik organisasi ini
        $events = Event::with('category', 'reviews')
            ->where('organization_id', $organization->id)
            ->orderBy('date', 'desc')
            ->get();

        // Ambil semua review dari semua event organisasi ini
        $eventIds = $events->pluck('id');
        $reviews = Review::with('user', 'event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->get();

        // Hitung statistik rating
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        
        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $reviews->where('rating', $i)->count();
        }

        $totalEvents = $events->count();

        return view('organizer-profile', compact(
            'organization', 'events', 'reviews',
            'totalReviews', 'avgRating', 'ratingCounts', 'totalEvents'
        ));
    }
}

