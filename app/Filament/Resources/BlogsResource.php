<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Blogs;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\BlogsResource\Pages;
use Filament\Forms\Components\RichEditor;

class BlogsResource extends Resource
{
    protected static ?string $model = Blogs::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationGroup = 'Artikel';
    protected static ?string $navigationLabel = "Artikel";
    protected static ?string $modelLabel = "Artikel";
    protected static ?string $slug = "artikel";
    protected static ?string $pluralModelLabel = 'Artikel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),

                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->label('Slug'),


                RichEditor::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Gambar')
                    ->columnSpanFull()
                    ->required(),

                Hidden::make('user_id')
                    ->label('Penulis')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Penulis'),
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Ditulis pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlogs::route('/create'),
            'edit' => Pages\EditBlogs::route('/{record}/edit'),
        ];
    }
}
