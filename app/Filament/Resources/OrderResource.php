<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\DetailsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?string $slug = 'pesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID Pesanan')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'in_cart' => 'Dalam Keranjang',
                                'pending' => 'Menunggu',
                                'in_shipping' => 'Dalam Pengiriman',
                                'delivered' => 'Terkirim',
                                'success' => 'Sukses',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('shipping_status')
                            ->options([
                                'preparing' => 'Disiapkan',
                                'shipped' => 'Dikirim',
                                'in_transit' => 'Dalam Perjalanan',
                                'delivered' => 'Terkirim',
                            ])
                            ->label('Status Pengiriman'),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi'),
                        Forms\Components\DateTimePicker::make('estimated_arrival')
                            ->label('Perkiraan Tiba'),
                        Forms\Components\TextInput::make('courier')
                            ->label('Kurir'),
                        Forms\Components\TextInput::make('courier_service')
                            ->label('Layanan Kurir'),
                    ])->columns(2),

                Forms\Components\Section::make('Status Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Belum Dibayar',
                                'paid' => 'Lunas',
                                'refunded' => 'Dikembalikan',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest()
                    ->where('status', '!=', 'in_cart')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->formatStateUsing(fn($state) => 'ORD-' . str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        // 'in_cart' => 'Dalam Keranjang',
                        'pending' => 'Menunggu',
                        'in_shipping' => 'Di Proses',
                        'delivered' => 'Dalam Pengiriman',
                        'success' => 'Sukses',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        // 'in_cart' => 'Dalam Keranjang',
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Filter Tanggal'),
                Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn($query) => $query->whereMonth('created_at', now()->month)
                                                ->whereYear('created_at', now()->year)),
                Filter::make('last_month')
                    ->label('Bulan Lalu')
                    ->query(fn($query) => $query->whereMonth('created_at', now()->subMonth()->month)
                                                ->whereYear('created_at', now()->subMonth()->year)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus yang Dipilih'),
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export ke PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->form([
                            DatePicker::make('export_from')
                                ->label('Dari Tanggal')
                                ->default(now()->startOfMonth()),
                            DatePicker::make('export_until')
                                ->label('Sampai Tanggal')
                                ->default(now()->endOfMonth()),
                        ])
                        ->action(function (Collection $records, array $data) {
                            // Filter records berdasarkan tanggal jika ada
                            if ($data['export_from'] || $data['export_until']) {
                                $filtered = $records->filter(function ($record) use ($data) {
                                    $recordDate = Carbon::parse($record->created_at);

                                    if ($data['export_from'] && $recordDate->lt(Carbon::parse($data['export_from']))) {
                                        return false;
                                    }

                                    if ($data['export_until'] && $recordDate->gt(Carbon::parse($data['export_until']))) {
                                        return false;
                                    }

                                    return true;
                                });

                                return static::exportToPdf($filtered, $data['export_from'], $data['export_until']);
                            }

                            return static::exportToPdf($records);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->headerActions([
                Action::make('export_all_pdf')
                    ->label('Export Laporan PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->form([
                        DatePicker::make('export_from')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth())
                            ->required(),
                        DatePicker::make('export_until')
                            ->label('Sampai Tanggal')
                            ->default(now()->endOfMonth())
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $orders = Order::with(['user'])
                            ->where('status', '!=', 'in_cart')
                            ->whereDate('created_at', '>=', $data['export_from'])
                            ->whereDate('created_at', '<=', $data['export_until'])
                            ->orderBy('created_at', 'desc')
                            ->get();

                        return static::exportToPdf($orders, $data['export_from'], $data['export_until']);
                    }),
            ]);
    }

    public static function exportToPdf(Collection $orders, $dateFrom = null, $dateUntil = null): \Symfony\Component\HttpFoundation\Response
    {
        // Analisis data untuk summary
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('grand_total');
        $avgOrderValue = $totalOrders > 0 ? $orders->avg('grand_total') : 0;

        // Status distribution
        $statusCounts = [
            'pending' => $orders->where('status', 'pending')->count(),
            'in_shipping' => $orders->where('status', 'in_shipping')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
            'success' => $orders->where('status', 'success')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];

        // Payment status distribution
        $paymentCounts = [
            'unpaid' => $orders->where('payment_status', 'unpaid')->count(),
            'paid' => $orders->where('payment_status', 'paid')->count(),
            'refunded' => $orders->where('payment_status', 'refunded')->count(),
        ];

        // Success rate calculation
        $completedOrders = $orders->whereIn('status', ['delivered', 'success'])->count();
        $successRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

        // Format periode
        $period = '';
        if ($dateFrom && $dateUntil) {
            $period = Carbon::parse($dateFrom)->format('d/m/Y') . ' - ' . Carbon::parse($dateUntil)->format('d/m/Y');
        } elseif ($dateFrom) {
            $period = 'Sejak ' . Carbon::parse($dateFrom)->format('d/m/Y');
        } elseif ($dateUntil) {
            $period = 'Sampai ' . Carbon::parse($dateUntil)->format('d/m/Y');
        } else {
            $period = 'Semua Periode';
        }

        $pdf = Pdf::loadView('pdf.orders-table', [
            'orders' => $orders,
            'title' => 'Laporan Pesanan',
            'period' => $period,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'avgOrderValue' => $avgOrderValue,
            'statusCounts' => $statusCounts,
            'paymentCounts' => $paymentCounts,
            'successRate' => $successRate,
        ]);

        $filename = 'laporan-pesanan-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->stream()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
            // PaymentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
