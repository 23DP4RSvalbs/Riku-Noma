# Rīku noma: testēšanas atskaite

**Datums:** 2026-10-05
**Zars:** `test/final-ui-mysql`
**Testētāji:** Rolands — vides pārbaude un dokumentācija; Marija — backend/API/PHPUnit; Ksenija — frontend/UI/Playwright.

## 1. Kopsavilkums

| Rādītājs | Iepriekš | Gala faktiskā pārbaude |
|---|---|---|
| UI | 19 bloķētas | 19 izpildītas ar headless Chromium |
| FT-02 | Bloķēts | Izgāja: 1 tests, 6 assertions |
| Backend PHPUnit | Iepriekšējā vēsturē nepilna kopa | Izgāja: 25 testi, 107 assertions |
| MySQL 8 | Bloķēts | Paliek bloķēts: instalētājs exit code 1602; MySQL nav pieejams |
| Frontend kvalitātes pārbaudes | Nepilnīgi dokumentētas | lint un build izgāja |

## 2. Vide un izpilde

Izmantots PHP 8.3.33, Composer 2.10.3, Node.js 22.21.0, npm 10.9.4, Playwright 1.63.0 un Chromium 153.0.8010.12. PHP `gd` un `pdo_mysql` tika ieslēgti aktīvajā `php.ini` un pārbaudīti ar `php -m`.

Backend tests tika izpildīti ar izolētu `backend/database/testing.sqlite`, neietekmējot developeru lokālo SQLite datni. Backend darbojās uz `http://127.0.0.1:8013`, frontend uz `http://127.0.0.1:5173`. UI pierādījumi ir [testu rezultātos](./testu-rezultati.md) un [ui-results.md](./screen/ui-results.md).

### 2.1. Gala izpildes tabula

| Pārbaude | Faktiskais rezultāts | Statuss | Testeris |
|---|---|---|---|
| PHPUnit | 25/25 testi, 107 assertions | Izgāja | Marija (PHPUnit) |
| Playwright UI | 19/19 scenāriji ar screenshot | Izgāja | Ksenija (Playwright) |
| Frontend lint/build | Abas komandas izgāja | Izgāja | Ksenija (Playwright) |
| MySQL 8 | Nav servera/klienta pēc instalētāja 1602 | Bloķēts | Rolands (vides pārbaude) |

### 2.2. UI un robežvērtību rezultāti

Visi deviņi pamatskati, mobilais skats, FT-02 UI un desmit BV/ER UI scenāriji izpildījās. Konkrētais redzamais teksts un screenshot saites ir [ui-results.md](./screen/ui-results.md); attēlu kopa atrodas [screen mapē](./screen/).

## 3. Kļūdu reģistrs

Iepriekš reģistrētie 11 TEST2/TEST3/TEST4 atradumi ir apkopoti [kļūdu reģistrā](./kludu-registrs.md). To gala integrācijas retesti bija pieejami `origin/main` bāzē; šajā izpildē visi 25 backend testi izgāja. Jauns lietotnes defekts šodienas UI izpildē netika reģistrēts. MySQL instalācijas neveiksme ir vides, nevis lietotnes kļūda.

## 4. Testētāju atribūcija

Backend/PHPUnit/API rindas: **Marija (PHPUnit)** vai **Marija (API pieprasījumi)**. UI un Playwright rindas: **Ksenija (Playwright)**. Vides un kopsavilkuma rindas: **Rolands (vides pārbaude)**. Visi jaunie izpildes datumi ir 2026-10-05.

## 5. Secinājumi

Šodien izpildītas visas plānotās 19 pamatpārbaudes, 19 UI scenāriji un backend PHPUnit kopa; FT-02 administratora login pierādījums izgāja, un frontend lint/build izgāja. Vienīgais neizpildītais elements ir MySQL 8 retests, jo Oracle instalētājs beidzās ar exit code 1602 un serveris šajā datorā nav pieejams. Reāls šodien atrasts lietotnes uzlabojums nav konstatēts.

## 6. Pielikumi

- [Testu plāns](./testa-plans.md)
- [Testu rezultāti](./testu-rezultati.md)
- [Kļūdu reģistrs](./kludu-registrs.md)
- [UI faktiskie rezultāti](./screen/ui-results.md)
- [UI screenshot mape](./screen/)
