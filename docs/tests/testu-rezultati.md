# TEST-2 testa rezultāti

**Datums:** 2026-10-02

**Vide:** `origin/main` (`815eb02`), zars `test/TEST-2-functional-ui`; PHP 8.4.15; Composer 2.10.3; Node.js 24.20.0; npm 11.19.0; Laravel 11.56.1. `backend/vendor` un `frontend/node_modules` bija pieejami. Papildu pakotnes netika instalētas.

## Droša testa datubāze

`migrate:fresh --seed` tika palaists tikai pret jaunu, pārbaudītu SQLite testa datni `/tmp/riku-noma-test-20261002.sqlite`, ar `APP_ENV=testing`, `DB_CONNECTION=sqlite` un šo precīzo `DB_DATABASE` ceļu. Pirms palaišanas Laravel konfigurācija tika nolasīta, lai pārbaudītu testa vidi un ceļu. Projekta lokālā `backend/database/database.sqlite` datne netika atiestatīta. Esošie `DatabaseSeeder`, `LomaSeeder`, `KategorijaSeeder`, `LietotajsSeeder` un `RiksSeeder` veiksmīgi ievietoja lomas, demonstrācijas kontus, kategorijas un rīkus.

MySQL 8 pārbaude ir **Bloķēta**: PHP instalācijai nav `pdo_mysql`, un MySQL klientis nav pieejams. Tāpēc MySQL servera versiju nevarēja pārbaudīt. PHP, Composer, Node.js prasības izpildītas; abu projektu atkarības jau bija instalētas.

## Automatizētie testi

Komanda `cd backend && php artisan test`: **14 izgāja, 6 neizgāja, kopā 20**. Feature/API testi tika izpildīti šīs komandas ietvaros.

| ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| `Tests\Unit\ExampleTest::test_that_true_is_true` | 2026-10-02 | Automatizēta izpilde | PHPUnit atzīmēja testu `PASS`. | Izgāja | `php artisan test`; `backend/tests/Unit/ExampleTest.php` |
| `AuthTest::test_user_can_register_with_client_role_and_token` | 2026-10-02 | Automatizēta izpilde | Reģistrācija, klienta loma un tokens; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php` |
| `AuthTest::test_registration_validates_password_and_confirmation_in_latvian` | 2026-10-02 | Automatizēta izpilde | Validācijas atbilde; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php` |
| `AuthTest::test_login_rejects_invalid_credentials_in_latvian` | 2026-10-02 | Automatizēta izpilde | Nederīga pieslēgšanās noraidīta; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php` |
| `AuthTest::test_user_can_login_and_read_profile` | 2026-10-02 | Automatizēta izpilde | Pieslēgšanās un profila nolasīšana; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php` |
| `AuthTest::test_user_can_logout_and_token_is_revoked` | 2026-10-02 | Automatizēta izpilde | Pēc atteikšanās `GET /api/user` atgrieza `200`, nevis sagaidīto `401`. | Neizgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php`; kļūda `TEST2-001` |
| `AuthTest::test_role_admin_middleware_accepts_administrator_role` | 2026-10-02 | Automatizēta izpilde | Administratora loma pieņemta; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/AuthTest.php` |
| `Tests\Feature\ExampleTest::test_the_application_returns_a_successful_response` | 2026-10-02 | Automatizēta izpilde | Sākumlapas HTTP atbilde veiksmīga; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ExampleTest.php` |
| `OrderApiTest::test_user_can_create_order_with_total_and_dates` | 2026-10-02 | Automatizēta izpilde | Saņemts `422`, jo testa pieprasījumā trūkst `noteikumi_apstiprinati` un `noteikumu_versija`; sagaidīts `201`. | Neizgāja | `php artisan test`; `backend/tests/Feature/OrderApiTest.php`; kļūda `TEST2-002` |
| `OrderApiTest::test_overlapping_order_is_rejected_when_quantity_is_unavailable` | 2026-10-02 | Automatizēta izpilde | Pirms pārklāšanās pārbaudes testa palīgmetode mēģināja atkārtoti izveidot unikālo lomu `Klients`; SQLite `UNIQUE constraint failed`. | Neizgāja | `php artisan test`; `backend/tests/Feature/OrderApiTest.php`; kļūda `TEST2-003` |
| `OrderApiTest::test_user_can_only_see_and_cancel_own_new_orders` | 2026-10-02 | Automatizēta izpilde | Testa palīgmetode atkārtoti izveidoja unikālo lomu `Klients`; SQLite `UNIQUE constraint failed`. | Neizgāja | `php artisan test`; `backend/tests/Feature/OrderApiTest.php`; kļūda `TEST2-004` |
| `OrderApiTest::test_admin_can_filter_orders_and_update_status` | 2026-10-02 | Automatizēta izpilde | Administratora pasūtījumu filtrēšana un statusa maiņa; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/OrderApiTest.php` |
| `ToolApiTest::test_guest_can_list_only_publicly_available_tools` | 2026-10-02 | Automatizēta izpilde | Viesim redzami tikai publiski pieejamie rīki; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |
| `ToolApiTest::test_public_catalog_supports_search_category_and_pagination` | 2026-10-02 | Automatizēta izpilde | `meta.total` bija `null`, nevis sagaidītais `2`; `meta.per_page` līgums netika apstiprināts. | Neizgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php`; kļūda `TEST2-005` |
| `ToolApiTest::test_public_details_and_availability_hide_non_public_tools` | 2026-10-02 | Automatizēta izpilde | Publiskās detaļas un slēpta rīka nepieejamība; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |
| `ToolApiTest::test_availability_counts_only_overlapping_active_orders` | 2026-10-02 | Automatizēta izpilde | Pārklājošo aktīvo pasūtījumu skaits; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |
| `ToolApiTest::test_month_availability_returns_daily_remaining_quantities` | 2026-10-02 | Automatizēta izpilde | Mēneša pieejamības dienu atlikumi; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |
| `ToolApiTest::test_non_admin_cannot_create_tool` | 2026-10-02 | Automatizēta izpilde | Klienta rīka izveide aizliegta; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |
| `ToolApiTest::test_admin_can_create_update_and_archive_tool_with_photo` | 2026-10-02 | Automatizēta izpilde | Testu apturēja `GD extension is not installed` fotoattēla sagatavošanā. | Bloķēts | `php artisan test`; `backend/tests/Feature/ToolApiTest.php`; kļūda `TEST2-006` |
| `ToolApiTest::test_invalid_tool_values_are_rejected` | 2026-10-02 | Automatizēta izpilde | Nederīgi rīka lauki noraidīti; `PASS`. | Izgāja | `php artisan test`; `backend/tests/Feature/ToolApiTest.php` |

## UI un testa plāna statuss

**Korekcija:** TEST-2 atskaitē kļūdaini norādīts, ka `docs/tests/testa-plans.md` nav atrasts. Plāns bija pieejams jau TEST-2 bāzes commitā `815eb02`; toreizējā `find` komanda tika palaista no `backend` mapes un skatīja nepareizo relatīvo ceļu. TEST-2 UI pārbaudes joprojām netika veiktas, jo pārlūka izpildvide nebija pieejama. TEST-3 scenāriji zemāk ņemti no faktiskā plāna.

Visiem UI scenārijiem statuss ir **Bloķēts**: vidē nav Chromium, Chrome, Firefox, Playwright vai Puppeteer, tāpēc nebija iespējams veikt pārlūka klikšķu, vizuālo vai mobilo skatu testēšanu.

| ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| Viesis: `/` | 2026-10-02 | Automatizēta izpilde | Pārlūkā sākumlapa netika atvērta un pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: palaist šo maršrutu pārlūkā. |
| Viesis: `/katalogs` | 2026-10-02 | Automatizēta izpilde | Meklēšana un kategoriju filtri pārlūkā netika izmēģināti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: izmēģināt viesim ar sēklas datiem. |
| Viesis: `/katalogs/{id}` | 2026-10-02 | Automatizēta izpilde | Rīka detaļas, pieejamība un atsauksmes pārlūkā netika pārbaudītas. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: atvērt sēklas rīka detaļas. |
| Viesis: `/ieiet`, `/registracija` | 2026-10-02 | Automatizēta izpilde | Viesim paredzētās pieslēgšanās un reģistrācijas formas pārlūkā netika pārbaudītas. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt abas formas pārlūkā. |
| Klients: `/rezervacijas` | 2026-10-02 | Automatizēta izpilde | Pasūtījumu vēsture, atcelšana, rezervācijas kalendārs un noteikumu piekrišana pārlūkā netika pārbaudīti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pieslēgties ar testa klientu un pārbaudīt visas darbības. |
| Klients: `/profils` | 2026-10-02 | Automatizēta izpilde | Kontakta datu un paroles maiņa pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt profila formas ar testa klientu. |
| Administrators: `/admin` | 2026-10-02 | Automatizēta izpilde | Inventāra, pasūtījumu un lietotāju pārvaldība pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt paneli ar administratora testa kontu. |
| Noteikumi: `/noteikumi` | 2026-10-02 | Automatizēta izpilde | Noteikumu lapa un obligātās piekrišanas mijiedarbība pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt noteikumu lapu un rezervācijas iesniegšanas validāciju. |
| Mobilā navigācija un izkārtojumi | 2026-10-02 | Automatizēta izpilde | Mobilā izvēlne un izkārtojumi dažādos skatos netika pārbaudīti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: izpildīt apstiprinātajā UI testa plānā definētos mobilos skatus. |

## TEST-3 robežvērtību un kļūdu scenāriji

**Vide:** latest `origin/main` `84ab129`, zars `test/TEST-3-boundary-errors`; API `127.0.0.1:8013`; izolēta SQLite datubāze `/tmp/riku-noma-test3-20261002.sqlite`, migrēta un piepildīta ar esošajiem sēklas datiem. Katrā nederīgajā ievadē salīdzināts mērķa ierakstu skaits pirms un pēc pieprasījuma. Derīgie pārbaudes ieraksti palika tikai izolētajā testa datubāzē; rezervācijas, kas aizņēma krājumu turpmākam testam, pēc mērījuma tika atceltas caur API.

| ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| BV-01 (7 rakstzīmes) | 2026-10-02 | API | `POST /api/register` atgrieza `422`, ziņojums: “Parolei jābūt vismaz 8 rakstzīmes garai.” Lietotāja ieraksts `0->0`. | Izgāja | API atbilde un SQLite `lietotajs` skaits e-pastam `bv01-7@example.test`, pirms/pēc. |
| BV-01 (8 rakstzīmes) | 2026-10-02 | API | `Riki1234` tika pieņemta; `201`, “Lietotājs veiksmīgi reģistrēts.” Lietotāja ieraksts `0->1`. | Izgāja | API atbilde un SQLite `lietotajs` skaits e-pastam `bv01-8@example.test`, pirms/pēc. |
| BV-02 (bez burtiem) | 2026-10-02 | API | `12345678` noraidīta ar `422`, “Parolei jāsatur burti un cipari.” Ieraksts `0->0`. | Izgāja | API atbilde un SQLite `lietotajs` skaits e-pastam `bv02-digits@example.test`, pirms/pēc. |
| BV-02 (bez cipariem) | 2026-10-02 | API | `Rikikiki` noraidīta ar `422`, “Parolei jāsatur burti un cipari.” Ieraksts `0->0`. | Izgāja | API atbilde un SQLite `lietotajs` skaits e-pastam `bv02-letters@example.test`, pirms/pēc. |
| BV-03 (cena 0) | 2026-10-02 | API | Administratora `POST /api/tools` ar cenu `0.00` atgrieza `201`; rīka ieraksts `0->1`. | Izgāja | API atbilde un SQLite `riks` skaits nosaukumam `TEST3-BV03-zero-price`, pirms/pēc. |
| BV-03 (negatīva cena) | 2026-10-02 | API | Cena `-0.01` noraidīta ar `422`; ieraksts `0->0`. Ziņojums angliski: “The cenadiena field must be at least 0.” | Neizgāja | API atbilde un SQLite `riks` skaits nosaukumam `TEST3-BV03-negative-price`, pirms/pēc; kļūda `TEST3-002`. |
| BV-04 (daudzums 0) | 2026-10-02 | API | Daudzums `0` pieņemts ar `201`; rīka ieraksts `0->1`. | Izgāja | API atbilde un SQLite `riks` skaits nosaukumam `TEST3-BV04-zero-quantity`, pirms/pēc. |
| BV-04 (negatīvs daudzums) | 2026-10-02 | API | Daudzums `-1` noraidīts ar `422`; ieraksts `0->0`. Ziņojums angliski: “The daudzums field must be at least 0.” | Neizgāja | API atbilde un SQLite `riks` skaits nosaukumam `TEST3-BV04-negative-quantity`, pirms/pēc; kļūda `TEST3-002`. |
| BV-05 (sākums pagātnē) | 2026-10-02 | API | Sākuma datums `01.10.2026` (iepriekšējā diena) pieņemts ar `201`; pasūtījumu/rindu skaits `0->1`. Izveidoto pasūtījumu pēc rezultāta fiksēšanas atcēla caur atcelšanas API. | Neizgāja | `POST /api/orders` atbilde un SQLite `pasutijums`/`pasutijuma_riks` skaiti pirms/pēc; kļūda `TEST3-001`. |
| BV-06 (vienādi datumi) | 2026-10-02 | API | Sākums un beigas `10.10.2026` pieņemti ar `201`; pasūtījumu/rindu skaits `1->2`. Pārbaudes rezervācija pēc mērījuma atcelta caur API. | Izgāja | `POST /api/orders` atbilde un SQLite `pasutijums`/`pasutijuma_riks` skaiti pirms/pēc. |
| BV-06 (beigas pirms sākuma) | 2026-10-02 | API | `11.10.2026` līdz `10.10.2026` noraidīts ar `422`, “Nomas beigu datumam jābūt pēc sākuma datuma.” Pasūtījumu/rindu skaits palika `2->2`. | Neizgāja | `POST /api/orders` atbilde un SQLite `pasutijums`/`pasutijuma_riks` skaiti pirms/pēc; kļūda `TEST3-004` par pretrunīgo formulējumu. |
| ER-01 (dublēts e-pasts) | 2026-10-02 | API | Esošais `marija@test.lv` noraidīts ar `422`, “Šis e-pasts jau ir reģistrēts.” Lietotāja skaits palika `1->1`. | Izgāja | API atbilde un SQLite `lietotajs` skaits e-pastam `marija@test.lv`, pirms/pēc. |
| ER-02 (klients admin maršrutā) | 2026-10-02 | API | Autentificētam klientam `GET /api/orders` atgrieza `403`, “Jums nav nepieciešamo tiesību.” Pasūtījumu skaits nemainījās. | Izgāja | API atbilde un SQLite `pasutijums` skaits pirms/pēc. |
| ER-03 (nepieejams rīks) | 2026-10-02 | API | Otrs klients mēģināja rezervēt jau pilnībā rezervētu rīku; `422`, “Šis instruments jau ir aizņemts šajos datumos”. Pasūtījumu un rindu skaits palika attiecīgi `3->3` un `3->3`. | Izgāja | API atbilde un SQLite `pasutijums`/`pasutijuma_riks` skaiti pirms/pēc. |
| ER-04 (bez autentifikācijas) | 2026-10-02 | API | `GET /api/orders` bez tokena atgrieza `401`, “Unauthenticated.” Pasūtījumu skaits nemainījās. | Neizgāja | API atbilde un SQLite `pasutijums` skaits pirms/pēc; angļu ziņojums reģistrēts kā `TEST3-003`. |

Visas BV/ER scenāriju **pārlūka** pārbaudes ir **Bloķētas**: vidē nav Chromium/Chrome/Firefox un nav Playwright/Puppeteer. Tāpēc nav apgalvots, ka UI kļūdas redzamība vai klienta formas uzvedība ir pārbaudīta.

| ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| BV-01 UI | 2026-10-02 | Pārlūks | 7/8 rakstzīmju reģistrācijas robeža lietotāja saskarnē nav pārbaudīta. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium `docs/tests/testa-plans.md` BV-01. |
| BV-02 UI | 2026-10-02 | Pārlūks | Paroles sastāva kļūdas lietotāja saskarnē nav pārbaudītas. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium BV-02. |
| BV-03 UI | 2026-10-02 | Pārlūks | Cenas `0`/negatīvas cenas forma un kļūdas paziņojums nav pārbaudīti. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium BV-03. |
| BV-04 UI | 2026-10-02 | Pārlūks | Daudzuma `0`/negatīva daudzuma forma un kļūdas paziņojums nav pārbaudīti. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium BV-04. |
| BV-05 UI | 2026-10-02 | Pārlūks | Pagātnes datuma izvēles un lietotājam redzamās validācijas uzvedība nav pārbaudīta. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium BV-05. |
| BV-06 UI | 2026-10-02 | Pārlūks | Vienādu/apgrieztu datumu formas uzvedība un kļūdas teksts nav pārbaudīts. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium BV-06. |
| ER-01 UI | 2026-10-02 | Pārlūks | Dublēta e-pasta formas kļūdas paziņojums nav pārbaudīts. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium ER-01. |
| ER-02 UI | 2026-10-02 | Pārlūks | Klienta atteikums admina lapai un navigācija pēc atteikuma nav pārbaudīta. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium ER-02. |
| ER-03 UI | 2026-10-02 | Pārlūks | Aizņemta rīka pieteikuma kļūdas paziņojums nav pārbaudīts. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium ER-03. |
| ER-04 UI | 2026-10-02 | Pārlūks | Neautentificētas piekļuves UI un pieslēgšanās piedāvājums nav pārbaudīts. | Bloķēts | Pārlūka izpildvide nav pieejama; nākamais solis: izpildīt ar Chromium ER-04. |

## Frontend pārbaudes

| Komanda | Rezultāts |
|---|---|
| `cd frontend && npm run lint` | Izgāja |
| `cd frontend && npm run build` | Izgāja; Vite izveidoja produkcijas būvējumu |

MySQL 8 un pārlūka testi paliek bloķēti. TEST-2 un TEST-3 kļūdas ir uzskaitītas `docs/tests/kludu-registrs.md`. Lietotnes kods TEST-2/TEST-3 laikā netika mainīts.

## TEST-4 atkārtotā pārbaude

| Testa ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| TEST2-001 | 2026-10-02 | Automatizēta izpilde | Reālā HTTP secībā login `200`, logout `200`, tokena ieraksts datubāzē dzēsts, nākamais `GET /api/user` `401`. PHPUnit testā pēc guard atiestatīšanas sagaidītais `401`; tokena ieraksta dzēšana pārbaudīta atsevišķi. | Izgāja | `cd backend && php artisan test --filter=test_user_can_logout_and_token_is_revoked`; 1 tests, 3 assertions; atsevišķs API request cikls; commit `f116253`. |
| Backend regresija pēc TEST2-001 | 2026-10-02 | Automatizēta izpilde | Pilnā `php artisan test` kopa: 15 izgāja, 5 neizgāja; TEST2-001 vairs nav starp kļūdām. | Daļēji izgāja | `cd backend && php artisan test`; pilnā izvade pēc `f116253`. |
