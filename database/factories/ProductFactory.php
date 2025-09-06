<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'image' => 'https://via.placeholder.com/400x400',
            'average_rating' => $this->faker->randomFloat(2, 0, 5),
            'total_ratings' => $this->faker->numberBetween(0, 1000),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $colors = ['Hitam', 'Biru'];
            $sizes = ['M', 'L'];

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $size,
                        'price' => fake()->numberBetween(50000, 200000),
                        'stock' => fake()->numberBetween(1, 50),
                        'variant_image' => 'https://via.placeholder.com/400x400',
                        'weight' => fake()->randomFloat(2, 0.1, 1.5),
                    ]);
                }
            }
        });
    }
}
