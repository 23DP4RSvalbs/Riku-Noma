# Testēšanas plāns

## 1. Mērķis

Pārbaudīt, vai rīku nomas sistēma darbojas viesim, klientam un administratoram.

Viesis var apskatīt katalogu, meklēt rīkus un izmantot kategoriju filtrus.

Klients var reģistrēties, pieslēgties, apskatīt profilu, izveidot rezervāciju un atcelt savu rezervāciju.

Administrators var pārvaldīt rīkus, lietotājus un pasūtījumus.

## 2. Pārbaudāmie moduļi

| Modulis | Ko pārbauda |
|---|---|
| Autentifikācija | Reģistrācija, pieslēgšanās, paroles, e-pasta unikālitāte, tokens un lomas. |
| Katalogs | Rīku saraksts, meklēšana, kategorijas, detaļas un pieejamība. |
| Rezervācijas | Datumi, daudzums, cena, noteikumu piekrišana, saglabāšana un atcelšana. |
| Administrēšana | Rīku izveide, labošana, arhivēšana, lietotāji un pasūtījumu statusi. |
| Datu pārbaude | Obligātie lauki, robežvērtības, unikāli lauki un datu saglabāšana. |
| Lietotāja saskarne | Formas, kļūdu paziņojumi, navigācija un UI attēlojums. |

## 3. Testēšanas veidi

- Funkcionālā testēšana.
- API testēšana.
- PHPUnit testēšana.
- Robežvērtību testēšana.
- Kļūdu testēšana.
- Atkārtota pārbaude pēc labošanas.
- Manuāla UI pārbaude pārlūkā.

## 4. Testa vide un dati

| Vienība | Vērtība |
|---|---|
| PHP | 8.3.33 |
| Node.js | 22.21.0 |
| MySQL | 8.4.3 |
| MySQL datubāze | `riki_noma_test` |
| PHP paplašinājumi | `gd`, `pdo_mysql`, `pdo_sqlite` |
| Administrators | `admin@riki-noma.lv` / `admin123` |
| Klients | `marija@test.lv` / `test123` |

Testi tika veikti ar MySQL 8.4.3 un atsevišķu SQLite testa datni. Dev datubāze netika izmantota.

## 5. Testa gadījumi

| ID | Modulis | Sākuma stāvoklis | Soļi | Sagaidāmais rezultāts | Faktiskais rezultāts | Testeris | Datums |
|---|---|---|---|---|---|---|---|
| FT-01 | Autentifikācija | E-pasts nav reģistrēts. | Reģistrē jaunu klientu ar derīgu paroli. | Konts tiek izveidots ar klienta lomu un tokenu. | Konts un tokens izveidoti. | Marija (PHPUnit) | 2026-10-09 |
| FT-02 | Autentifikācija | Ir administratora sēklas konts. | Pieslēdzas ar admina e-pastu un paroli. | Saņem tokenu un lomu `Administrators`. | Tokens un loma saņemti. | Marija (PHPUnit) | 2026-10-09 |
| FT-03 | Katalogs | Publiskie rīki ir iesēkloti. | Meklē rīku pēc nosaukuma. | Atgriež atbilstošus rīkus. | Meklēšana izgāja. | Marija (API pieprasījumi) | 2026-10-09 |
| FT-04 | Katalogs | Rīki ir vairākās kategorijās. | Izmanto kategorijas filtru. | Redz tikai izvēlētās kategorijas rīkus. | Filtrs izgāja. | Marija (API pieprasījumi) | 2026-10-09 |
| FT-05 | Rezervācijas | Klients ir pieslēdzies un rīks ir pieejams. | Nosūta derīgu rezervāciju ar datumiem un piekrišanu. | Rezervācija tiek saglabāta ar pareizu summu. | Rezervācija saglabāta. | Marija (PHPUnit) | 2026-10-09 |
| FT-06 | Rezervācijas | Klientam ir jauns pasūtījums. | Atver vēsturi un atceļ savu pasūtījumu. | Redz tikai savu pasūtījumu, statuss kļūst `Atcelts`. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| FT-07 | Administrēšana | Administrators ir pieslēdzies. | Izveido, maina un arhivē rīku ar attēlu. | Izmaiņas tiek saglabātas. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| FT-08 | Administrēšana | Sistēmā ir pasūtījums. | Administrators maina pasūtījuma statusu. | Jaunais statuss tiek saglabāts. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| FT-09 | Katalogs | Katalogā ir pieejams rīks. | Apskata detaļas un pieejamību periodā. | Redz rīka datus un brīvo daudzumu. | Pārbaude izgāja. | Marija (API pieprasījumi) | 2026-10-09 |
| BV-01 | Autentifikācija | E-pasts nav reģistrēts. | Izmēģina 7 un 8 rakstzīmju paroli. | 7 rakstzīmes noraida, 8 pieņem. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| BV-02 | Autentifikācija | E-pasts nav reģistrēts. | Izmēģina paroli bez burtiem un bez cipariem. | Nederīgās paroles noraida. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| BV-03 | Administrēšana / UI | Administrators ir rīka formā. | Ievada cenu `-0.01` un iesniedz formu. | Negatīvu cenu noraida ar latviešu tekstu. | `Dienas cenai jābūt vismaz 0.` | Marija (PHPUnit), Ksenija (UI) | 2026-10-09 |
| BV-04 | Administrēšana / UI | Administrators ir rīka formā. | Ievada daudzumu `-1` un iesniedz formu. | Negatīvu daudzumu noraida ar latviešu tekstu. | `Daudzumam jābūt vismaz 0.` | Marija (PHPUnit), Ksenija (UI) | 2026-10-09 |
| BV-05 | Rezervācijas | Klients ir pieslēdzies. | Iesniedz rezervāciju ar vakardienas sākuma datumu. | Rezervāciju noraida un neko nesaglabā. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| BV-06 | Rezervācijas | Klients ir pieslēdzies. | Izmēģina vienādus un apgrieztus datumus. | Vienādus pieņem, apgrieztus noraida. | Pārbaude izgāja. | Marija (PHPUnit) | 2026-10-09 |
| ER-01 | Autentifikācija / UI | `marija@test.lv` jau pastāv. | Reģistrē ar šo pašu e-pastu. | Otro kontu neizveido un parāda kļūdu. | `Šis e-pasts jau ir reģistrēts.` | Marija (PHPUnit), Ksenija (UI) | 2026-10-09 |
| ER-02 | Autorizācija | Klients ir pieslēdzies. | Klients mēģina atvērt admina API. | Saņem `403`, admina dati nav pieejami. | Pārbaude izgāja. | Marija (API pieprasījumi) | 2026-10-09 |
| ER-03 | Rezervācijas / UI | Rīka daudzums periodā ir aizņemts. | Klients iesniedz rezervāciju tajā pašā periodā. | Rezervāciju noraida ar saprotamu kļūdu. | `Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.` | Marija (PHPUnit), Ksenija (UI) | 2026-10-09 |
| ER-04 | Autentifikācija | Lietotājs nav pieslēdzies. | Sūta pieprasījumu aizsargātam API. | Saņem `401` un dati netiek atgriezti. | Pārbaude izgāja. | Marija (API pieprasījumi) | 2026-10-09 |

## 6. UI scenāriji

UI pārbaudes atkārtoja plānotās lietotāja darbības pārlūkā. Tās tika piesaistītas šādiem testa gadījumiem:

| UI grupa | Scenāriji | Testeris |
|---|---|---|
| Pamatplūsmas | UI-01, UI-02, UI-03, UI-04, UI-05, UI-06, FT-02 UI, UI-08, UI-09-MOB | Ksenija (UI) |
| Paroles robežas | BV-01, BV-02 | Ksenija (UI) |
| Cenas un daudzums | BV-03, BV-04 | Ksenija (UI) |
| Datumi | BV-05, BV-06 | Ksenija (UI) |
| Kļūdu situācijas | ER-01, ER-02, ER-03, ER-04 | Ksenija (UI) |

UI faktiskie teksti un screenshot saites ir [ui-results.md](./screen/ui-results.md).

## 7. Testu kopsavilkums

- 19 no 19 plānotajiem testa gadījumiem izgāja API vai PHPUnit līmenī.
- 25 PHPUnit testi un 107 pārbaudes izgāja ar SQLite.
- 25 PHPUnit testi un 107 pārbaudes izgāja ar MySQL 8.4.3.
- 19 UI scenāriji izgāja pārlūkā; četri no tiem ietvēra reālu nederīgas formas iesniegšanu.
- Frontend lint un build izgāja.

## 8. Pierādījumi

Backend rezultāti ir [testu rezultātos](./testu-rezultati.md).

UI rezultāti un četri attēli ir [ui-results.md](./screen/ui-results.md).

Atrasto kļūdu labojumi un atkārtotie testi ir [kļūdu reģistrā](./kludu-registrs.md).

## 9. Izpildes kritēriji

- Katram testam ir ID, modulis, soļi, sagaidāmais rezultāts, faktiskais rezultāts, testētājs un datums.
- Ir pārbaudīti funkcionālie, robežvērtību un kļūdu gadījumi.
- Atrastās kļūdas ir aprakstītas, izlabotas un pārbaudītas atkārtoti.
- Rezultātiem ir PHPUnit, API un UI pierādījumi.
