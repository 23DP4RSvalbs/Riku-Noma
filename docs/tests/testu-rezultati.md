# Testu rezultāti

Datums: 2026-10-09

## Kopsavilkums

| Pārbaude | Faktiskais rezultāts | Statuss |
|---|---|---|
| Plānotie testa gadījumi | 19 no 19 izpildīti API un PHPUnit līmenī | Izgāja |
| PHPUnit ar SQLite | 25 testi, 107 pārbaudes | Izgāja |
| PHPUnit ar MySQL 8.4.3 | 25 testi, 107 pārbaudes | Izgāja |
| UI pārbaudes | 19 no 19 izpildītas pārlūkā | Izgāja |
| Frontend lint | Pārbaude izgāja | Izgāja |
| Frontend build | Būve izveidojās bez kļūdām | Izgāja |

## 19 testa gadījumi

| ID | Pārbaudes rezultāts | Metode | Statuss | Testeris |
|---|---|---|---|---|
| FT-01 | Derīga reģistrācija izveido klienta kontu un atgriež tokenu. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-02 | Admina konts saņem tokenu un lomu `Administrators`. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-03 | Kataloga meklēšana atgriež atbilstošus publiskos rīkus. | PHPUnit / API | Izgāja | Marija (API pieprasījumi) |
| FT-04 | Kategorijas filtrs atgriež izvēlētās kategorijas rīkus. | PHPUnit / API | Izgāja | Marija (API pieprasījumi) |
| FT-05 | Derīga rezervācija tiek saglabāta ar pareizu summu un datumiem. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-06 | Klients redz un atceļ tikai savu jauno rezervāciju. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-07 | Administrators var izveidot, mainīt un arhivēt rīku ar attēlu. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-08 | Administrators var filtrēt pasūtījumus un mainīt statusu. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| FT-09 | Rīka detaļas un pieejamības aprēķins atgriež pareizus datus. | PHPUnit / API | Izgāja | Marija (API pieprasījumi) |
| BV-01 | 7 rakstzīmju parole tiek noraidīta, 8 rakstzīmju parole tiek pieņemta. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| BV-02 | Parole bez burtiem vai bez cipariem tiek noraidīta. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| BV-03 | Negatīva cena tiek noraidīta ar `Dienas cenai jābūt vismaz 0.`. | PHPUnit / API / UI | Izgāja | Marija (PHPUnit), Ksenija (UI) |
| BV-04 | Negatīvs daudzums tiek noraidīts ar `Daudzumam jābūt vismaz 0.`. | PHPUnit / API / UI | Izgāja | Marija (PHPUnit), Ksenija (UI) |
| BV-05 | Pagātnes nomas sākuma datums tiek noraidīts bez saglabāta pasūtījuma. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| BV-06 | Vienāds datums tiek pieņemts, apgriezts periods tiek noraidīts. | PHPUnit / API | Izgāja | Marija (PHPUnit) |
| ER-01 | Dublēts e-pasts tiek noraidīts ar `Šis e-pasts jau ir reģistrēts.`. | PHPUnit / API / UI | Izgāja | Marija (PHPUnit), Ksenija (UI) |
| ER-02 | Klienta piekļuve administratora API tiek noraidīta ar `403`. | PHPUnit / API | Izgāja | Marija (API pieprasījumi) |
| ER-03 | Aizņemts periods tiek noraidīts ar `Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.`. | PHPUnit / API / UI | Izgāja | Marija (PHPUnit), Ksenija (UI) |
| ER-04 | Neautentificēts aizsargāta API maršruta pieprasījums saņem `401`. | PHPUnit / API | Izgāja | Marija (API pieprasījumi) |

## UI pierādījumi

Pārlūkā tika izpildīti 19 UI scenāriji. Pilns faktisko rezultātu, testētāju, datumu un screenshotu saraksts ir [ui-results.md](./screen/ui-results.md).

| UI grupa | Scenāriji | Testeris | Pierādījums |
|---|---|---|---|
| Pamatplūsmas | UI-01 līdz UI-06, FT-02 UI, UI-08, UI-09-MOB | Ksenija (UI) | [ui-results.md](./screen/ui-results.md) |
| Paroles robežas | BV-01, BV-02 | Ksenija (UI) | [ui-results.md](./screen/ui-results.md) |
| Cenas un daudzums | BV-03, BV-04 | Ksenija (UI) | [ui-results.md](./screen/ui-results.md) |
| Datumi | BV-05, BV-06 | Ksenija (UI) | [ui-results.md](./screen/ui-results.md) |
| Kļūdu situācijas | ER-01, ER-02, ER-03, ER-04 | Ksenija (UI) | [ui-results.md](./screen/ui-results.md) |

## Vide

PHP: `8.3.33`

Node.js: `22.21.0`

PHP paplašinājumi: `gd`, `pdo_mysql`, `pdo_sqlite`

MySQL serveris: `8.4.3`

MySQL testa datubāze: `riki_noma_test`

Admina konts: `admin@riki-noma.lv` / `admin123`

Klienta konts: `marija@test.lv` / `test123`
