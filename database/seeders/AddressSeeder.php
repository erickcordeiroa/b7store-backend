<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::first()->addresses()->createMany([
            [
                'zipcode' => '12345-678',
                'street' => 'Rua Exemplo',
                'number' => '100',
                'complement' => 'Apto 101',
                'city' => 'Registro',
                'state' => 'SP',
                'country' => 'Brasil',
            ],
            [
                'zipcode' => '98765-432',
                'street' => 'Avenida Teste',
                'number' => '200',
                'complement' => null,
                'city' => 'Registro',
                'state' => 'SP',
                'country' => 'Brasil',
            ],
        ]);
    }
}
