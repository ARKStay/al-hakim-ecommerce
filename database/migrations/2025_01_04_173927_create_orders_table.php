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

            // Relasi user dan cart
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // Midtrans
            $table->string('midtrans_order_id')->unique();

            // Info transaksi
            $table->integer('total_price');
            $table->string('shipping_method');
            $table->integer('shipping_cost')->default(0);

            // Status
            $table->string('payment_status')->default('paid'); // contoh: pending, paid, failed, expired
            $table->string('order_status')->default('pending');   // contoh: pending, processed, shipped, completed, cancelled

            // Snap info tambahan
            $table->string('payment_type')->nullable(); // credit_card, bank_transfer, dll
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();

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
