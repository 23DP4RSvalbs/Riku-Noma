<?php

namespace Database\Seeders;

use App\Models\Lietotajs;
use App\Models\Pasutijums;
use App\Models\RikAtsauksme;
use App\Models\Riks;
use Illuminate\Database\Seeder;

class PasutijumsSeeder extends Seeder
{
    public function run(): void
    {
        $marija = Lietotajs::where('epasts', 'marija@test.lv')->firstOrFail();
        $ksenija = Lietotajs::where('epasts', 'ksenija@test.lv')->firstOrFail();
        $riki = Riks::pluck('rikID', 'kods');

        // [lietotājs, statuss, rīka kods, daudzums, sākums (dienas no šodienas), ilgums dienās]
        $pasutijumi = [
            [$marija, 'Izpildits', 'EL-001', 1, -30, 3],
            [$marija, 'Izpildits', 'DA-001', 1, -20, 2],
            [$ksenija, 'Izpildits', 'EL-002', 1, -15, 2],
            [$marija, 'Apstiprinats', 'BT-002', 1, 2, 3],
            [$ksenija, 'Apstiprinats', 'ME-001', 2, 5, 4],
            [$marija, 'Jauns', 'KD-001', 1, 7, 5],
            [$ksenija, 'Jauns', 'TI-001', 1, 9, 2],
            [$ksenija, 'Atcelts', 'DA-002', 1, 4, 2],
        ];

        foreach ($pasutijumi as [$lietotajs, $statuss, $kods, $daudzums, $nobide, $ilgums]) {
            $rik = Riks::findOrFail($riki[$kods]);
            $sakums = now()->addDays($nobide)->startOfDay();
            $beigas = $sakums->copy()->addDays($ilgums - 1);

            $pasutijums = Pasutijums::create([
                'lietotajID' => $lietotajs->lietotajsID,
                'statuss' => $statuss,
                'kopsumma' => $rik->cenadiena * $daudzums * $ilgums,
                'izveidesdatums' => $sakums->copy()->subDays(3),
                'noteikumu_versija' => '1.0',
                'noteikumi_apstiprinati_at' => $sakums->copy()->subDays(3),
            ]);

            $pasutijums->riki()->attach($rik->getKey(), [
                'daudzums_pozicija' => $daudzums,
                'nomassakums' => $sakums->toDateString(),
                'nomasbeigums' => $beigas->toDateString(),
            ]);
        }

        $atsauksmes = [
            [$marija, 'EL-001', 5, 'Ļoti labs urbis, baterijas pietika visai dienai.'],
            [$marija, 'DA-001', 4, 'Pļāvējs strādāja bez problēmām, nedaudz skaļš.'],
            [$ksenija, 'EL-002', 5, 'Zāģis ass un viegli lietojams. Iesaku.'],
        ];

        foreach ($atsauksmes as [$lietotajs, $kods, $vertejums, $teksts]) {
            RikAtsauksme::create([
                'rikID' => $riki[$kods],
                'lietotajID' => $lietotajs->lietotajsID,
                'vertejums' => $vertejums,
                'teksts' => $teksts,
            ]);
        }
    }
}
