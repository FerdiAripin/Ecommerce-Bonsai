<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Categories extends Model
{
    use HasFactory;

    protected $fillable =[
        'categories_name',
        'image',
        'description'
    ];

    public function products()
    {

        return $this->hasMany(Product::class);

    }
}
