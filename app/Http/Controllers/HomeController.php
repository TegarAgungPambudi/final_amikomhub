<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Organization;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter
        $categories = Category::all();

        // 2. Query event: tampilkan semua (masa depan & masa lalu)
        $query = Event::with('category', 'organization')->orderBy('date', 'desc');

        // 3. Filter berdasarkan kategori (jika ada)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Ambil data event
        $events = $query->get();
        
        // 5. Pisahkan event yang akan datang dan yang sudah lewat
        $upcomingEvents = $events->filter(function ($event) {
            return $event->date >= now();
        })->sortBy('date');
        
        $pastEvents = $events->filter(function ($event) {
            return $event->date < now();
        })->sortByDesc('date');

        // 6. Ambil data partner
        $partners = Partner::all();

        // 7. Ambil semua organisasi dengan statistik rating
        $organizations = Organization::where('is_active', true)->get()->map(function ($org) {
            $eventIds = Event::where('organization_id', $org->id)->pluck('id');
            $reviews = Review::whereIn('event_id', $eventIds);
            $org->total_reviews = $reviews->count();
            $org->avg_rating = $org->total_reviews > 0 ? round($reviews->avg('rating'), 1) : 0;
            $org->total_events = Event::where('organization_id', $org->id)->count();
            return $org;
        })->sortByDesc('avg_rating');

        return view('welcome', compact('events', 'upcomingEvents', 'pastEvents', 'categories', 'partners', 'organizations'));
    }
}
