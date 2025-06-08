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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('provider')->default('midtrans'); // Untuk kemungkinan multiple payment providers
            $table->string('transaction_id');
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('va_number')->nullable();
            $table->string('bank')->nullable();
            $table->dateTime('expiry_time')->nullable();
            $table->text('payment_code')->nullable(); // For Indomaret/Alfamart
            $table->text('qr_code')->nullable(); // For QRIS/GoPay
            $table->text('pdf_url')->nullable(); // For payment instructions
            $table->text('snap_token')->nullable();
            $table->text('snap_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
