<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Relasi ke produk
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Info varian
            $table->string('color');
            $table->string('size');
            $table->integer('price');
            $table->unsignedInteger('stock');
            $table->string('variant_image')->nullable();
            $table->decimal('weight', 8, 2)->default(0); // dalam KG

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
