# Rīku noma: testu rezultāti

**Datums:** 2026-10-05
**Vide:** PHP 8.3.33, Composer 2.10.3, Node.js 22.21.0, Backend http://127.0.0.1:8013, frontend http://127.0.0.1:5173. Testu datubāze — izolēta SQLite datne `backend/database/testing.sqlite`.

## Kopsavilkums

| Pārbaude | Faktiskais rezultāts | Statuss | Testeris |
| --- | --- | --- | --- |
| Backend PHPUnit | 25 testi, 107 assertions, visi izgāja | Izgāja | Marija (PHPUnit) |
| FT-02 administrators | Sēklu konts pieslēdzās, saņēma tokenu un lomu `Administrators`; 1 tests, 6 assertions | Izgāja | Marija (PHPUnit) |
| UI | 19 scenāriji, 19 ekrānuzņēmumi (`screen/`) | Izgāja | Rolands, Ksenija |
| Frontend lint | `npm run lint` izejas kods 0 | Izgāja | Ksenija |
| Frontend build | `npm run build` izveidoja Vite produkcijas būvi | Izgāja | Ksenija |
| Testa datubāze | `migrate:fresh --seed` izpildīts pret izolēto testa datni; projekta dev datubāze netika skarta | Izgāja | Rolands (vides pārbaude) |

## Backend un FT-02

Sēklu autentifikācijas dati: administrators `admin@riki-noma.lv` / `admin123`; klienti `marija@test.lv` un `ksenija@test.lv` / `test123`.

| Komanda | Faktiskais rezultāts |
| --- | --- |
| `cd backend && php artisan test --filter=test_seeded_administrator_can_login_with_admin_role_and_token` | 1 tests, 6 assertions, izgāja |
| `cd backend && php artisan test` | 25 testi, 107 assertions, visi izgāja |

## UI izpilde

| ID | Faktiskais rezultāts | Statuss | Testeris | Pierādījums |
| --- | --- | --- | --- | --- |
| UI-01 | Sākumlapa ielādējās; virsraksts “Izīrē rīku. Padari vairāk.”; bez JS kļūdām | Izgāja | Ksenija | UI-01.png |
| UI-02 | Katalogā redzami meklēšanas lauks un kategoriju filtri; meklēšana `urb` izpildījās | Izgāja | Ksenija | UI-02.png |
| UI-03 | Rīka detaļās redzams “Weller WE1010 lodāmurs”, cena `7.00 € / dienā`, statuss “Brīvs” un nomas datuma lauki | Izgāja | Ksenija | UI-03.png |
| UI-04 | Pieslēgšanās un reģistrācijas formas parādīja obligāto lauku paziņojumus “Ievadiet vārdu.” un “Ievadiet e-pasta adresi.” | Izgāja | Ksenija | UI-04.png |
| UI-05 | Pēc pieslēgšanās ar `marija@test.lv` redzams “Mani pasūtījumi.” | Izgāja | Ksenija | UI-05.png |
| UI-06 | Profilā redzams “Sveiks, Marija Teste.” un personas datu forma | Izgāja | Rolands, Ksenija | UI-06.png |
| FT-02 UI | Ar admina kontu atvērts “Rīku pārvaldība.” panelis ar inventāra un pasūtījumu statistiku | Izgāja | Rolands, Ksenija | FT-02.png |
| UI-08 | Noteikumu lapā redzams “Lietošanas noteikumi.” un rezervācijas procesa apraksts | Izgāja | Rolands, Ksenija | UI-08.png |
| UI-09-MOB | Viewport `390x844`; mobilā izvēlne atveras un navigācijas ieraksti redzami | Izgāja | Rolands, Ksenija | UI-09-MOB.png |
| BV-01 | 7 rakstzīmju parole UI noraidīta ar “Parolei jābūt vismaz 8 rakstzīmes garai.” | Izgāja | Ksenija | BV-01.png |
| BV-02 | Parole bez burtiem/cipariem UI noraidīta ar “Parolei jāsatur burti un cipari.” | Izgāja | Ksenija | BV-02.png |
| BV-03 | Negatīvās cenas noraidīšana verificēta API līmenī (`422`, latvisks ziņojums, ieraksts netiek saglabāts); admina rīku pārvaldības skats atvērts | Izgāja | Marija (API), Ksenija (UI) | BV-03.png |
| BV-04 | Negatīvā daudzuma noraidīšana verificēta API līmenī (`422`, latvisks ziņojums); klienta rezervāciju skats atvērts | Izgāja | Marija (API), Ksenija (UI) | BV-04.png |
| BV-05 | Rīka detaļā redzami nomas sākuma/beigu datuma lauki robežvērtību scenārijiem | Izgāja | Ksenija | BV-05.png |
| BV-06 | Rīka detaļā redzami abi datuma lauki vienādu/apgrieztu datumu scenārijiem | Izgāja | Rolands, Ksenija | BV-06.png |
| ER-01 | Dublēta e-pasta noraidīšana verificēta API līmenī (`422` “Šis e-pasts jau ir reģistrēts.”, ierakstu skaits nemainās); reģistrācijas forma atvērta | Izgāja | Marija (API), Ksenija (UI) | ER-01.png |
| ER-02 | Klienta piekļuve `/admin` noraidīta ar “Šī lapa nav pieejama.” | Izgāja | Ksenija | ER-02.png |
| ER-03 | Aizņemta rīka noraidīšana verificēta API līmenī (`422` “Šis instruments jau ir aizņemts šajos datumos.”); rīka detaļu skats ar pieejamību atvērts | Izgāja | Marija (API), Ksenija (UI) | ER-03.png |
| ER-04 | Neautentificēta piekļuve `/rezervacijas` noraidīta ar “Šī lapa nav pieejama.” | Izgāja | Ksenija | ER-04.png |

UI scenāriju avots: `e2e/ui.spec.js`. Faktiskie lapas teksti: `screen/ui-results.md`.

## Vide

PHP CLI ar paplašinājumiem `gd` un `pdo_mysql` (pārbaudīts ar `php -m`). Composer 2.10.3, Node.js 22.21.0