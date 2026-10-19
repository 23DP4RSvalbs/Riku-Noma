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

Šodienas Playwright izpildē jauns lietotnes defekts netika konstatēts. MySQL instalācijas exit code `1602` ir vides bloķējums, nevis lietotnes kļūda.
