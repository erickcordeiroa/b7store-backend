<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            "label" => 'Camisa Laravel - Azul',
            "description" => "Descrição do produto de exemplo",
            "price" => 39.99,
            "views_count" => 0,
            "sales_count" => 0,
            "category_id" => 1
        ]);

        Product::create([
            "label" => 'Camisa Laravel - Preto',
            "description" => "Descrição do produto de exemplo 1",
            "price" => 19.99,
            "views_count" => 20,
            "sales_count" => 10,
            "category_id" => 1
        ]);
    }
}
