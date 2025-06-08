<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'va_number',
        'bank',
        'expiry_time',
        'payment_code',
        'qr_code',
        'pdf_url',
        'snap_token',
        'snap_url'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
