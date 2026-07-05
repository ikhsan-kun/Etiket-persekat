<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    $order->generateETickets();
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
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
            if ($lockedOrder && $lockedOrder->status === 'pending') {
                $lockedOrder->update(['status' => 'failed']);
                $this->restoreQuota($lockedOrder);
            }
        });
    }

    /**
     * Handle expired payment - restore ticket quota.
     */
    private function handleExpiredPayment(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
            if ($lockedOrder && $lockedOrder->status === 'pending') {
                $lockedOrder->update(['status' => 'expired']);
                $this->restoreQuota($lockedOrder);
            }
        });
    }

    /**
     * Restore ticket quota when order is cancelled/expired.
     */
    private function restoreQuota(Order $order): void
    {
        $order->load('items.ticketCategory');

        foreach ($order->items as $item) {
            if ($item->ticketCategory) {
                // Pessimistic lock the ticket category to update the sold field safely
                $category = $item->ticketCategory()->lockForUpdate()->first();
                if ($category) {
                    $category->sold = max(0, $category->sold - $item->quantity);
                    $category->save();
                }
            }
        }
    }
}

