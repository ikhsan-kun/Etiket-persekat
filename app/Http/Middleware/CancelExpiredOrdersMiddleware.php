<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrdersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cancel expired orders dynamically before proceeding with the request
        $this->cancelExpiredOrders();

        return $next($request);
    }

    /**
     * Cancel expired orders and restore ticket quotas.
     */
    private function cancelExpiredOrders(): void
    {
        try {
            // Find if there are any pending orders that have expired
            $hasExpired = Order::where('status', 'pending')
                ->where('expired_at', '<', now())
                ->exists();

            if (!$hasExpired) {
                return;
            }

            // Fetch and lock expired orders one by one to prevent race conditions
            $expiredOrderIds = Order::where('status', 'pending')
                ->where('expired_at', '<', now())
                ->pluck('id');

            foreach ($expiredOrderIds as $orderId) {
                DB::transaction(function () use ($orderId) {
                    $order = Order::where('id', $orderId)
                        ->where('status', 'pending')
                        ->lockForUpdate()
                        ->first();

                    if ($order) {
                        $order->update(['status' => 'expired']);
                        
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
                });
            }
        } catch (\Exception $e) {
            Log::error("Failed to dynamically cancel expired orders: " . $e->getMessage());
        }
    }
}
