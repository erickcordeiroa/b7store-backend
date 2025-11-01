<?php

namespace Database\Seeders;

use App\Domain\Entities\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            "uri" => "banners/banner_promo_1.jpg",
            "link" => "https://google.com.br"
        ]);
    }
}
