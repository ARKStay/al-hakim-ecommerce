<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id'); // Foreign key ke orders (boleh pakai FK)
            $table->unsignedBigInteger('product_id'); // Snapshot ID (tanpa FK)
            $table->unsignedBigInteger('product_variant_id'); // Snapshot ID (tanpa FK)

            // Snapshot data produk
            $table->string('product_name');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('variant_image')->nullable();

            // Berat satuan (dalam kg, format desimal)
            $table->decimal('weight', 8, 2)->default(0);

            // Harga per item & jumlah
            $table->integer('price');
            $table->integer('quantity');

            $table->timestamps();

            // Optional foreign key (kalau lo tetap pengen FK ke orders)
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
