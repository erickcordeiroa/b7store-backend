<?php

namespace Database\Seeders;

use App\Domain\Entities\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::first();
        $product->images()->createMany([
            ['uri' => 'products/camisa-laravel-azul.jpeg'],
            ['uri' => 'products/camisa-laravel-azul-2.jpeg'],
        ]);
    }
}
