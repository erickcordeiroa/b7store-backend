<?php

namespace Database\Seeders;

use App\Domain\Entities\Category;
use App\Domain\Entities\Product;
use Illuminate\Database\Seeder;

class CategoryMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryTec = Category::first()->metadata()->create([
                'id' => 'tecnologia',
                'name' => 'Tecnologia',
            ]);

        $categoryCor = Category::first()->metadata()->create([
                'id' => 'cor',
                'name' => 'Cor',
        ]);

        $categoryTec->values()->createMany([
            [
                'id' => 'laravel',
                'label' => 'Laravel',
            ],
            [
                'id' => 'react-native',
                'label' => 'React Native',
            ],
        ]);

        $categoryCor->values()->createMany([
            [
                'id' => 'preto',
                'label' => 'Preto',
            ],
            [
                'id' => 'branco',
                'label' => 'Branco',
            ],
            [
                'id' => 'azul',
                'label' => 'Azul',
            ],
        ]);

        Product::first()->metadata()->create([
            'category_metadata_id' => 'tecnologia',
            'metadata_value_id' => 'laravel',
        ]);

         Product::first()->metadata()->create([
            'category_metadata_id' => 'cor',
            'metadata_value_id' => 'azul',
        ]);
    }
}
