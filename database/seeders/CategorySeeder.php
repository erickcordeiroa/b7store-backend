<?php

namespace Database\Seeders;

use App\Domain\Entities\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            "name" => "Camisetas",
            "slug" => "camisetas"
        ]);

        Category::create([
            "name" => "Eletrônicos",
            "slug" => "eletronicos"
        ]);
    }
}
