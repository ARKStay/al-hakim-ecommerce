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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('color');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->unsignedInteger('stock');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('sizes_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedBigInteger('total_ratings')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
