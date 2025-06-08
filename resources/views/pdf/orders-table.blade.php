<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 15px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #000;
            font-size: 28px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .header p {
            color: #000;
            margin: 3px 0;
            font-size: 11px;
        }

        .table-container {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th {
            background-color: #666;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 6px 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .order-id {
            font-family: monospace;
            font-weight: bold;
            color: #000;
        }

        .amount {
            font-weight: bold;
            color: #000;
            text-align: right;
        }

        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            margin: 25px 0;
        }

        .summary h3 {
            margin: 0 0 12px 0;
            color: #000;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 15px;
        }

        .summary-item {
            text-align: center;
            padding: 8px;
            background: white;
            border: 1px solid #ddd;
        }

        .summary-number {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .stats-section {
            background: white;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .stats-title {
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            font-size: 10px;
        }

        .stats-label {
            color: #666;
        }

        .stats-value {
            font-weight: bold;
            color: #000;
        }

        .performance-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            font-weight: bold;
            font-size: 11px;
        }

        .indicator-excellent { color: #155724; }
        .indicator-good { color: #155724; }
        .indicator-average { color: #856404; }
        .indicator-poor { color: #721c24; }

        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
                font-size: 9px;
            }
            .summary {
                break-inside: avoid;
                margin: 15px 0;
            }
            tr {
                break-inside: avoid;
            }
            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }
            .header h1 {
                font-size: 24px;
            }
            .summary h3 {
                font-size: 12px;
            }
            .summary-number {
                font-size: 14px;
            }
            .stats-title {
                font-size: 10px;
            }
            .performance-indicator {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Periode:</strong> {{ $period }}</p>
        <p>Tanggal Generate: {{ $generated_at }}</p>
        <p>Total Pesanan: {{ $totalOrders }} pesanan</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">ID Pesanan</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 18%;">Pelanggan</th>
                    <th style="width: 15%;">Total</th>
                    <th style="width: 15%;">Status Pesanan</th>
                    <th style="width: 15%;">Status Pembayaran</th>
                    <th style="width: 10%;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="order-id">
                            ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            @switch($order->status)
                                @case('pending') Menunggu @break
                                @case('in_shipping') Dalam Pengiriman @break
                                @case('delivered') Terkirim @break
                                @case('success') Sukses @break
                                @case('cancelled') Dibatalkan @break
                                @default {{ $order->status }} @break
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <span class="payment-badge payment-{{ $order->payment_status ?? 'unpaid' }}">
                            @switch($order->payment_status)
                                @case('paid') Lunas @break
                                @case('refunded') Dikembalikan @break
                                @case('unpaid')
                                @default Belum Dibayar @break
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('H:i') }}</td>
                </tr>
                @endforeach

                @if($orders->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #666; font-style: italic;">
                        Tidak ada data pesanan untuk periode yang dipilih
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Analisis Performa:</strong>
            @if($totalOrders > 0)
                @if($successRate >= 80)
                    Bisnis berjalan dengan SANGAT BAIK dengan tingkat keberhasilan {{ number_format($successRate, 1) }}%
                @elseif($successRate >= 60)
                    Bisnis berjalan dengan BAIK dengan tingkat keberhasilan {{ number_format($successRate, 1) }}%
                @elseif($successRate >= 40)
                    Performa bisnis CUKUP dengan tingkat keberhasilan {{ number_format($successRate, 1) }}%. Perlu optimalisasi proses.
                @else
                    Performa bisnis PERLU DIPERBAIKI dengan tingkat keberhasilan {{ number_format($successRate, 1) }}%. Evaluasi menyeluruh diperlukan.
                @endif
                <br>
                Total pendapatan periode ini: <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
            @else
                Belum ada data pesanan untuk periode ini
            @endif
        </p>
        <p>Laporan ini digenerate secara otomatis pada {{ $generated_at }}</p>
        <p>© {{ date('Y') }} - Ruang Bonsai</p>
    </div>
</body>
</html>
