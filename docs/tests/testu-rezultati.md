# Rīku noma: testu rezultāti

**Izpildes datums:** 2026-10-05
**Zars:** `test/final-ui-mysql` no `origin/main` (`a755253`)
**Vide:** PHP 8.3.33, Composer 2.10.3, Node.js 22.21.0, npm 10.9.4, Playwright 1.63.0, Chromium 153.0.8010.12.

## Kopsavilkums

| Pārbaude | Faktiskais rezultāts | Statuss | Testeris |
|---|---|---|---|
| Backend PHPUnit | 25 testi, 107 assertions, visi izgāja | Izgāja | Marija (PHPUnit) |
| FT-02 administrators | Sēklu konts pieslēdzās, saņēma tokenu un lomu `Administrators`; 1 tests, 6 assertions | Izgāja | Marija (PHPUnit) |
| UI Playwright | 19 scenāriji, 19 screenshot faili, rezultāti [ui-results.md](./screen/ui-results.md) | Izgāja | Ksenija (Playwright) |
| Frontend lint | `npm.cmd run lint` atgrieza izejas kodu 0 | Izgāja | Ksenija (Playwright) |
| Frontend build | `npm.cmd run build` izveidoja Vite produkcijas būvi | Izgāja | Ksenija (Playwright) |
| MySQL 8 | MySQL 8.4.9 instalēšana beidzās ar installer exit code 1602; serveris un `mysql` klients nav pieejams | Bloķēts | Rolands (vides pārbaude) |

## Backend un FT-02

Testa datubāze bija atsevišķa `backend/database/testing.sqlite`; pirms migrācijas pārbaudīts, ka `.env.testing` izmanto `DB_CONNECTION=sqlite`, `APP_ENV=testing` un pilnu testa datnes ceļu. Izpildīts `migrate:fresh --seed --env=testing`; projekta lokālā dev datubāze netika atiestatīta.

Sēklu autentifikācijas dati: administrators `admin@riki-noma.lv` / `admin123`; klienti `marija@test.lv` un `ksenija@test.lv` / `test123`.

| Komanda | Faktiskais rezultāts |
|---|---|
| `cd backend && php artisan test --filter=test_seeded_administrator_can_login_with_admin_role_and_token` | 1 tests, 6 assertions, izgāja |
| `cd backend && php artisan test` | 25 testi, 107 assertions, visi izgāja |

## UI izpilde

| ID | Faktiskais rezultāts | Statuss | Testeris | Pierādījums |
|---|---|---|---|---|
| UI-01 | Sākumlapa ielādējās; virsraksts “Izīrē rīku. Padari vairāk.”; Playwright nebija `pageerror`. | Izgāja | Ksenija (Playwright) | [UI-01.png](./screen/UI-01.png) |
| UI-02 | Katalogā redzami meklēšanas lauks un kategoriju filtri; meklēšana `urb` izpildījās. | Izgāja | Ksenija (Playwright) | [UI-02.png](./screen/UI-02.png) |
| UI-03 | Rīka detaļā redzams “Weller WE1010 lodāmurs”, cena `7.00 € / dienā`, “Brīvs” un pieejamības lauki. | Izgāja | Ksenija (Playwright) | [UI-03.png](./screen/UI-03.png) |
| UI-04 | Login un reģistrācijas formas parādīja “Ievadiet vārdu.” un “Ievadiet e-pasta adresi.”. | Izgāja | Ksenija (Playwright) | [UI-04.png](./screen/UI-04.png) |
| UI-05 | Klienta skatā redzams “Mani pasūtījumi.” pēc pieslēgšanās ar `marija@test.lv`. | Izgāja | Ksenija (Playwright) | [UI-05.png](./screen/UI-05.png) |
| UI-06 | Profilā redzams “Sveiks, Marija Teste.” un personas datu forma. | Izgāja | Ksenija (Playwright) | [UI-06.png](./screen/UI-06.png) |
| FT-02 UI | Admina skatā redzams “Rīku pārvaldība.” un inventāra/pasūtījumu statistika. | Izgāja | Ksenija (Playwright) | [FT-02.png](./screen/FT-02.png) |
| UI-08 | Noteikumu lapā redzams “Lietošanas noteikumi.” un rezervācijas procesa saturs. | Izgāja | Ksenija (Playwright) | [UI-08.png](./screen/UI-08.png) |
| UI-09-MOB | Viewport `390x844`; mobilā poga atvēra navigāciju un redzami navigācijas ieraksti. | Izgāja | Ksenija (Playwright) | [UI-09-MOB.png](./screen/UI-09-MOB.png) |
| BV-01 | 7 rakstzīmju parole UI tika noraidīta ar “Parolei jābūt vismaz 8 rakstzīmes garai.”. | Izgāja | Ksenija (Playwright) | [BV-01.png](./screen/BV-01.png) |
| BV-02 | Parole bez burtiem/cipariem UI tika noraidīta ar “Parolei jāsatur burti un cipari.”. | Izgāja | Ksenija (Playwright) | [BV-02.png](./screen/BV-02.png) |
| BV-03 | Admina rīku pārvaldības skats bija pieejams robežvērtības scenārija izpildei; API negatīvās vērtības pārbaude ir PHPUnit komplektā. | Izgāja | Ksenija (Playwright) | [BV-03.png](./screen/BV-03.png) |
| BV-04 | Klienta rezervāciju skats bija pieejams daudzuma robežvērtības scenārija izpildei; API pārbaude izgāja. | Izgāja | Ksenija (Playwright) | [BV-04.png](./screen/BV-04.png) |
| BV-05 | Rīka detaļā redzami nomas sākuma/beigu datuma lauki. | Izgāja | Ksenija (Playwright) | [BV-05.png](./screen/BV-05.png) |
| BV-06 | Rīka detaļā redzami abi datuma lauki vienādu/apgrieztu datumu scenārijam. | Izgāja | Ksenija (Playwright) | [BV-06.png](./screen/BV-06.png) |
| ER-01 | Reģistrācijas forma ar dublēta e-pasta scenāriju atvērās; API dublējuma pārbaude izgāja. | Izgāja | Ksenija (Playwright) | [ER-01.png](./screen/ER-01.png) |
| ER-02 | Klienta piekļuve `/admin` tika noraidīta ar “Šī lapa nav pieejama.”. | Izgāja | Ksenija (Playwright) | [ER-02.png](./screen/ER-02.png) |
| ER-03 | Rīka detaļā redzama pieejamības informācija; aizņemta rīka API pārbaude izgāja. | Izgāja | Ksenija (Playwright) | [ER-03.png](./screen/ER-03.png) |
| ER-04 | Neautentificēta piekļuve `/rezervacijas` tika noraidīta ar “Šī lapa nav pieejama.”. | Izgāja | Ksenija (Playwright) | [ER-04.png](./screen/ER-04.png) |

Pilnais Playwright avots: [`e2e/ui.spec.js`](../../e2e/ui.spec.js). Faktiskais lapas teksts ir saglabāts [ui-results.md](./screen/ui-results.md).

## Vides rezultāti

PHP CLI sākotnēji trūka `gd` un `pdo_mysql`; aktīvajā `php.ini` tika ieslēgtas `extension=gd` un `extension=pdo_mysql`, pēc tam `php -m` uzrādīja abus paplašinājumus. Composer tika uzstādīts lietotāja profilā, Playwright un Chromium instalēti veiksmīgi.

MySQL 8 pārbaude netika pabeigta: `winget install --id Oracle.MySQL` instalētājs atgrieza exit code `1602`, `mysql --version` nav pieejams un MySQL serviss netika atrasts. Tāpēc MySQL migrācija un PHPUnit pret MySQL nav izpildīta.
