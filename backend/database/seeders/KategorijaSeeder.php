<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategorija;  

class KategorijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            ['nosaukums' => 'Elektroinstrumenti',   'apraksts' => 'Urbji, zāģi, slīpmašīnas'],
            ['nosaukums' => 'Dārza instrumenti',    'apraksts' => 'Pļāvēji, trimmeri, zaru zāģi'],
            ['nosaukums' => 'Būvniecības tehnika',  'apraksts' => 'Betona maisītāji, perforatori'],
            ['nosaukums' => 'Mērinstrumenti',       'apraksts' => 'Lāzera līmeņi, tālmēri'],
            ['nosaukums' => 'Tīrīšanas aprīkojums', 'apraksts' => 'Putekļsūcēji, mazgāšanas iekārtas'],
            ['nosaukums' => 'Kompaktdarbnīca',      'apraksts' => '3D printeri, lodāmuri'],
        ];

        foreach ($data as $row) {
            Kategorija::create($row);
        }
    }
}
