# Kļūdu reģistrs

**Gala retesta datums:** 2026-10-05
**Atribūcija:** backend/API — Marija (PHPUnit/API pieprasījumi); UI — Ksenija (Playwright); vide — Rolands (vides pārbaude).

| ID | Sākotnējais atradums | Novēršana / gala rezultāts | Commit | Retests |
|---|---|---|---|---|
| TEST2-001 | PHPUnit guard kešoja stāvokli pēc logout; atsevišķā HTTP ciklā API kļūda nebija reproducējama. | Tests atiestata guard un pārbauda tokena dzēšanu; gala PHPUnit kopa izgāja. | `d8963b4` | Marija (PHPUnit), 2026-10-05 |
| TEST2-002 | Order testa ievadē trūka noteikumu piekrišanas lauku. | Pievienoti `noteikumi_apstiprinati` un `noteikumu_versija`; gala kopa izgāja. | `f7c70be` | Marija (PHPUnit), 2026-10-05 |
| TEST2-003 | Pārklāšanās testa sagatavošana izveidoja dublētu `Klients` lomu. | Izmantots `firstOrCreate()`; pārklāšanās tests izgāja. | `6d3d2ef` | Marija (PHPUnit), 2026-10-05 |
| TEST2-004 | Īpašumtiesību testa sagatavošana izveidoja dublētu `Klients` lomu. | Izmantots kopīgs lomas ieraksts; ownership/cancel tests izgāja. | `6d3d2ef` | Marija (PHPUnit), 2026-10-05 |
| TEST2-005 | Kataloga tests gaidīja neatbilstošu paginatora JSON struktūru. | Assertions salāgotas ar faktisko API atbildi; kataloga tests izgāja. | `7ca0f01` | Marija (PHPUnit), 2026-10-05 |
| TEST2-006 | Foto testa sagatavošanai trūka GD. | Izmantots repozitorijā esošs PNG; šodien GD arī ieslēgts. Tests izgāja. | `769c707` | Marija (PHPUnit), 2026-10-05 |
| TEST3-001 | Pagātnes nomas sākuma datums sākotnēji tika pieņemts. | Pievienota datuma validācija; fokuss un pilnā kopa izgāja. Gala commit kolonnā atstāts `a1923af`; `e104167` bija starpposma retests. | `a1923af` | Marija (PHPUnit), 2026-10-05 |
| TEST3-002 | Negatīvas cenas un daudzuma ziņojumi bija angļu valodā. | Pievienoti latviešu validācijas ziņojumi; negatīvie ieraksti netiek saglabāti. | `0b294e5` | Marija (PHPUnit), 2026-10-05 |
| TEST3-003 | Neautentificētas API atbildes ziņojums bija angļu valodā. | Pievienota lokalizēta `401` atbilde; tests izgāja. | `1d0edb1` | Marija (PHPUnit), 2026-10-05 |
| TEST3-004 | Apgrieztu datumu ziņojums bija pretrunīgs ar vienas dienas nomu. | Precizēts ziņojums; vienāds datums tiek pieņemts, apgriezts noraidīts. | `eb2c191` | Marija (PHPUnit), 2026-10-05 |
| TEST4-INT-001 | Integrētajos testos bija novecojuši statiski pagātnes datumi. | Fixtures pārcelti uz relatīviem nākotnes datumiem; order regresija izgāja. | `TEST-4 integrācija` | Marija (PHPUnit), 2026-10-05 |

## Gala verifikācija (zars `fix/final-verification`)

| ID | Simptoms | Loma | Reproducēšana | Cēlonis | Labojums |
|---|---|---|---|---|---|
| FINAL-001 | Angļu valodas validācijas ziņojumi, piem., *The noteikumi apstiprinati field is required.*, *The selected statuss is invalid.*, *The from field is required…*; 404 ziņojums *No query results for model…* | Klients, Administrators, Viesis | `POST /api/orders` bez `noteikumi_apstiprinati`; `PATCH /api/orders/{id}/status` ar nederīgu statusu; `GET /api/tools/9999` | Nebija latviešu valodas faila `lang/lv`, lokalizācija `en` | Pievienots `backend/lang/lv/validation.php`, lokalizācija `lv`, 404 apstrādātājs `bootstrap/app.php`; pievienots regresijas tests |
| FINAL-002 | Esoša rīka rediģēšana ar fotogrāfiju neizdevās: *Laukam foto jābūt attēlam.* | Administrators | **Pārvaldība** → **Rediģēt** rīkam ar foto → **Saglabāt rīku ↗** | Rediģēšanas forma nosūtīja esošo foto ceļu kā tekstu | `frontend/src/App.jsx`: `foto` sūta tikai ja izvēlēts jauns fails |
| FINAL-003 | Administratora izvēlnēs statusi bez garumzīmēm (*Apstiprinats*, *Izpildits*, *iznomats*, *bojats*) | Administrators | **Pārvaldība** → pasūtījumu statusa izvēlne un rīka statusa lauks | Attēlota datubāzes vērtība | Pievienotas attēlojamās etiķetes (*Apstiprināts*, *Izpildīts*, *Iznomāts*, *Bojāts*); saglabātās vērtības nemainītas |
| FINAL-004 | README norādīja `php artisan serve` (ports 8000), bet frontend starpniekserveris (`vite.config.js`) izmanto 8013 — lietotne pēc README nedarbojās | Visi | Izpildīt README soļus | Dokumentācijas neatbilstība | README: `php artisan serve --port=8013` |

Šodienas Playwright izpildē jauns lietotnes defekts netika konstatēts. MySQL instalācijas exit code `1602` ir vides bloķējums, nevis lietotnes kļūda.
