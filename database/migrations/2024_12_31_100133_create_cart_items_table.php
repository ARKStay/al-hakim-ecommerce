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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');

            // Snapshot data
            $table->string('product_name'); // dari product->name
            $table->string('color');
            $table->string('size');
            $table->decimal('weight', 8, 2)->default(0); // dari variant

            $table->integer('price'); // harga per unit
            $table->integer('quantity'); // jumlah yang dibeli

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
