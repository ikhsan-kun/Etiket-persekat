<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Batalkan pesanan pending yang melewati waktu kedaluwarsa dan kembalikan kuota tiket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada pesanan kedaluwarsa yang ditemukan.');
            return 0;
        }

        $count = 0;

        foreach ($expiredOrders as $order) {
            try {
                DB::transaction(function () use ($order, &$count) {
                    // Lock order to prevent race conditions
                    $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
                    
                    if ($lockedOrder && $lockedOrder->status === 'pending') {
                        $lockedOrder->update(['status' => 'expired']);

                        // Restore ticket quota
                        $lockedOrder->load('items.ticketCategory');
                        foreach ($lockedOrder->items as $item) {
                            if ($item->ticketCategory) {
                                // Pessimistic lock the ticket category to update the sold field safely
                                $category = $item->ticketCategory()->lockForUpdate()->first();
                                $category->decrement('sold', $item->quantity);
                            }
                        }

                        $count++;
                    }
                });
            } catch (\Exception $e) {
                Log::error("Gagal membatalkan pesanan kedaluwarsa ID {$order->id}: " . $e->getMessage());
                $this->error("Gagal membatalkan pesanan #{$order->order_number}: {$e->getMessage()}");
            }
        }

        $this->info("Berhasil membatalkan {$count} pesanan kedaluwarsa dan mengembalikan kuotanya.");
        return 0;
    }
}
