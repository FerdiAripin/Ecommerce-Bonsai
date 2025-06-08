<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        return [
            // Customer and Product Stats
            Stat::make('Total Pelanggan', User::where('roles', 'Customer')->count())
                ->description('Jumlah pelanggan terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Total Produk', Product::count())
                ->description('Jumlah produk yang ada')
                ->icon('heroicon-o-archive-box')
                ->color('primary'),

            // Order Status Stats
            Stat::make('Pesanan Pending', Order::where('status', 'pending')->count())
                ->description('Pesanan menunggu konfirmasi')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Pesanan Diproses', Order::where('status', 'in_shipping')->count())
                ->description('Pesanan dalam proses')
                ->icon('heroicon-o-truck')
                ->color('info'),

            Stat::make('Pesanan Dikirim', Order::where('status', 'delivered')->count())
                ->description('Pesanan dalam pengiriman')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Pesanan Selesai', Order::where('status', 'success')->count())
                ->description('Pesanan berhasil diselesaikan')
                ->icon('heroicon-o-gift')
                ->color('success'),

            Stat::make('Pesanan Dibatalkan', Order::where('status', 'cancelled')->count())
                ->description('Pesanan telah dibatalkan')
                ->icon('heroicon-o-gift')
                ->color('danger'),

            // Additional financial stats
            Stat::make('Total Pendapatan', 'Rp ' . number_format(
                Order::where('status', 'success')->sum('grand_total'),
                0,
                ',',
                '.'
            ))
                ->description('Total pendapatan dari pesanan yang berhasil diselesaikan')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')

        ];
    }
}
