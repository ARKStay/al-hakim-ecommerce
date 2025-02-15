<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sizes>
 */
class SizesFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->sentence(rand(1, 2), false),
            'slug' => Str::slug(fake()->sentence(rand(1, 2), false))
        ];
    }
}
