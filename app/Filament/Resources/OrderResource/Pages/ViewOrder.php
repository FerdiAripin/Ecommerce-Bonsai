<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Ringkasan Pesanan')
                    ->schema([
                        Components\TextEntry::make('id')
                            ->label('ID Pesanan')
                            ->formatStateUsing(fn($state) => 'ORD-' . str_pad($state, 6, '0', STR_PAD_LEFT)),
                        Components\TextEntry::make('created_at')
                            ->label('Tanggal Pesanan')
                            ->dateTime(),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'in_cart' => 'gray',
                                'pending' => 'warning',
                                'in_shipping' => 'info',
                                'delivered' => 'success',
                                'success' => 'success',
                                'cancelled' => 'danger',
                            })
                            ->label('Status'),
                        Components\TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'unpaid' => 'danger',
                                'paid' => 'success',
                                'refunded' => 'warning',
                            })
                            ->label('Status Pembayaran'),
                        Components\TextEntry::make('grand_total')
                            ->money('IDR')
                            ->label('Total Pembayaran'),
                    ])
                    ->columns(3),

                Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Components\TextEntry::make('user.name')
                            ->label('Nama Pelanggan'),
                        Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Components\TextEntry::make('recipient_name')
                            ->label('Nama Penerima'),
                        Components\TextEntry::make('phone_number')
                            ->label('Nomor Telepon'),
                    ])
                    ->columns(2),

                Components\Section::make('Informasi Pengiriman')
                    ->schema([
                        Components\TextEntry::make('shipping_address')
                            ->label('Alamat Pengiriman'),
                        Components\TextEntry::make('province')
                            ->label('Provinsi'),
                        Components\TextEntry::make('city')
                            ->label('Kota/Kabupaten'),
                        // Components\TextEntry::make('district')
                        //     ->label('Kecamatan'),
                        Components\TextEntry::make('postal_code')
                            ->label('Kode Pos'),
                        Components\TextEntry::make('courier')
                            ->label('Kurir'),
                        Components\TextEntry::make('courier_service')
                            ->label('Layanan Kurir'),
                        Components\TextEntry::make('tracking_number')
                            ->label('Nomor Resi'),
                        Components\TextEntry::make('estimated_arrival')
                            ->label('Perkiraan Tiba')
                            ->dateTime(),
                        Components\TextEntry::make('shipping_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'preparing' => 'gray',
                                'shipped' => 'info',
                                'in_transit' => 'warning',
                                'delivered' => 'success',
                            })
                            ->label('Status Pengiriman'),
                    ])
                    ->columns(2),

                Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Components\TextEntry::make('payment_method')
                            ->label('Metode Pembayaran'),
                        Components\TextEntry::make('payment.payment_type')
                            ->label('Tipe Pembayaran'),
                        Components\TextEntry::make('payment.transaction_status')
                            ->label('Status Transaksi')
                            ->badge(),
                        Components\TextEntry::make('payment.bank')
                            ->label('Bank'),
                        Components\TextEntry::make('payment.va_number')
                            ->label('Nomor Virtual Account'),
                        Components\TextEntry::make('payment.expiry_time')
                            ->label('Waktu Kedaluwarsa')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),

            Action::make('invoice')
                ->label('Cetak Invoice')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('orders.invoice', $record->id))
                ->openUrlInNewTab(),
        ];
    }
}
