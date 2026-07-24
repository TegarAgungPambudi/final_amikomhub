<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOrganizer()) {
            return redirect()->route('admin.organizer.dashboard');
        }

        // Multi-tenant: filter berdasarkan organisasi jika organizer
        $transactionQuery = Transaction::whereIn('status', ['settlement', 'success']);
        $eventQuery = Event::where('date', '>=', now());
        $pendingQuery = Transaction::where('status', 'pending');

        if ($user->isOrganizer()) {
            $orgId = $user->organization_id;
            $transactionQuery->whereHas('event', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId);
            });
            $eventQuery->where('organization_id', $orgId);
            $pendingQuery->whereHas('event', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId);
            });
        }

        $totalRevenue = (clone $transactionQuery)->sum('total_price');
        $ticketsSold = (clone $transactionQuery)->count();
        $activeEvents = (clone $eventQuery)->count();
        $pendingOrders = (clone $pendingQuery)->count();

        // Recent transactions
        $recentQuery = Transaction::with('event');
        if ($user->isOrganizer()) {
            $recentQuery->whereHas('event', function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            });
        }
        $recentTransactions = $recentQuery->latest()->take(5)->get();

        // ===== CHART DATA =====
        // Monthly revenue for last 6 months
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $revenueQuery = clone $transactionQuery;
            $revenue = (clone $revenueQuery)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');

            $monthlyLabels[] = $month->format('M Y');
            $monthlyRevenue[] = (int) $revenue;
        }

        // Ticket sales per event (top 5)
        $popularEventsQuery = Event::withCount(['transactions' => function ($q) {
            $q->whereIn('status', ['settlement', 'success']);
        }]);

        if ($user->isOrganizer()) {
            $popularEventsQuery->where('organization_id', $user->organization_id);
        }

        $popularEvents = $popularEventsQuery->orderBy('transactions_count', 'desc')->take(5)->get();
        $eventLabels = $popularEvents->pluck('title')->map(fn($t) => strlen($t) > 20 ? substr($t, 0, 20) . '...' : $t);
        $eventData = $popularEvents->pluck('transactions_count');

        // User growth (for superadmin only)
        $userGrowthLabels = [];
        $userGrowthData = [];
        if ($user->isSuperAdmin()) {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthStart = $month->copy()->startOfMonth();
                $monthEnd = $month->copy()->endOfMonth();

                $count = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
                $userGrowthLabels[] = $month->format('M Y');
                $userGrowthData[] = $count;
            }
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 'ticketsSold', 'activeEvents', 'pendingOrders',
            'recentTransactions', 'monthlyRevenue', 'monthlyLabels',
            'eventLabels', 'eventData', 'userGrowthLabels', 'userGrowthData',
            'popularEvents'
        ));
    }

    /**
     * Organizer-specific dashboard
     */
    public function organizerDashboard()
    {
        $user = auth()->user();
        $orgId = $user->organization_id;
        $organization = \App\Models\Organization::find($orgId);

        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])
            ->whereHas('event', fn($q) => $q->where('organization_id', $orgId))
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])
            ->whereHas('event', fn($q) => $q->where('organization_id', $orgId))
            ->count();

        $activeEvents = Event::where('date', '>=', now())
            ->where('organization_id', $orgId)
            ->count();

        $pendingOrders = Transaction::where('status', 'pending')
            ->whereHas('event', fn($q) => $q->where('organization_id', $orgId))
            ->count();

        $recentTransactions = Transaction::with('event')
            ->whereHas('event', fn($q) => $q->where('organization_id', $orgId))
            ->latest()->take(5)->get();

        // Chart data for organizer - monthly revenue
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $revenue = Transaction::whereIn('status', ['settlement', 'success'])
                ->whereHas('event', fn($q) => $q->where('organization_id', $orgId))
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');

            $monthlyLabels[] = $month->format('M Y');
            $monthlyRevenue[] = (int) $revenue;
        }

        // Popular events for organizer
        $popularEvents = Event::withCount(['transactions' => function ($q) {
                $q->whereIn('status', ['settlement', 'success']);
            }])
            ->where('organization_id', $orgId)
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();
        $eventLabels = $popularEvents->pluck('title')->map(fn($t) => strlen($t) > 20 ? substr($t, 0, 20) . '...' : $t);
        $eventData = $popularEvents->pluck('transactions_count');

        return view('admin.organizer-dashboard', compact(
            'totalRevenue', 'ticketsSold', 'activeEvents', 'pendingOrders',
            'recentTransactions', 'monthlyRevenue', 'monthlyLabels',
            'eventLabels', 'eventData', 'popularEvents', 'organization'
        ));
    }

    /**
     * Organizer-specific events list
     */
    public function organizerEvents()
    {
        $events = Event::with('category')
            ->where('organization_id', auth()->user()->organization_id)
            ->latest()->paginate(10);

        return view('admin.organizer-events', compact('events'));
    }

    /**
     * Organizer-specific transactions list
     */
    public function organizerTransactions()
    {
        $transactions = Transaction::with('event')
            ->whereHas('event', fn($q) => $q->where('organization_id', auth()->user()->organization_id))
            ->latest()->paginate(20);

        return view('admin.organizer-transactions', compact('transactions'));
    }

    public function indexEvent()
    {
        return view('admin.events');
    }

    public function indexTransaction()
    {
        $query = Transaction::with('event');

        // Multi-tenant: organizer hanya lihat transaksi untuk event organisasinya
        if (auth()->user()->isOrganizer()) {
            $query->whereHas('event', function ($q) {
                $q->where('organization_id', auth()->user()->organization_id);
            });
        }

        $transactions = $query->latest()->paginate(20);
        return view('admin.transactions', compact('transactions'));
    }
}
