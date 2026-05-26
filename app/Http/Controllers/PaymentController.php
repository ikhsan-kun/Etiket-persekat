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
    /**
     * Show payment page for an order.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->isPaid()) {
            return redirect()->route('my-tickets.show', $order)
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        if ($order->isExpired()) {
            return redirect()->route('my-tickets.index')
                ->with('error', 'Pesanan ini sudah kadaluarsa.');
        }

        $order->load('items.ticketCategory.match');

        // Generate Midtrans Snap token if not exists
        if (!$order->midtrans_snap_token) {
            $snapToken = $this->getSnapToken($order);
            if ($snapToken) {
                $order->update(['midtrans_snap_token' => $snapToken]);
            }
        }

        return view('payment.show', compact('order'));
    }

    /**
     * Generate Midtrans Snap token.
     */
    private function getSnapToken(Order $order): ?string
    {
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            // Return null for dummy mode
            return null;
        }

        try {
            $order->load('user', 'items.ticketCategory');

            $items = $order->items->map(function ($item) {
                return [
                    'id' => 'TKT-' . $item->ticket_category_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->ticketCategory->name,
                ];
            })->toArray();

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone ?? '',
                ],
                'item_details' => $items,
                'expiry' => [
                    'start_time' => $order->created_at->format('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => (int) config('app.ticket_expiry_minutes', 30),
                ],
            ];

            $apiUrl = config('midtrans.is_production')
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $response = Http::withBasicAuth($serverKey, '')
                ->post($apiUrl, $params);

            if ($response->successful()) {
                return $response->json('token');
            }

            Log::error('Midtrans Snap error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap exception', ['error' => $e->getMessage()]);
            return null;
        }
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

        // Simulate successful payment
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_type' => 'dummy_payment',
        ]);

        // Generate e-tickets
        $this->generateETickets($order);

        return redirect()->route('my-tickets.show', $order)
            ->with('success', 'Pembayaran berhasil! E-Ticket Anda sudah tersedia.');
    }

    /**
     * Generate e-tickets for paid order.
     */
    private function generateETickets(Order $order): void
    {
        $order->load('items');

        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                $ticketCode = ETicket::generateTicketCode();

                ETicket::create([
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'ticket_code' => $ticketCode,
                    'qr_code_data' => json_encode([
                        'code' => $ticketCode,
                        'order' => $order->order_number,
                        'match_id' => $item->ticketCategory->match_id ?? null,
                    ]),
                ]);
            }
        }
    }
}
