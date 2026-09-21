<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lietotajs; 
use App\Models\Loma;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LietotajsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Atrodam lomu ID pēc nosaukuma
        $adm = Loma::where('nosaukums', 'Administrators')->first();
        $kli = Loma::where('nosaukums', 'Klients')->first();

        // ---------- 1 administrators ----------
        $admin = Lietotajs::create([
            'vards'    => 'Rolands Admin',
            'epasts'   => 'admin@riki-noma.lv',
            'parole'   => Hash::make('admin123'),
            'telefons' => '+37120000001',
        ]);
        $admin->lomas()->attach($adm->lomasID);

        // Ieraksts tabulā admin
        Admin::create([
            'lietotajID' => $admin->lietotajsID,
            'aktivs'     => 1,
        ]);

        // ---------- 2 testa lietotāji ----------
        $testi = [
            ['Marija Teste',  'marija@test.lv',  '+37120000002'],
            ['Ksenija Teste', 'ksenija@test.lv', '+37120000003'],
        ];

        foreach ($testi as [$vards, $epasts, $telefons]) {
            $u = Lietotajs::create([
                'vards'    => $vards,
                'epasts'   => $epasts,
                'parole'   => Hash::make('test123'),
                'telefons' => $telefons,
            ]);
            $u->lomas()->attach($kli->lomasID);
        }
    }
}
