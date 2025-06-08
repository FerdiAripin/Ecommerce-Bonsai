<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Categories;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\CategoriesResource\Pages;
use Filament\Forms\Components\Textarea;

class CategoriesResource extends Resource
{
    protected static ?string $model = Categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = "Produk";
    protected static ?string $navigationLabel = "Kategori Produk";
    protected static ?string $modelLabel = "Kategori Produk";
    protected static ?string $slug = "kategori-produk";
    protected static ?string $pluralModelLabel = 'Kategori Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('categories_name')
                    ->label('Nama Kategori')
                    ->columnSpanFull()
                    ->required()
                    ->unique(Categories::class, 'categories_name', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Kategori dengan nama ini sudah ada.',
                    ]),

                FileUpload::make('image')
                    ->label('Gambar')
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('categories_name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),

                TextColumn::make('description')
                    ->label('Deskripsi')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
