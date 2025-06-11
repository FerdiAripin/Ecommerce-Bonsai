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
            grid-template-columns: repeat(3, 1fr);
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

        .rating-stars {
            color: #000;
            font-size: 12px;
            white-space: nowrap;
            font-weight: bold;
        }

        .rating-stars .empty {
            color: #ccc;
        }

        .comment-cell {
            max-width: 200px;
            word-wrap: break-word;
            font-size: 9px;
            line-height: 1.3;
        }

        .no-comment {
            color: #666;
            font-style: italic;
        }

        .order-id {
            font-family: monospace;
            font-weight: bold;
            color: #000;
        }

        .status-badge {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }

        .status-excellent { color: #155724; }
        .status-good { color: #155724; }
        .status-average { color: #856404; }
        .status-poor { color: #721c24; }
        .status-very-poor { color: #721c24; }

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
        <p>Total Ulasan: {{ $totalReviews }} ulasan</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 15%;">Pengguna</th>
                    <th style="width: 20%;">Produk</th>
                    <th style="width: 12%;">ID Pesanan</th>
                    <th style="width: 12%;">Rating</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 16%;">Komentar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $index => $review)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $review->created_at->format('d/m/Y') }}</td>
                    <td>{{ $review->user->name ?? 'N/A' }}</td>
                    <td>{{ $review->product->name ?? 'N/A' }}</td>
                    <td>
                        <span class="order-id">
                            {{ $review->order ? 'ORD-' . str_pad($review->order->id, 6, '0', STR_PAD_LEFT) : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? '' : 'empty' }}">★</span>
                            @endfor
                            {{-- ({{ $review->rating }}) --}}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge
                            @if($review->rating == 5) status-excellent
                            @elseif($review->rating == 4) status-good
                            @elseif($review->rating == 3) status-average
                            @elseif($review->rating == 2) status-poor
                            @else status-very-poor
                            @endif
                        ">
                            @if($review->rating == 5) Sangat Baik
                            @elseif($review->rating == 4) Baik
                            @elseif($review->rating == 3) Cukup
                            @elseif($review->rating == 2) Buruk
                            @else Sangat Buruk
                            @endif
                        </span>
                    </td>
                    <td class="comment-cell">
                        @if($review->comment && trim($review->comment) !== '')
                            {{ Str::limit($review->comment, 80) }}
                        @else
                            <span class="no-comment">Tidak ada komentar</span>
                        @endif
                    </td>
                </tr>
                @endforeach

                @if($reviews->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #666; font-style: italic;">
                        Tidak ada data ulasan untuk periode yang dipilih
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Analisis Performa:</strong>
            @if($totalReviews > 0)
                @if($averageRating >= 4)
                    Kualitas produk dan layanan dalam kondisi SANGAT BAIK dengan rating rata-rata {{ $averageRating }}/5
                @elseif($averageRating >= 3)
                    Kualitas produk dan layanan dalam kondisi BAIK dengan rating rata-rata {{ $averageRating }}/5
                @elseif($averageRating >= 2)
                    Kualitas produk dan layanan PERLU DIPERBAIKI dengan rating rata-rata {{ $averageRating }}/5
                @else
                    Kualitas produk dan layanan dalam kondisi BURUK dan PERLU PERBAIKAN SEGERA dengan rating rata-rata {{ $averageRating }}/5
                @endif
                <br>
                Tingkat kepuasan pelanggan: <strong>{{ number_format(($totalReviews > 0 ? ($highRatings / $totalReviews) * 100 : 0), 1) }}%</strong>
            @else
                Belum ada data ulasan untuk periode ini
            @endif
        </p>
        <p>Laporan ini digenerate secara otomatis pada {{ $generated_at }}</p>
        <p>© {{ date('Y') }} - Ruang Bonsai</p>
    </div>
</body>
</html>
