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

`docs/tests/testa-plans.md` repozitorijā nav atrasts (`find docs -maxdepth 4 -type f`). Tāpēc oficiālos `FT-xx` identifikatorus un UI scenāriju sarakstu nevar noteikt; tie nav izdomāti. Tālāk norādītās rindas ir esošo lietotnes maršrutu un lomu pārklājuma inventārs, nevis aizstājēji trūkstošajiem plāna ID.

Visiem UI scenārijiem statuss ir **Bloķēts**: vidē nav Chromium, Chrome, Firefox, Playwright vai Puppeteer, tāpēc nebija iespējams veikt pārlūka klikšķu, vizuālo vai mobilo skatu testēšanu.

| ID | Datums | Testeris | Faktiskais rezultāts | Statuss | Pierādījums |
|---|---|---|---|---|---|
| Trūkstošais FT/UI plāns | 2026-10-02 | Automatizēta izpilde | Faila `docs/tests/testa-plans.md` nav; oficiālie scenāriji un ID nav zināmi. | Bloķēts | `find docs -maxdepth 4 -type f`; nākamais solis: pievienot apstiprināto testa plānu un atkārtot katru tajā norādīto gadījumu. |
| Viesis: `/` | 2026-10-02 | Automatizēta izpilde | Pārlūkā sākumlapa netika atvērta un pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: palaist šo maršrutu pārlūkā. |
| Viesis: `/katalogs` | 2026-10-02 | Automatizēta izpilde | Meklēšana un kategoriju filtri pārlūkā netika izmēģināti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: izmēģināt viesim ar sēklas datiem. |
| Viesis: `/katalogs/{id}` | 2026-10-02 | Automatizēta izpilde | Rīka detaļas, pieejamība un atsauksmes pārlūkā netika pārbaudītas. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: atvērt sēklas rīka detaļas. |
| Viesis: `/ieiet`, `/registracija` | 2026-10-02 | Automatizēta izpilde | Viesim paredzētās pieslēgšanās un reģistrācijas formas pārlūkā netika pārbaudītas. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt abas formas pārlūkā. |
| Klients: `/rezervacijas` | 2026-10-02 | Automatizēta izpilde | Pasūtījumu vēsture, atcelšana, rezervācijas kalendārs un noteikumu piekrišana pārlūkā netika pārbaudīti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pieslēgties ar testa klientu un pārbaudīt visas darbības. |
| Klients: `/profils` | 2026-10-02 | Automatizēta izpilde | Kontakta datu un paroles maiņa pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt profila formas ar testa klientu. |
| Administrators: `/admin` | 2026-10-02 | Automatizēta izpilde | Inventāra, pasūtījumu un lietotāju pārvaldība pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt paneli ar administratora testa kontu. |
| Noteikumi: `/noteikumi` | 2026-10-02 | Automatizēta izpilde | Noteikumu lapa un obligātās piekrišanas mijiedarbība pārlūkā netika pārbaudīta. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: pārbaudīt noteikumu lapu un rezervācijas iesniegšanas validāciju. |
| Mobilā navigācija un izkārtojumi | 2026-10-02 | Automatizēta izpilde | Mobilā izvēlne un izkārtojumi dažādos skatos netika pārbaudīti. | Bloķēts | Pārlūka izpildvide nav instalēta; nākamais solis: izpildīt apstiprinātajā UI testa plānā definētos mobilos skatus. |

## Frontend pārbaudes

| Komanda | Rezultāts |
|---|---|
| `cd frontend && npm run lint` | Izgāja |
| `cd frontend && npm run build` | Izgāja; Vite izveidoja produkcijas būvējumu |

Pieteiktais MySQL 8 un pārlūka tests paliek bloķēts. Uzvedības testu kļūdas ir uzskaitītas `docs/tests/kludu-registrs.md`. Lietotnes kods TEST-2/TEST-3 laikā netika mainīts.
