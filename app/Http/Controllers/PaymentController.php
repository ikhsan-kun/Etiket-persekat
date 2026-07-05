<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(\App\Services\MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show payment page for an order.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Sync status with Midtrans in case webhook was missed/delayed
        if (!$order->isPaid() && $order->midtrans_snap_token) {
            $this->midtransService->checkAndSyncStatus($order);
        }

        if ($order->isPaid()) {
            return redirect()->route('my-tickets.show', $order)
                ->with('success', 'Pembayaran berhasil! E-Ticket Anda sudah tersedia.');
        }

        if ($order->isExpired()) {
            return redirect()->route('my-tickets.index')
                ->with('error', 'Pesanan ini sudah kadaluarsa.');
        }

        $order->load('items.ticketCategory.match');

        // Generate Midtrans Snap token if not exists
        if (!$order->midtrans_snap_token) {
            $snapToken = $this->midtransService->getSnapToken($order);
            if ($snapToken) {
                $order->update(['midtrans_snap_token' => $snapToken]);
            }
        }

        return view('payment.show', compact('order'));
    }

    /**
     * Handle dummy payment (development mode).
     */
    public function dummyPay(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->isPending()) {
            return redirect()->route('my-tickets.show', $order);
        }

        // Prevent payment on an expired order
        if ($order->isExpired()) {
            return redirect()->route('my-tickets.show', $order)
                ->with('error', 'Pesanan ini sudah kadaluarsa dan tidak dapat dibayar.');
        }

        // Simulate successful payment
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_type' => 'dummy_payment',
        ]);

        // Generate e-tickets
        $order->generateETickets();

        return redirect()->route('my-tickets.show', $order)
            ->with('success', 'Pembayaran berhasil! E-Ticket Anda sudah tersedia.');
    }
}

