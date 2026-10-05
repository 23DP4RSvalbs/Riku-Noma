# Testu rezultāti

Datums: 2026-10-05

Testi tika veikti ar PHP 8.3.33 un atsevišķu SQLite testa datni.

## Backend

| Pārbaude | Rezultāts | Testeris |
|---|---|---|
| Visi PHPUnit testi | 25 testi un 107 pārbaudes. Visi izgāja. | Marija (PHPUnit) |
| Administratora pieslēgšanās | Admina konts pieslēdzās. Tika saņemts tokens un loma Administrators. | Marija (PHPUnit) |
| Frontend lint | Izgāja. | Ksenija (frontend) |
| Frontend build | Izgāja. | Ksenija (frontend) |
| MySQL 8 | Neizdevās uzstādīt. Instalētājs beidzās ar kļūdu 1602. | Rolands (vides pārbaude) |

Admina konts no sēklas datiem:
`admin@riki-noma.lv` / `admin123`

Klientu konti:
`marija@test.lv` / `test123`
`ksenija@test.lv` / `test123`

## UI pārbaudes

Playwright faili un automātiskie attēli ir noņemti. Tie bija tikai darba palīgrīks un nav iesnieguma pierādījumi.

UI pārbaudes šajā reizē nav veiktas manuāli. Tāpēc tās nedrīkst saukt par izgājušām.

Manuāli jāpārbauda:

1. Sākumlapa un katalogs.
2. Rīka detaļas un pieejamība.
3. Login un reģistrācija.
4. Klienta profils un rezervācijas.
5. Admina panelis.
6. Noteikumi.
7. Mobilais skats 390 x 844.
8. BV un ER gadījumi testa plānā.

## Vide

PHP paplašinājumi `gd` un `pdo_mysql` ir ieslēgti.

Composer ir uzstādīts. Frontend atkarības ir uzstādītas frontend mapē.

MySQL nav pieejams. Tāpēc MySQL migrācija un testi nav veikti.
