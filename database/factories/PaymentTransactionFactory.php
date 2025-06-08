<?php

namespace Database\Factories;

use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition()
    {
        $statuses = ['pending', 'capture', 'settlement', 'deny', 'cancel', 'expire', 'failure'];
        $paymentTypes = ['credit_card', 'bank_transfer', 'qris', 'echannel', 'gopay'];
        $banks = ['bca', 'bni', 'bri', 'mandiri', 'permata', 'other'];

        return [
            'order_id' => \App\Models\Order::factory(),
            'provider' => 'midtrans',
            'transaction_id' => $this->faker->uuid,
            'transaction_status' => $this->faker->randomElement($statuses),
            'payment_type' => $this->faker->randomElement($paymentTypes),
            'va_number' => $this->faker->optional()->numerify('#############'),
            'bank' => $this->faker->randomElement($banks),
            'expiry_time' => $this->faker->dateTimeBetween('+1 hour', '+3 days'),
            'payment_code' => $this->faker->optional()->bothify('#########'),
            'qr_code' => $this->faker->optional()->imageUrl(),
            'pdf_url' => $this->faker->optional()->url,
            'snap_token' => $this->faker->uuid,
            'snap_url' => $this->faker->optional()->url,
        ];
    }
}
