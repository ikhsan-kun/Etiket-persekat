<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with analytics.
     */
    public function index()
    {
        $totalRevenue     = Order::paid()->sum('total_amount');
        $revenueThisMonth = Order::paid()
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total_amount');

        $totalTicketsSold = DB::table('ticket_categories')->sum('sold');
        $totalQuota       = DB::table('ticket_categories')->sum('quota');

        $totalOrders = Order::count();
        $paidOrders  = Order::where('status', 'paid')->count();

        $pendingOrders  = Order::where('status', 'pending')->count();
        $ticketsScanned = DB::table('e_tickets')->where('is_used', true)->count();

        // Upcoming match with tickets info
        $upcomingMatches = FootballMatch::with('ticketCategories')
            ->where('status', 'published')
            ->upcoming()
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user', 'items.ticketCategory.match'])
            ->recent()
            ->take(10)
            ->get();

        // Revenue by match
        $revenueByMatch = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('ticket_categories', 'order_items.ticket_category_id', '=', 'ticket_categories.id')
            ->join('matches', 'ticket_categories.match_id', '=', 'matches.id')
            ->where('orders.status', 'paid')
            ->select('matches.opponent', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('matches.opponent')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'revenueThisMonth',
            'totalTicketsSold',
            'totalQuota',
            'totalOrders',
            'paidOrders',
            'pendingOrders',
            'ticketsScanned',
            'upcomingMatches',
            'recentOrders',
            'revenueByMatch'
        ));
    }
}
