<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Riks;
use App\Models\Kategorija;

class RiksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Sasaistām kategorijas nosaukumu ar tās ID
        $kat = Kategorija::pluck('kategorijaID', 'nosaukums');

        // [kategorija, nosaukums, apraksts, cena, daudzums, min, max, foto, kods, zimols]
        $riki = [
            ['Elektroinstrumenti',   'Bosch GSB 18V urbis',
             'Akumulatora urbis ar 2 baterijām un lādētāju',       12.50, 3, 1, 7,
             'riki/bosch-gsb-18v.jpg',      'EL-001', 'Bosch'],

            ['Elektroinstrumenti',   'Makita 5008MG ripzāģis',
             'Diska zāģis 1800W ar 210mm disku',                   15.00, 2, 1, 5,
             'riki/makita-5008mg.jpg',      'EL-002', 'Makita'],

            ['Elektroinstrumenti',   'DeWalt DWE4257 slīpmašīna',
             'Leņķa slīpmašīna 125mm, 900W',                       10.00, 4, 1, 3,
             'riki/dewalt-dwe4257.jpg',     'EL-003', 'DeWalt'],

            ['Dārza instrumenti',    'Husqvarna 130 zāles pļāvējs',
             'Benzīna zāles pļāvējs, 38.2cc',                      18.00, 2, 1, 3,
             'riki/husqvarna-130.jpg',      'DA-001', 'Husqvarna'],

            ['Dārza instrumenti',    'Stihl FS 55 trimmeris',
             'Benzīna trimmeris ar auklas galvu',                  14.00, 3, 1, 3,
             'riki/stihl-fs55.jpg',         'DA-002', 'Stihl'],

            ['Būvniecības tehnika',  'Betona maisītājs 140L',
             'Elektriskais betona maisītājs, 140 litri',           20.00, 2, 1, 7,
             'riki/betona-maisitajs-140l.jpg', 'BT-001', 'Altrad'],

            ['Būvniecības tehnika',  'Hilti TE 30 perforators',
             'SDS-Plus perforators ar 1050W motoru',               22.00, 1, 1, 7,
             'riki/hilti-te30.jpg',         'BT-002', 'Hilti'],

            ['Mērinstrumenti',       'Bosch GLL 3-80 lāzera līmenis',
             'Zaļais lāzera līmenis, 3 plaknes, 80m',              16.00, 2, 1, 14,
             'riki/bosch-gll380.jpg',       'ME-001', 'Bosch'],

            ['Mērinstrumenti',       'Leica DISTO D2 tālmērs',
             'Lāzera attāluma mērītājs līdz 60m',                   8.00, 3, 1, 14,
             'riki/leica-disto-d2.jpg',     'ME-002', 'Leica'],

            ['Tīrīšanas aprīkojums', 'Kärcher K5 spiediena mazgātājs',
             'Augstspiediena mazgātājs, 145 bar',                  17.00, 2, 1, 3,
             'riki/karcher-k5.jpg',         'TI-001', 'Kärcher'],

            ['Tīrīšanas aprīkojums', 'Nilfisk putekļsūcējs',
             'Rūpnieciskais putekļsūcējs, 30L tvertne',             9.00, 3, 1, 3,
             'riki/nilfisk-multi.jpg',      'TI-002', 'Nilfisk'],

            ['Kompaktdarbnīca',      'Creality Ender 3 3D printeris',
             'FDM 3D printeris, 220x220x250mm drukas lauks',       25.00, 1, 1, 7,
             'riki/creality-ender3.jpg',    'KD-001', 'Creality'],

            ['Kompaktdarbnīca',      'Weller WE1010 lodāmurs',
             'Lodāmurs ar temperatūras kontroli 70W',               7.00, 4, 1, 7,
             'riki/weller-we1010.jpg',      'KD-002', 'Weller'],
        ];

        foreach ($riki as [
            $kategorija, $nosaukums, $apraksts, $cena, $daudzums,
            $min, $max, $foto, $kods, $zimols
        ]) {
            Riks::create([
                'kategorijaID'    => $kat[$kategorija],
                'nosaukums'       => $nosaukums,
                'apraksts'        => $apraksts,
                'cenadiena'       => $cena,
                'daudzums'        => $daudzums,
                'nomasilgumsmin'  => $min,
                'nomasilgumsmax'  => $max,
                'foto'            => $foto,
                'kods'            => $kods,
                'zimols'          => $zimols,
                'redzamsKatalogs' => true,
            ]);
        }
    }
}
