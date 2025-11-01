<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Entities\Category;
use App\Domain\Entities\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $label = fake()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'label' => ucwords($label),
            'description' => fake()->paragraph(),
            'slug' => fake()->unique()->slug(),
            'price' => fake()->randomFloat(2, 10, 500),
            'sales_count' => fake()->numberBetween(0, 1000),
            'views_count' => fake()->numberBetween(0, 5000),
        ];
    }
}

