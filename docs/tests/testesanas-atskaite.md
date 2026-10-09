# Testēšanas atskaite

Datums: 2026-10-09

## 1. Kopsavilkums

| Rādītājs | Faktiskais rezultāts | Statuss |
|---|---|---|
| Testa plāns | 19 funkcionālie, robežvērtību un kļūdu gadījumi | Izgāja |
| Funkcionālie testi | FT-01 līdz FT-09 izgāja | Izgāja |
| Robežvērtību testi | BV-01 līdz BV-06 izgāja | Izgāja |
| Kļūdu testi | ER-01 līdz ER-04 izgāja | Izgāja |
| UI pārbaudes | 19 no 19 UI scenārijiem izgāja | Izgāja |
| PHPUnit ar SQLite | 25 testi, 107 pārbaudes | Izgāja |
| PHPUnit ar MySQL 8.4.3 | 25 testi, 107 pārbaudes | Izgāja |
| Frontend pārbaudes | Lint un build izgāja | Izgāja |

## 2. Testa vide

PHP `8.3.33`, Node.js `22.21.0`, MySQL `8.4.3` un PHP paplašinājumi `gd`, `pdo_mysql`, `pdo_sqlite`.

Testa datubāze bija `riki_noma_test`.

Izmantotie konti bija admina konts `admin@riki-noma.lv` / `admin123` un klienta konts `marija@test.lv` / `test123`.

## 3. Testēšanas rezultāti

Visi 19 plānotie testa gadījumi izgāja API vai PHPUnit līmenī. Visi 19 UI scenāriji tika izpildīti pārlūkā, un katram ir rezultāts, testētājs, datums un screenshot saite.

Pilns sadalījums ir [testu rezultātos](./testu-rezultati.md). UI teksts un ekrānuzņēmumi ir [ui-results.md](./screen/ui-results.md).

## 4. Kļūdas

Iepriekš atrastās kļūdas ir aprakstītas [kļūdu reģistrā](./kludu-registrs.md). Tām tika veikti atkārtoti testi.

BV-03 gala teksts ir `Dienas cenai jābūt vismaz 0.`. BV-04 gala teksts ir `Daudzumam jābūt vismaz 0.`. ER-03 gala teksts ir `Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.`.

## 5. Secinājumi

Testēšanas plāns ir izpildīts. Funkcionālie, robežvērtību un kļūdu testi izgāja. PHPUnit tests izgāja gan ar SQLite, gan ar MySQL 8.4.3. Visi 19 UI scenāriji izgāja ar konkrētiem redzamiem rezultātiem. Rezultāti un ekrānuzņēmumi ir pievienoti dokumentācijai.

## 6. Pierādījumi

- [Testa plāns](./testa-plans.md)
- [Testu rezultāti](./testu-rezultati.md)
- [UI rezultāti](./screen/ui-results.md)
- [Kļūdu reģistrs](./kludu-registrs.md)
