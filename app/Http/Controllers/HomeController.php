<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;

class HomeController extends Controller
{
    /**
     * Show landing page.
     */
    public function index()
    {
        $upcomingMatches = FootballMatch::with('ticketCategories')
            ->published()
            ->upcoming()
            ->take(3)
            ->get();

        return view('landing', compact('upcomingMatches'));
    }

    /**
     * Show matches catalog (authenticated users).
     */
    public function matches()
    {
        $matches = FootballMatch::with('ticketCategories')
            ->published()
            ->upcoming()
            ->paginate(9);

        return view('matches.index', compact('matches'));
    }

    /**
     * Show match detail page.
     */
    public function matchDetail(FootballMatch $match)
    {
        if ($match->status !== 'published' && $match->status !== 'live') {
            abort(404);
        }

        $match->load('ticketCategories');

        return view('matches.show', compact('match'));
    }

    /**
     * Verify a digitally signed report document.
     */
    public function verifyReport(\Illuminate\Http\Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $totalRevenue = $request->get('total_revenue');
        $totalTickets = $request->get('total_tickets');
        $totalOrders = $request->get('total_orders');
        $signee = $request->get('signee');
        $printedBy = $request->get('printed_by');
        $hash = $request->get('hash');

        // Re-generate hash to verify authenticity
        $calculatedHash = hash_hmac(
            'sha256',
            $dateFrom . '|' . $dateTo . '|' . $totalRevenue . '|' . $totalTickets . '|' . $totalOrders . '|' . $signee . '|' . $printedBy,
            config('app.key')
        );

        $isValid = (!empty($hash) && $hash === $calculatedHash);

        return view('reports.verify', compact(
            'isValid',
            'dateFrom',
            'dateTo',
            'totalRevenue',
            'totalTickets',
            'totalOrders',
            'signee',
            'printedBy'
        ));
    }
}
