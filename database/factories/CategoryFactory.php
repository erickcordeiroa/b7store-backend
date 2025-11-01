<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Entities\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(1, true);

        return [
            'name' => ucfirst($name),
            'slug' => fake()->unique()->slug(),
        ];
    }
}

