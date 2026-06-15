<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Tiket Persekat - Periode {{ date('d/m/Y', strtotime($dateFrom)) }} s.d. {{ date('d/m/Y', strtotime($dateTo)) }}</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 30px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #cbd5e1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .header-title {
            text-align: right;
        }
        .header-title h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: 800;
            color: #dc2626; /* Persekat Red */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }
        .metadata {
            display: flex;
            justify-content: space-between;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 30px;
        }
        .metadata-item span {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
            font-weight: 600;
        }
        .metadata-item strong {
            font-size: 13px;
            color: #0f172a;
        }
        .stats-grid {
            display: grid;
            grid-template-cols: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background-color: #ffffff;
        }
        .stat-card span {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            border-left: 4px solid #dc2626;
            padding-left: 10px;
            margin: 30px 0 15px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .print-btn-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
        }
        .btn-print {
            background-color: #dc2626;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .btn-print:hover {
            background-color: #b91c1c;
        }
        @media print {
            body {
                padding: 0;
                font-size: 12px;
            }
            .print-btn-container {
                display: none;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>

    <!-- Print Button Floating -->
    <div class="print-btn-container">
        <button onclick="window.print()" class="btn-print">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Laporan
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <img src="{{ asset('logo.png') }}" alt="Logo Persekat" class="header-logo">
        <div class="header-title">
            <h1>Laporan Penjualan Tiket</h1>
            <p>Sistem E-Ticket Persekat Tegal</p>
        </div>
    </div>

    <!-- Metadata -->
    <div class="metadata">
        <div class="metadata-item">
            <span>Periode Laporan</span>
            <strong>{{ date('d M Y', strtotime($dateFrom)) }} - {{ date('d M Y', strtotime($dateTo)) }}</strong>
        </div>
        <div class="metadata-item">
            <span>Tanggal Cetak</span>
            <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong>
        </div>
        <div class="metadata-item">
            <span>Dicetak Oleh</span>
            <strong>{{ Auth::user()->name }}</strong>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <span>Total Pendapatan</span>
            <h3 style="color: #16a34a;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="stat-card">
            <span>Total Tiket Terjual</span>
            <h3>{{ number_format($totalTicketsSold, 0, ',', '.') }} Tiket</h3>
        </div>
        <div class="stat-card">
            <span>Total Transaksi</span>
            <h3>{{ number_format($totalOrders, 0, ',', '.') }} Pesanan</h3>
        </div>
    </div>

    <!-- Match Breakdown -->
    <div class="section-title">Penjualan per Pertandingan</div>
    <table>
        <thead>
            <tr>
                <th>Pertandingan</th>
                <th>Tanggal Kick-off</th>
                <th class="text-center">Tiket Terjual</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matchSales as $match)
                <tr>
                    <td style="font-weight: 700;">Persekat vs {{ $match->opponent }}</td>
                    <td>{{ date('d M Y, H:i', strtotime($match->match_date)) }} WIB</td>
                    <td class="text-center">{{ number_format($match->tickets_sold, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: 700; color: #1e293b;">Rp {{ number_format($match->revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #64748b; padding: 20px;">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Order Breakdown -->
    <div class="section-title">Rincian Transaksi Penjualan (Lunas)</div>
    <table>
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Tanggal Bayar</th>
                <th>Nama Pembeli</th>
                <th>Pertandingan & Kategori</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Total Bayar</th>
                <th class="text-center">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                @foreach($order->items as $index => $item)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $order->items->count() }}" class="font-mono" style="font-weight: 700; vertical-align: top;">
                                {{ $order->order_number }}
                            </td>
                            <td rowspan="{{ $order->items->count() }}" style="vertical-align: top;">
                                {{ $order->paid_at->format('d/m/Y H:i') }}
                            </td>
                            <td rowspan="{{ $order->items->count() }}" style="vertical-align: top; font-weight: 500;">
                                {{ $order->user->name }}
                            </td>
                        @endif
                        <td>
                            <div style="font-weight: 500;">vs {{ $item->ticketCategory->match->opponent ?? '-' }}</div>
                            <div style="font-size: 11px; color: #64748b;">{{ $item->ticketCategory->name ?? '-' }}</div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        @if($index === 0)
                            <td rowspan="{{ $order->items->count() }}" class="text-right font-mono" style="font-weight: 700; vertical-align: top;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td rowspan="{{ $order->items->count() }}" class="text-center" style="vertical-align: top;">
                                <span class="badge badge-success">{{ $order->payment_type ?? 'Midtrans' }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #64748b; padding: 20px;">Tidak ada transaksi pembayaran lunas pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Auto trigger print when loaded
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
