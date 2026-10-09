# Testēšanas plāns

## Mērķis

Pārbaudīt, vai rīku nomas sistēma strādā viesim, klientam un administratoram.

## Ko pārbaudām

- Reģistrāciju un pieslēgšanos.
- Katalogu un meklēšanu.
- Kategoriju filtrus.
- Rīka detaļas un pieejamību.
- Rezervācijas un atcelšanu.
- Klienta profilu.
- Administratora paneli.
- Datu pārbaudes un kļūdu ziņojumus.
- Datu saglabāšanu datubāzē.

## Testēšanas veidi

- Funkcionālie testi.
- Robežvērtību testi.
- Kļūdu testi.
- Atkārtoti testi pēc labojumiem.
- Manuāla UI pārbaude pārlūkā.

## Testa dati

Administrators: `admin@riki-noma.lv` / `admin123`

Klients: `marija@test.lv` / `test123`

## Testa datubāze

Testiem izmantota MySQL `8.4.3` datubāze `riki_noma_test`. Papildu pārbaudei izmantota atsevišķa SQLite testa datne.

## Testa gadījumi

| ID | Ko dara | Ko sagaida | Rezultāts | Testeris |
|---|---|---|---|---|
| FT-01 | Reģistrē jaunu klientu. | Konts tiek izveidots un saņem tokenu. | Izgāja ar PHPUnit | Marija (PHPUnit) |
| FT-02 | Pieslēdzas administrators. | Saņem tokenu un lomu Administrators. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| FT-03 | Meklē rīku katalogā. | Redz atbilstošu rīku. | Izgāja ar PHPUnit un API. | Marija (API pieprasījumi) |
| FT-04 | Izvēlas kategoriju. | Redz tikai šīs kategorijas rīkus. | Izgāja ar PHPUnit un API. | Marija (API pieprasījumi) |
| FT-05 | Izveido rezervāciju. | Rezervācija tiek saglabāta. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| FT-06 | Atceļ savu rezervāciju. | Rezervācija kļūst par Atcelts. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| FT-07 | Administrators pievieno un maina rīku. | Izmaiņas tiek saglabātas. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| FT-08 | Administrators maina pasūtījuma statusu. | Jaunais statuss tiek saglabāts. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| FT-09 | Apskata rīka detaļas un pieejamību. | Redz pareizu informāciju. | Izgāja ar PHPUnit un API. | Marija (API pieprasījumi) |
| BV-01 | Izmēģina 7 un 8 rakstzīmju paroli. | 7 noraida, 8 pieņem. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| BV-02 | Izmēģina paroli bez burtiem vai cipariem. | Paroli noraida. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| BV-03 | Ievada cenu 0 un negatīvu cenu. | 0 pieņem, negatīvu noraida. | API un UI izgāja. UI rādīja `Dienas cenai jābūt vismaz 0.` | Marija (PHPUnit), Ksenija (UI) |
| BV-04 | Ievada daudzumu 0 un negatīvu daudzumu. | 0 pieņem, negatīvu noraida. | API un UI izgāja. UI rādīja `Daudzumam jābūt vismaz 0.` | Marija (PHPUnit), Ksenija (UI) |
| BV-05 | Ievada vakardienas datumu. | Rezervāciju noraida. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| BV-06 | Ievada vienādus un apgrieztus datumus. | Vienādus pieņem, apgrieztus noraida. | Izgāja ar PHPUnit un API. | Marija (PHPUnit) |
| ER-01 | Reģistrē jau izmantotu e-pastu. | Otro kontu neizveido. | API un UI izgāja. UI teksts: `Šis e-pasts jau ir reģistrēts.` | Marija (PHPUnit), Ksenija (UI) |
| ER-02 | Klients atver admina daļu. | Piekļuvi noraida. | Izgāja ar PHPUnit un API. | Marija (API pieprasījumi) |
| ER-03 | Mēģina rezervēt aizņemtu rīku. | Rezervāciju noraida. | API un UI izgāja. UI rādīja `Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.` | Marija (PHPUnit), Ksenija (UI) |
| ER-04 | Neielogots lietotājs atver aizsargātu daļu. | Saņem 401 vai login iespēju. | Izgāja ar PHPUnit un API. | Marija (API pieprasījumi) |

## Pierādījumi

19 testa gadījumu rezultāti ir [testu rezultātos](./testu-rezultati.md). Četru UI scenāriju teksti un ekrānuzņēmumi ir [ui-results.md](./screen/ui-results.md).
