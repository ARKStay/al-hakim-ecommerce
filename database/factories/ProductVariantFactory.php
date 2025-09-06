<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $colors = ['Hitam', 'Biru', 'Kuning', 'Merah', 'Hijau'];
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];

        return [
            'product_id' => Product::factory(), // bikin produk dummy juga
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'price' => $this->faker->numberBetween(10000, 200000),
            'stock' => $this->faker->numberBetween(0, 50),
            'variant_image' => 'https://via.placeholder.com/150', // dummy image
            'weight' => $this->faker->randomFloat(2, 0.1, 2), // 0.1kg - 2kg
        ];
    }
}
