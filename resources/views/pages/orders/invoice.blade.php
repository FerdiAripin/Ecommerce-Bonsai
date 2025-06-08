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

        .invoice-container {
            max-width: 100%;
            margin: 0;
            background-color: #fff;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }

        .invoice-meta {
            text-align: right;
            font-size: 11px;
        }

        .invoice-meta div {
            margin: 3px 0;
        }

        .invoice-meta strong {
            display: inline-block;
            width: 100px;
            text-align: left;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-section h3 {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .info-section p {
            margin: 2px 0;
            color: #000;
            font-size: 10px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th {
            background-color: #666;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
        }

        .invoice-table th:nth-child(4),
        .invoice-table td:nth-child(4) {
            text-align: center;
        }

        .invoice-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .invoice-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-name {
            font-weight: bold;
            color: #000;
        }

        .product-description {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }

        .totals-section {
            margin-top: 20px;
            float: right;
            width: 250px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px 0;
            font-size: 11px;
            border-bottom: 1px solid #ddd;
        }

        .totals-table td:first-child {
            text-align: left;
            font-weight: normal;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .totals-table tr:last-child {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            padding-top: 10px;
        }

        .notes-section {
            margin-top: 30px;
            clear: both;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .notes-section h3 {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .notes-section p {
            font-size: 9px;
            color: #000;
            margin: 5px 0;
        }

        .terms-section {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .terms-section h3 {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .terms-section ol {
            padding-left: 15px;
            margin: 0;
        }

        .terms-section li {
            font-size: 8px;
            color: #000;
            margin: 4px 0;
            line-height: 1.2;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 10px;
        }

        .status-pending { background-color: #f0f0f0; color: #666; }
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }

        @media print {
            body {
                margin: 0;
                padding: 10px;
                font-size: 9px;
            }
            .invoice-container {
                max-width: none;
                height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .invoice-header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }
            .invoice-title {
                font-size: 24px;
            }
            .invoice-info {
                margin-bottom: 15px;
            }
            .invoice-table {
                margin-bottom: 15px;
                flex-grow: 1;
            }
            .totals-section {
                break-inside: avoid;
                margin-top: 15px;
            }
            .notes-section {
                break-inside: avoid;
                margin-top: 20px;
                padding-top: 10px;
            }
            .terms-section {
                margin-top: 10px;
                padding-top: 10px;
            }
            .notes-section h3,
            .terms-section h3 {
                font-size: 10px;
            }
            .notes-section p {
                font-size: 8px;
            }
            .terms-section li {
                font-size: 7px;
                line-height: 1.1;
                margin: 2px 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1 class="invoice-title">INVOICE</h1>
            <div class="invoice-meta">
                <div><strong>Nomor Invoice</strong> #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div><strong>Tanggal Invoice</strong> {{ $order->created_at->format('d/n/Y') }}</div>
            </div>
        </div>

        <div class="invoice-info">
            <div class="info-section">
                <h3>Informasi Pesanan</h3>
                <p>Nama Pembeli : {{ $order->user->name }}</p>
                <p>Email : {{ $order->user->email }}</p>
                <p>No Handphone : {{ $order->phone_number }}</p>
                <p>Status Pembayaran : {{ $order->payment_status}}</p>
            </div>
            <div class="info-section">
                <h3>Detail Pengiriman</h3>
                <p>Nama Penerima : {{ $order->recipient_name }}</p>
                <p>Alamat Penerima : {{ $order->shipping_address }}, </p>
                <p>{{ $order->city }}, {{ $order->province }}, {{ $order->postal_code }}</p>
                <p>Kurir : {{$order->courier}}</p>
                <p>Layanan : {{$order->courier_service}}</p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->details as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item->product->name }}</div>
                        @if($item->product->description)
                        <div class="product-description">{{ Str::limit($item->product->description, 100) }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->price, 2) }} IDR</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>{{ number_format($order->total_amount, 2) }} IDR</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td>{{ number_format($order->shipping_cost, 2) }} IDR</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{{ number_format($order->grand_total, 2) }} IDR</td>
                </tr>
            </table>
        </div>

        <div class="notes-section">
            <h3>Catatan Tambahan</h3>
            @if($order->notes)
            <p>{{ $order->notes }}</p>
            @else
            <p>Tidak ada catatan tambahan.</p>
            @endif
        </div>

        <div class="terms-section">
            <h3>Syarat dan Ketentuan</h3>
            <ol>
                <li>Penjual tidak bertanggung jawab kepada Pembeli, baik secara langsung maupun tidak langsung, atas kerugian atau kerusakan yang dialami oleh Pembeli.</li>
                <li>Penjual memberikan garansi produk selama satu (1) tahun sejak tanggal pengiriman.</li>
                <li>Setiap pesanan pembelian yang diterima oleh Penjual akan diartikan sebagai penerimaan atas penawaran ini dan penawaran penjualan secara tertulis. Pembeli hanya dapat membeli produk dalam penawaran ini berdasarkan Syarat dan Ketentuan Penjual yang tercantum dalam penawaran ini.</li>
            </ol>
        </div>
    </div>
</body>
</html>
