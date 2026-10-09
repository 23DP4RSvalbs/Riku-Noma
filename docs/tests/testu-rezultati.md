# Testu rezultāti

Datums: 2026-10-09

## Backend

| Pārbaude | Faktiskais rezultāts | Statuss | Testeris |
|---|---|---|---|
| PHPUnit ar SQLite | 25 testi, 107 pārbaudes. | Izgāja | Marija (PHPUnit) |
| PHPUnit ar MySQL 8.4.3 | 25 testi, 107 pārbaudes. | Izgāja | Marija (PHPUnit) |
| Administratora pieslēgšanās | Admina konts saņēma tokenu un lomu `Administrators`. | Izgāja | Marija (PHPUnit) |
| Frontend lint | Pārbaude izgāja. | Izgāja | Ksenija (frontend) |
| Frontend build | Būve izveidojās bez kļūdām. | Izgāja | Ksenija (frontend) |

MySQL servera versija: `8.4.3`.

Admina konts: `admin@riki-noma.lv` / `admin123`

Klienta konts: `marija@test.lv` / `test123`

## UI pārbaudes

| ID | Faktiskais rezultāts | Statuss | Testeris | Ekrānuzņēmums |
|---|---|---|---|---|
| BV-03 | Pārlūks rādīja `Value must be greater than or equal to 0.`. | Neizgāja | Ksenija (pārlūks) | [BV-03.png](./screen/BV-03.png) |
| BV-04 | Pārlūks rādīja `Value must be greater than or equal to 0.`. | Neizgāja | Ksenija (pārlūks) | [BV-04.png](./screen/BV-04.png) |
| ER-01 | Forma rādīja `Šis e-pasts jau ir reģistrēts.`. | Izgāja | Ksenija (pārlūks) | [ER-01.png](./screen/ER-01.png) |
| ER-03 | Aizņemtā diena nebija izvēlama. Poga `Apstiprināt rezervāciju` bija atspējota. | Neizgāja | Ksenija (pārlūks) | [ER-03.png](./screen/ER-03.png) |

Pilnie četru UI ierakstu teksti ir [ui-results.md](./screen/ui-results.md).

## Vide

PHP: `8.3.33`

Node.js: `22.21.0`

PHP paplašinājumi: `gd`, `pdo_mysql`, `pdo_sqlite`

Testa datubāze: MySQL `8.4.3`, datubāze `riki_noma_test`.

Papildu SQLite pārbaude tika veikta atsevišķā testa datnē.
