<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['in_cart', 'pending', 'in_shipping', 'delivered', 'success', 'cancelled'])->default('in_cart');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');

            // Shipping info sebagai string
            $table->string('payment_method')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();

            // Courier info sebagai string
            $table->string('courier')->nullable();
            $table->string('courier_service')->nullable();
            $table->string('tracking_number')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('arrived_at')->nullable();
            $table->enum('shipping_status', ['preparing', 'shipped', 'in_transit', 'delivered'])->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->string('payment_proof')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
