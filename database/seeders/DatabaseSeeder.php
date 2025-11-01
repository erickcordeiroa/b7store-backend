<?php

namespace Database\Seeders;

use App\Domain\Entities\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BannersSeeder::class,
            CategorySeeder::class,
            ProductsSeeder::class,
            ProductImageSeeder::class,
            CategoryMetadataSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
        ]);
    }
}
