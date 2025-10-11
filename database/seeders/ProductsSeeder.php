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
            "label" => 'Produto de Exemplo',
            "description" => "Descrição do produto de exemplo",
            "price" => 39.99,
            "category_id" => 1
        ]);

        Product::create([
            "label" => 'Produto de Exemplo 1',
            "description" => "Descrição do produto de exemplo 1",
            "price" => 19.99,
            "category_id" => 1
        ]);
    }
}
