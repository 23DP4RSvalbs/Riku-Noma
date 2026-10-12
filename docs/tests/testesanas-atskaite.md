# Rīku noma: testēšanas atskaite

**Datums:** 2026-10-12
**Testētāji:** Rolands — vides pārbaude un dokumentācija, Marija — backend/API/PHPUnit, Ksenija — frontend/UI

## 1. Kopsavilkums

| Rādītājs | Faktiskais rezultāts | Statuss |
| --- | --- | --- |
| Testa plāna pamatgadījumi | 19 no 19 izpildīti (9 funkcionālie, 6 robežvērtību, 4 kļūdu) | Izgāja |
| UI pārbaudes | 17 manuālas Chromium pārbaudes ar 17 unikāliem, klikšķināmiem ekrānuzņēmumiem | Izgāja |
| Datumu robežvērtības | BV-05 un BV-06 pārbaudītas API/PHPUnit līmenī | Izgāja |
| Backend PHPUnit | 25 testi, 107 apgalvojumi | Izgāja |
| Frontend kvalitātes pārbaudes | ESLint koda kvalitātes pārbaude un Vite produkcijas būve | Izgāja |
| Reģistrētās kļūdas | 11 kļūdas; visas novērstas un atkārtoti pārbaudītas | Novērstas |
| Testa datubāze | Izolēta SQLite datne; `migrate:fresh --seed` izgāja | Izgāja |

## 2. Vide un izpilde

Izmantots PHP 8.3.33 (paplašinājumi `gd` un `pdo_mysql`), Composer 2.10.3, Node.js 22.21.0, Backend darbojās uz `http://127.0.0.1:8013`, frontend uz `http://127.0.0.1:5173`. Backend testi izpildīti pret testa datni `backend/database/testing.sqlite`, izstrādātāju lokālā datubāze netika skarta. Testa cikla datubāze ir SQLite, sistēmas mērķa datubāze izstrādē ir MySQL 8.

## 3. Kļūdu reģistrs

Testēšanā reģistrētas 11 kļūdas (TEST2-001–006, TEST3-001–004, TEST4-INT-001). Katrai ir dokumentēts sākotnējais atradums, cēlonis, novēršana un retests [kļūdu reģistrā](./kludu-registrs.md). Pēc visu labojumu integrēšanas gala backend izpilde deva 25 testus un 107 apgalvojumus.

## 4. Secinājumi

Visi plānotie testa gadījumi ir izpildīti. Detalizētie faktiskie rezultāti un klikšķināmi ekrānuzņēmumi ir [testu rezultātos](./testu-rezultati.md). API validāciju pārbaudīja PHPUnit, bet lietotāja saskarni — manuālas Chromium pārbaudes. Atrastās 11 kļūdas ir novērstas un atkārtoti pārbaudītas.

Sistēma atbilst prasību dokumentā definētajām funkcijām visām trim lomām: viesis var pārlūkot katalogu, lietotājs var rezervēt rīkus un pārvaldīt savus pasūtījumus, bet administrators var pārvaldīt inventāru un pasūtījumus. Validācijas robežas (paroles garums un sastāvs, cenas un daudzuma robežas, datumu secība un pagātnes datumi) darbojas kā paredzēts, un kļūdu gadījumā lietotājs saņem latvisku, saprotamu paziņojumu. Kā uzlabojamu virzienu turpmāk var minēt automatizēto UI testu iekļaušanu katra iesūtījuma pārbaudē.
