# Rīku noma: testēšanas atskaite

**Datums:** 2026-10-08
**Testētāji:** Rolands — vides pārbaude un dokumentācija; Marija — backend/API/PHPUnit; Ksenija — frontend/UI/Playwright.

## 1. Kopsavilkums

| Rādītājs | Faktiskais rezultāts | Statuss |
| --- | --- | --- |
| Testa plāna pamatgadījumi | 19 no 19 izpildīti (9 funkcionālie, 6 robežvērtību, 4 kļūdu) | Izgāja |
| UI pārbaudes | 19 no 19 izpildītas ar Chromium, 19 ekrānuzņēmumi | Izgāja |
| Backend PHPUnit | 25 testi, 107 assertions | Izgāja |
| Frontend kvalitātes pārbaudes | `npm run lint` un `npm run build` | Izgāja |
| Reģistrētās kļūdas | 11 kļūdas; visas novērstas un atkārtoti pārbaudītas | Novērstas |
| Testa datubāze | Izolēta SQLite datne; `migrate:fresh --seed` izgāja | Izgāja |

## 2. Vide un izpilde

Izmantots PHP 8.3.33 (paplašinājumi `gd` un `pdo_mysql`), Composer 2.10.3, Node.js 22.21.0, Backend darbojās uz `http://127.0.0.1:8013`, frontend uz `http://127.0.0.1:5173`. Backend testi izpildīti pret izolētu SQLite testa datni `backend/database/testing.sqlite`; izstrādātāju lokālā datubāze netika skarta. Testa cikla datubāze ir SQLite; sistēmas mērķa datubāze izstrādē ir MySQL 8.

## 3. Kļūdu reģistrs

Testēšanā reģistrētas 11 kļūdas (TEST2-001–006, TEST3-001–004, TEST4-INT-001). Katrai ir dokumentēts sākotnējais atradums, cēlonis, novēršana ar commit atsauci un atkārtotās testēšanas rezultāts. Pēc visu labojumu integrēšanas pilnā backend kopa izgāja: 24 testi, 101 assertions; gala izpildē — 25 testi, 107 assertions. Pilns reģistrs: `docs/tests/kludu-registrs.md`.

## 4. Secinājumi

Visi plānotie testa gadījumi ir izpildīti un rezultāti dokumentēti ar pierādījumiem: 19 pamatgadījumi (funkcionālā, robežvērtību un kļūdu testēšana API līmenī ar PHPUnit un tiešiem pieprasījumiem), 19 lietotāja saskarnes pārbaudes ar Playwright un ekrānuzņēmumiem, kā arī frontend lintera un produkcijas būvējuma pārbaudes. Testēšanā atrastās 11 kļūdas visas ir novērstas un atkārtoti pārbaudītas; kļūdu cēloņi un labojumi ir versijoti commit vēsturē.

Sistēma atbilst prasību dokumentā definētajām funkcijām visām trim lomām: viesis var pārlūkot katalogu, lietotājs — rezervēt un pārvaldīt savus pasūtījumus, administrators — pārvaldīt inventāru un pasūtījumus. Validācijas robežas (paroles garums un sastāvs, cenas un daudzuma robežas, datumu secība un pagātnes datumi) darbojas kā paredzēts, un kļūdu gadījumā lietotājs saņem latvisku, saprotamu paziņojumu. Kā uzlabojamu virzienu izstrādē turpmāk varētu minēt automatizēto UI testu iekļaušanu katra iesūtījuma pārbaudē (CI).

