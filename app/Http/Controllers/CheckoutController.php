<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\TicketCategory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ETicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Show checkout page for a match.
     */
    public function index(FootballMatch $match)
    {
        if (!in_array($match->status, ['published', 'live'])) {
            abort(404);
        }

        $match->load('ticketCategories');

        return view('checkout.index', compact('match'));
    }

    /**
     * Process ticket order with pessimistic locking.
     */
    public function store(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.category_id' => 'required|exists:ticket_categories,id',
            'tickets.*.quantity' => 'required|integer|min:0|max:4',
        ]);

        // Filter out categories with 0 quantity (not selected)
        $selectedTickets = collect($validated['tickets'])->filter(fn($t) => $t['quantity'] > 0)->values()->all();

        if (empty($selectedTickets)) {
            return back()->with('error', 'Pilih minimal 1 tiket sebelum melanjutkan.');
        }

        $totalQuantity = collect($selectedTickets)->sum('quantity');

        // Max 4 tickets per order
        if ($totalQuantity > (int) config('app.max_tickets_per_order', 4)) {
            return back()->with('error', 'Maksimal 4 tiket per pemesanan.');
        }

        // Check if user has already bought tickets for this match
        $existingTickets = OrderItem::whereHas('order', function ($q) {
                $q->where('user_id', Auth::id())
                  ->whereIn('status', ['pending', 'paid']);
            })
            ->whereHas('ticketCategory', function ($q) use ($match) {
                $q->where('match_id', $match->id);
            })
            ->sum('quantity');

        if (($existingTickets + $totalQuantity) > 4) {
            return back()->with('error', 'Anda sudah memiliki ' . $existingTickets . ' tiket untuk pertandingan ini. Maksimal 4 tiket per akun.');
        }

        // Use filtered tickets from here on
        $validated['tickets'] = $selectedTickets;

        try {
            $order = DB::transaction(function () use ($validated, $match) {
                $totalAmount = 0;
                $orderItems = [];

                foreach ($validated['tickets'] as $ticketData) {
                    if ($ticketData['quantity'] <= 0) continue;

                    // Pessimistic locking to prevent overselling
                    $category = TicketCategory::where('id', $ticketData['category_id'])
                        ->where('match_id', $match->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!$category->isAvailable($ticketData['quantity'])) {
                        throw new \Exception("Stok tiket {$category->name} tidak mencukupi. Tersisa: {$category->availableQuota()}");
                    }

                    // Increment sold count
                    $category->increment('sold', $ticketData['quantity']);

                    $subtotal = $category->price * $ticketData['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'ticket_category_id' => $category->id,
                        'quantity' => $ticketData['quantity'],
                        'price' => $category->price,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($orderItems)) {
                    throw new \Exception('Tidak ada tiket yang dipilih.');
                }

                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => Order::generateOrderNumber(),
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'expired_at' => now()->addMinutes((int) config('app.ticket_expiry_minutes', 30)),
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                return $order;
            });

            return redirect()->route('payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
