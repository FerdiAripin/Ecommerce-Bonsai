<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories_id',
        'name',
        'image',
        'description',
        'price',
        'discount',
        'stock'
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Method untuk menghitung rating rata-rata
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
