# Testēšanas atskaite

Datums: 2026-10-05

## Īss kopsavilkums

| Daļa | Rezultāts |
|---|---|
| Testa plāns | Ir sagatavots ar funkcionālajiem, robežvērtību un kļūdu gadījumiem. |
| Backend | 25 PHPUnit testi izgāja. |
| FT-02 | Admina pieslēgšanās izgāja. |
| UI | Manuāli vēl nav pārbaudīts. |
| MySQL 8 | Nav izdevies uzstādīt. |
| Kļūdas | Iepriekš atrastās kļūdas ir aprakstītas un atkārtoti pārbaudītas backend testos. |

## Kas tika izdarīts

Backend tika palaists ar atsevišķu SQLite testa datni. Tika palaistas migrācijas un sēklas dati.

Pārbaudījām reģistrāciju, pieslēgšanos, lomas, katalogu, rezervācijas, pieejamību un admina darbības.

Frontend lint un build izgāja.

## Kas vēl jāizdara

UI vēl jāiziet manuāli pārlūkā. Jāpārbauda visi testa plāna gadījumi un jāsaglabā tikai īsti manuāli uzņemti attēli.

Jāuzstāda MySQL 8 un jāpalaiž testi pret MySQL datubāzi.

## Secinājums

Backend daļa ir pārbaudīta un strādā.

Pilnu 100 punktu testa rezultātu vēl nevar apgalvot, jo manuālie UI testi un MySQL tests nav pabeigti. Dokumentos apzināti nav izdomātu rezultātu.

## Pierādījumi

Pilnie backend rezultāti ir [testu rezultātos](./testu-rezultati.md).

Testa soļi ir [testa plānā](./testa-plans.md).

Kļūdas ir [kļūdu reģistrā](./kludu-registrs.md).
