<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'shipping_cost',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'recipient_name',
        'phone_number',
        'shipping_address',
        'province',
        'city',
        'district',
        'postal_code',
        'courier',
        'courier_service',
        'tracking_number',
        'estimated_arrival',
        'shipped_at',
        'arrived_at',
        'shipping_status',
        'notes',
        'payment_proof'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function scopeCart($query, $userId)
    {
        return $query->where('user_id', $userId)->where('status', 'in_cart');
    }
}
