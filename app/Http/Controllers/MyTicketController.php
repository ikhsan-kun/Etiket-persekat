<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTicketController extends Controller
{
    /**
     * Show user's ticket history.
     */
    public function index()
    {
        $orders = Order::with(['items.ticketCategory.match'])
            ->where('user_id', Auth::id())
            ->recent()
            ->paginate(10);

        return view('my-tickets.index', compact('orders'));
    }

    /**
     * Show order detail with e-tickets.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.ticketCategory.match', 'eTickets.orderItem.ticketCategory']);

        return view('my-tickets.show', compact('order'));
    }
}
