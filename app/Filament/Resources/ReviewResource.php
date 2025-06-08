<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?string $navigationLabel = 'Ulasan';
    protected static ?string $modelLabel = 'Ulasan';
    protected static ?string $pluralModelLabel = 'Ulasan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Ulasan')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pengguna')
                            ->disabled(),
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->label('Produk')
                            ->disabled(),
                        Select::make('order_id')
                            ->relationship('order', 'id')
                            ->label('ID Pesanan')
                            ->disabled(),
                        TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->disabled(),
                        Textarea::make('comment')
                            ->label('Komentar')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('order.id')
                    ->label('ID Pesanan')
                    ->formatStateUsing(fn($state) => 'ORD-' . str_pad($state, 6, '0', STR_PAD_LEFT)),
                RatingColumn::make('rating')
                    ->label('Rating')
                    ->color('warning'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Produk'),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Pengguna'),
                Filter::make('high_rating')
                    ->label('Rating Tinggi')
                    ->query(fn($query) => $query->where('rating', '>=', 4)),
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
                        $reviews = Review::with(['user', 'product', 'order'])
                            ->whereDate('created_at', '>=', $data['export_from'])
                            ->whereDate('created_at', '<=', $data['export_until'])
                            ->orderBy('created_at', 'desc')
                            ->get();

                        return static::exportToPdf($reviews, $data['export_from'], $data['export_until']);
                    }),
            ]);
    }

    public static function exportToPdf(Collection $reviews, $dateFrom = null, $dateUntil = null): \Symfony\Component\HttpFoundation\Response
    {
        // Analisis data untuk summary
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        $highRatings = $reviews->where('rating', '>=', 4)->count();
        $lowRatings = $reviews->where('rating', '<=', 2)->count();
        $mediumRatings = $reviews->whereBetween('rating', [3, 3])->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $reviews->where('rating', $i)->count();
        }

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

        $pdf = Pdf::loadView('pdf.reviews-table', [
            'reviews' => $reviews,
            'title' => 'Laporan Ulasan Produk',
            'period' => $period,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'highRatings' => $highRatings,
            'lowRatings' => $lowRatings,
            'mediumRatings' => $mediumRatings,
            'ratingDistribution' => $ratingDistribution,
        ]);

        $filename = 'laporan-ulasan-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->stream()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReviews::route('/'),
        ];
    }
}
