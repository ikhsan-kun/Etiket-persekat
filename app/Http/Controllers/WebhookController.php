<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans payment notification webhook.
     */
    public function midtrans(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        // Validate signature
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signature = $payload['signature_key'] ?? null;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature !== $expectedSignature) {
            Log::warning('Midtrans webhook: Invalid signature', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                if (!$order->isPaid()) {
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'payment_type' => $payload['payment_type'] ?? null,
                    ]);

                    // Generate e-tickets
                    $this->generateETickets($order);
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
            $this->handleFailedPayment($order);
        } elseif ($transactionStatus === 'expire') {
            $this->handleExpiredPayment($order);
        } elseif ($transactionStatus === 'pending') {
            // No action needed, order stays pending
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Handle failed payment - restore ticket quota.
     */
    private function handleFailedPayment(Order $order): void
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'failed']);
            $this->restoreQuota($order);
        }
    }

    /**
     * Handle expired payment - restore ticket quota.
     */
    private function handleExpiredPayment(Order $order): void
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'expired']);
            $this->restoreQuota($order);
        }
    }

    /**
     * Restore ticket quota when order is cancelled/expired.
     */
    private function restoreQuota(Order $order): void
    {
        $order->load('items.ticketCategory');

        foreach ($order->items as $item) {
            $item->ticketCategory->decrement('sold', $item->quantity);
        }
    }

    /**
     * Generate e-tickets for paid order.
     */
    private function generateETickets(Order $order): void
    {
        $order->load('items.ticketCategory');

        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                $ticketCode = ETicket::generateTicketCode();

                ETicket::create([
                    'order_id'      => $order->id,
                    'order_item_id' => $item->id,
                    'ticket_code'   => $ticketCode,
                    'qr_code_data'  => json_encode([
                        'code'     => $ticketCode,
                        'order'    => $order->order_number,
                        'match_id' => $item->ticketCategory->match_id ?? null,
                    ]),
                ]);
            }
        }
    }
}
