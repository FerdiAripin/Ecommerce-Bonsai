<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrderChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Status Pesanan';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $filters = ['all' => 'Semua Data'];

        // Filter untuk tahun sekarang saja
        $currentYear = date('Y');

        // Generate filter untuk 12 bulan di tahun sekarang
        for ($month = 12; $month >= 1; $month--) {
            $date = Carbon::create($currentYear, $month, 1);
            $filters[$date->format('Y-m')] = $date->format('F Y');
        }

        return $filters;
    }

    protected function getData(): array
    {
        $query = Order::query();

        // Apply filter berdasarkan bulan yang dipilih
        if ($this->filter && $this->filter !== 'all') {
            $filterDate = Carbon::createFromFormat('Y-m', $this->filter);
            $query->whereYear('created_at', $filterDate->year)
                  ->whereMonth('created_at', $filterDate->month);
        }

        // Data untuk pie chart status pesanan
        $statusData = [
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'in_shipping' => (clone $query)->where('status', 'in_shipping')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'success' => (clone $query)->where('status', 'success')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Status Pesanan',
                    'data' => array_values($statusData),
                    'backgroundColor' => [
                        '#f59e0b', // pending - warning
                        '#3b82f6', // in_shipping - info
                        '#10b981', // delivered - success
                        '#059669', // success - success darker
                        '#ef4444', // cancelled - danger
                    ],
                    'borderColor' => [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#059669',
                        '#ef4444',
                    ],
                    'borderWidth' => 2,
                ]
            ],
            'labels' => [
                'Menunggu (' . $statusData['pending'] . ')',
                'Di Proses (' . $statusData['in_shipping'] . ')',
                'Dalam Pengiriman (' . $statusData['delivered'] . ')',
                'Sukses (' . $statusData['success'] . ')',
                'Dibatalkan (' . $statusData['cancelled'] . ')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
