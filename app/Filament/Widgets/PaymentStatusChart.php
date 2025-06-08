<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Carbon\Carbon;

class PaymentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pembayaran';

    protected static ?int $sort = 6;

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
        $query = Order::where('status', '!=', 'in_cart');

        // Apply filter berdasarkan bulan yang dipilih
        if ($this->filter && $this->filter !== 'all') {
            $filterDate = Carbon::createFromFormat('Y-m', $this->filter);
            $query->whereYear('created_at', $filterDate->year)
                  ->whereMonth('created_at', $filterDate->month);
        }

        // Data untuk doughnut chart status pembayaran
        $paymentStatusData = [
            'unpaid' => (clone $query)->where('payment_status', 'unpaid')->count(),
            'paid' => (clone $query)->where('payment_status', 'paid')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Status Pembayaran',
                    'data' => array_values($paymentStatusData),
                    'backgroundColor' => [
                        '#ef4444', // unpaid - danger
                        '#10b981', // paid - success
                    ],
                    'borderColor' => [
                        '#ef4444',
                        '#10b981',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ]
            ],
            'labels' => [
                'Belum Dibayar (' . $paymentStatusData['unpaid'] . ')',
                'Dibayar (' . $paymentStatusData['paid'] . ')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
            'cutout' => '50%',
        ];
    }
}
