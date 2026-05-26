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
}
