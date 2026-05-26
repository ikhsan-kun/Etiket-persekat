<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Show reports page.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $salesData = Order::paid()
            ->whereBetween('paid_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $matchSales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('ticket_categories', 'order_items.ticket_category_id', '=', 'ticket_categories.id')
            ->join('matches', 'ticket_categories.match_id', '=', 'matches.id')
            ->where('orders.status', 'paid')
            ->whereBetween('orders.paid_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                'matches.opponent',
                'matches.match_date',
                DB::raw('SUM(order_items.quantity) as tickets_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('matches.id', 'matches.opponent', 'matches.match_date')
            ->orderBy('revenue', 'desc')
            ->get();

        $totalRevenue = $salesData->sum('revenue');
        $totalOrders = $salesData->sum('total_orders');

        return view('admin.reports.index', compact(
            'salesData',
            'matchSales',
            'totalRevenue',
            'totalOrders',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Export sales data to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $orders = Order::with(['user', 'items.ticketCategory.match'])
            ->paid()
            ->whereBetween('paid_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->get();

        $filename = 'laporan-penjualan-' . $dateFrom . '-' . $dateTo . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'No. Order',
                'Tanggal Bayar',
                'Nama Pembeli',
                'Email',
                'Pertandingan',
                'Kategori Tiket',
                'Jumlah',
                'Harga Satuan',
                'Subtotal',
                'Total Order',
                'Metode Bayar',
            ]);

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->paid_at->format('Y-m-d H:i'),
                        $order->user->name,
                        $order->user->email,
                        'Persekat vs ' . ($item->ticketCategory->match->opponent ?? '-'),
                        $item->ticketCategory->name ?? '-',
                        $item->quantity,
                        number_format($item->price, 0, ',', '.'),
                        number_format($item->subtotal, 0, ',', '.'),
                        number_format($order->total_amount, 0, ',', '.'),
                        $order->payment_type ?? '-',
                    ]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
