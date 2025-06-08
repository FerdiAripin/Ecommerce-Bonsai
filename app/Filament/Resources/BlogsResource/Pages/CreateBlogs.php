<?php

namespace App\Filament\Resources\BlogsResource\Pages;

use App\Filament\Resources\BlogsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Blogs;

class CreateBlogs extends CreateRecord
{
    protected static string $resource = BlogsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika slug kosong, buat dari title
        if (empty($data['slug'])) {
            $slug = Str::slug($data['title']);
            $originalSlug = $slug;
            $counter = 1;

            // Pastikan slug unik
            while (Blogs::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $data['slug'] = $slug;
        }

        return $data;
    }
}
