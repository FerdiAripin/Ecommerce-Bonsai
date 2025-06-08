<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OrderWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->where('created_at', '<=', now()->endOfMonth())
                    ->where('status', '!=', 'in_cart') // Exclude cart items
                    ->with(['user', 'details'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->formatStateUsing(fn($state) => 'ORD-' . str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'in_shipping' => 'Di Proses',
                        'delivered' => 'Dalam Pengiriman',
                        'success' => 'Sukses',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->label('Status')
                    ->disabled(),

                Tables\Columns\SelectColumn::make('payment_status')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->label('Status Pembayaran')
                    ->disabled(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'in_shipping' => 'Di Proses',
                        'delivered' => 'Dalam Pengiriman',
                        'success' => 'Sukses',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->label('Status Pembayaran'),
            ])
            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('Lihat'),
            // ])
            ->defaultSort('created_at', 'desc')
            ->paginated(5);
    }

    public static function getLabel(): string
    {
        return 'Pesanan Bulan Ini';
    }

    protected function getTableHeading(): string
    {
        return 'Daftar Pesanan Bulan Ini (' . now()->translatedFormat('F Y') . ')';
    }
}
