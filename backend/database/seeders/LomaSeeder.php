<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Loma;
use Illuminate\Database\Seeder;

class LomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Loma::create(['nosaukums' => 'Klients']);
        Loma::create(['nosaukums' => 'Administrators']);
    }
}
