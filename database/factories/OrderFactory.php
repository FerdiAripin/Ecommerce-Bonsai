<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $statuses = ['in_cart', 'pending', 'in_shipping', 'delivered', 'success', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $paymentMethods = ['bank_transfer', 'credit_card', 'e-wallet', 'cash_on_delivery'];
        $couriers = ['jne', 'tiki', 'pos', 'jnt', 'sicepat'];
        $shippingStatuses = ['preparing', 'shipped', 'in_transit', 'delivered'];

        return [
            'user_id' => $user->id,
            'total_amount' => $this->faker->numberBetween(100000, 10000000),
            'shipping_cost' => $this->faker->numberBetween(5000, 50000),
            'grand_total' => function (array $attributes) {
                return $attributes['total_amount'] + $attributes['shipping_cost'];
            },
            'status' => $this->faker->randomElement($statuses),
            'payment_status' => $this->faker->randomElement($paymentStatuses),
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'recipient_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'shipping_address' => $this->faker->address,
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'district' => $this->faker->citySuffix,
            'postal_code' => $this->faker->postcode,
            'courier' => $this->faker->randomElement($couriers),
            'courier_service' => $this->faker->randomElement(['REG', 'ECO', 'ONS']),
            'tracking_number' => $this->faker->bothify('??##########'),
            'estimated_arrival' => $this->faker->dateTimeBetween('+1 day', '+2 weeks'),
            'shipped_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'arrived_at' => $this->faker->optional()->dateTimeBetween('-3 days', 'now'),
            'shipping_status' => $this->faker->randomElement($shippingStatuses),
            'notes' => $this->faker->optional()->sentence,
            'payment_proof' => $this->faker->optional()->imageUrl(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Create 1-5 order details for each order
            \App\Models\OrderDetail::factory()
                ->count($this->faker->numberBetween(1, 5))
                ->create(['order_id' => $order->id]);

            // Recalculate total amount based on order details
            $order->update([
                'total_amount' => $order->details->sum('subtotal'),
                'grand_total' => $order->details->sum('subtotal') + $order->shipping_cost,
            ]);

            // Create payment transaction for non-cart orders
            if ($order->status !== 'in_cart') {
                \App\Models\PaymentTransaction::factory()
                    ->create(['order_id' => $order->id]);
            }

            // Ensure status consistency
            if ($order->status === 'in_cart') {
                $order->update([
                    'payment_status' => 'unpaid',
                    'shipping_status' => null,
                    'shipped_at' => null,
                    'arrived_at' => null
                ]);
            } elseif ($order->status === 'success') {
                $order->update([
                    'payment_status' => 'paid',
                    'shipping_status' => 'delivered',
                    'arrived_at' => $this->faker->dateTimeBetween('-1 week', 'now')
                ]);
            } elseif ($order->status === 'cancelled') {
                $order->update([
                    'payment_status' => $this->faker->randomElement(['unpaid', 'refunded']),
                    'shipping_status' => null
                ]);
            }
        });
    }
}
