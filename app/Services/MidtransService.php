<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ETicket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransService
{
    /**
     * Get Midtrans Server Key from config.
     */
    protected function getServerKey(): string
    {
        return config('midtrans.server_key', '');
    }

    /**
     * Check if Midtrans is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->getServerKey());
    }

    /**
     * Get snap API endpoint url.
     */
    protected function getSnapUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Get status API endpoint url.
     */
    protected function getStatusUrl(string $orderNumber): string
    {
        return config('midtrans.is_production')
            ? "https://api.midtrans.com/v2/{$orderNumber}/status"
            : "https://api.sandbox.midtrans.com/v2/{$orderNumber}/status";
    }

    /**
     * Get snap token for an order.
     */
    public function getSnapToken(Order $order): ?string
    {
        if (!$this->isConfigured()) {
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

            $response = Http::withBasicAuth($this->getServerKey(), '')
                ->post($this->getSnapUrl(), $params);

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
     * Query Midtrans API to check transaction status and sync with local order.
     */
    public function checkAndSyncStatus(Order $order): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        // If already paid, no need to check
        if ($order->isPaid()) {
            return true;
        }

        try {
            $response = Http::withBasicAuth($this->getServerKey(), '')
                ->get($this->getStatusUrl($order->order_number));

            if (!$response->successful()) {
                return false;
            }

            $statusData = $response->json();
            $transactionStatus = $statusData['transaction_status'] ?? null;
            $fraudStatus = $statusData['fraud_status'] ?? null;

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept' || $fraudStatus === null) {
                    DB::transaction(function () use ($order, $statusData) {
                        // Lock order for update
                        $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
                        
                        if ($lockedOrder && !$lockedOrder->isPaid()) {
                            // If it was expired/failed previously, we should make sure the quota is updated or at least marked paid
                            $wasExpiredOrFailed = in_array($lockedOrder->status, ['expired', 'failed']);
                            
                            $lockedOrder->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'midtrans_transaction_id' => $statusData['transaction_id'] ?? null,
                                'payment_type' => $statusData['payment_type'] ?? null,
                            ]);

                            // If it was previously expired/failed, we adjust the category sold count back
                            if ($wasExpiredOrFailed) {
                                $lockedOrder->load('items.ticketCategory');
                                foreach ($lockedOrder->items as $item) {
                                    if ($item->ticketCategory) {
                                        $category = $item->ticketCategory()->lockForUpdate()->first();
                                        if ($category) {
                                            $category->sold = $category->sold + $item->quantity;
                                            $category->save();
                                        }
                                    }
                                }
                            }

                            // Generate e-tickets
                            $lockedOrder->generateETickets();
                        }
                    });

                    // Refresh order object state
                    $order->refresh();
                    return true;
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
                $this->handleFailedPayment($order);
            } elseif ($transactionStatus === 'expire') {
                $this->handleExpiredPayment($order);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Midtrans status check exception', ['error' => $e->getMessage(), 'order' => $order->order_number]);
            return false;
        }
    }

    /**
     * Handle failed payment.
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
        $order->refresh();
    }

    /**
     * Handle expired payment.
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
        $order->refresh();
    }

    /**
     * Restore ticket quota.
     */
    private function restoreQuota(Order $order): void
    {
        $order->load('items.ticketCategory');
        foreach ($order->items as $item) {
            if ($item->ticketCategory) {
                $category = $item->ticketCategory()->lockForUpdate()->first();
                if ($category) {
                    $category->sold = max(0, $category->sold - $item->quantity);
                    $category->save();
                }
            }
        }
    }
}
