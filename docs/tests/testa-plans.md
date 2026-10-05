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

## Testa gadījumi

| ID | Ko dara | Ko sagaida | Rezultāts |
|---|---|---|---|
| FT-01 | Reģistrē jaunu klientu. | Konts tiek izveidots un saņem tokenu. | Izgāja ar PHPUnit |
| FT-02 | Pieslēdzas administrators. | Saņem tokenu un lomu Administrators. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| FT-03 | Meklē rīku katalogā. | Redz atbilstošu rīku. | API izgāja. UI nav pārbaudīts. |
| FT-04 | Izvēlas kategoriju. | Redz tikai šīs kategorijas rīkus. | API izgāja. UI nav pārbaudīts. |
| FT-05 | Izveido rezervāciju. | Rezervācija tiek saglabāta. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| FT-06 | Atceļ savu rezervāciju. | Rezervācija kļūst par Atcelts. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| FT-07 | Administrators pievieno un maina rīku. | Izmaiņas tiek saglabātas. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| FT-08 | Administrators maina pasūtījuma statusu. | Jaunais statuss tiek saglabāts. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| FT-09 | Apskata rīka detaļas un pieejamību. | Redz pareizu informāciju. | API izgāja. UI nav pārbaudīts. |
| BV-01 | Izmēģina 7 un 8 rakstzīmju paroli. | 7 noraida, 8 pieņem. | API izgāja. UI nav pārbaudīts. |
| BV-02 | Izmēģina paroli bez burtiem vai cipariem. | Paroli noraida. | API izgāja. UI nav pārbaudīts. |
| BV-03 | Ievada cenu 0 un negatīvu cenu. | 0 pieņem, negatīvu noraida. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| BV-04 | Ievada daudzumu 0 un negatīvu daudzumu. | 0 pieņem, negatīvu noraida. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| BV-05 | Ievada vakardienas datumu. | Rezervāciju noraida. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| BV-06 | Ievada vienādus un apgrieztus datumus. | Vienādus pieņem, apgrieztus noraida. | Izgāja ar PHPUnit. UI nav pārbaudīts. |
| ER-01 | Reģistrē jau izmantotu e-pastu. | Otro kontu neizveido. | API izgāja. UI nav pārbaudīts. |
| ER-02 | Klients atver admina daļu. | Piekļuvi noraida. | API izgāja. UI nav pārbaudīts. |
| ER-03 | Mēģina rezervēt aizņemtu rīku. | Rezervāciju noraida. | API izgāja. UI nav pārbaudīts. |
| ER-04 | Neielogots lietotājs atver aizsargātu daļu. | Saņem 401 vai login iespēju. | API izgāja. UI nav pārbaudīts. |

## Kas vēl jāizdara manuāli

1. Palaist backend un frontend.
2. Atvērt katru lapu pārlūkā.
3. Izpildīt FT, BV un ER soļus.
4. Pierakstīt redzamo ziņojumu.
5. Saglabāt tikai īstus manuālus screenshotus.
6. Atkārtot testu, ja tiek atrasta kļūda.
