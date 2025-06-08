<?php

namespace App\Filament\Resources\BlogsResource\Pages;

use App\Models\Blogs;
use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BlogsResource;

class EditBlogs extends EditRecord
{
    protected static string $resource = BlogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
{
    if (empty($data['slug'])) {
        $slug = Str::slug($data['title']);
        $originalSlug = $slug;
        $counter = 1;

        while (Blogs::where('slug', $slug)->where('id', '!=', $this->record->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $data['slug'] = $slug;
    }

    return $data;
}

}
