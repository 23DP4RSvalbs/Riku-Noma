# Kļūdu reģistrs
**Gala retesta datums:** 2026-10-12

| ID | Identificētā kļūda | Kļūdas cēlonis | Veiktā labošana | Commit | Atkārtotās testēšanas rezultāts | Statuss |
|---|---|---|---|---|---|---|
| TEST2-001 | PHPUnit guard kešoja stāvokli pēc logout; atsevišķā HTTP ciklā API kļūda nebija reproducējama. | Testa process atkārtoti izmantoja autentifikācijas guard stāvokli. | Tests atiestata guard un pārbauda tokena dzēšanu; gala PHPUnit kopa izgāja. | `d8963b4` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST2-002 | Order testa ievadē trūka noteikumu piekrišanas lauku. | Testa fixtures neatbilda rezervācijas API obligātajiem laukiem. | Pievienoti `noteikumi_apstiprinati` un `noteikumu_versija`; gala kopa izgāja. | `f7c70be` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST2-003 | Pārklāšanās testa sagatavošana izveidoja dublētu `Klients` lomu. | Katrs testa lietotājs veidoja lomu, nevis izmantoja kopīgu ierakstu. | Izmantots `firstOrCreate()`; pārklāšanās tests izgāja. | `6d3d2ef` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST2-004 | Īpašumtiesību testa sagatavošana izveidoja dublētu `Klients` lomu. | Lomas fixtures nebija koplietojams starp testa lietotājiem. | Izmantots kopīgs lomas ieraksts; ownership/cancel tests izgāja. | `6d3d2ef` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST2-005 | Kataloga tests gaidīja neatbilstošu paginatora JSON struktūru. | Assertion tika balstīts uz citu Laravel paginatora atbildes struktūru. | Assertions salāgotas ar faktisko API atbildi; kataloga tests izgāja. | `7ca0f01` | Rolands, Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST2-006 | Foto testa sagatavošanai trūka GD. | Testa vide nebija neatkarīga no GD paplašinājuma. | Izmantots repozitorijā esošs PNG; vēlāk GD tika ieslēgts. Tests izgāja. | `43fc246` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST3-001 | Pagātnes nomas sākuma datums sākotnēji tika pieņemts. | Rezervācijas validācijā nebija pārbaudes pret šodienas datumu. | Pievienota datuma validācija; fokuss un pilnā kopa izgāja. Gala commit ir `a1923af`; `e104167` bija starpposma retests. | `a1923af` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST3-002 | Negatīvas cenas un daudzuma ziņojumi bija angļu valodā. | Validācijai nebija definēti latviešu lauku ziņojumi. | Pievienoti latviešu validācijas ziņojumi; negatīvie ieraksti netiek saglabāti. | `0b294e5` | Rolands, Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST3-003 | Neautentificētas API atbildes ziņojums bija angļu valodā. | Sanctum noklusējuma `401` atbilde netika lokalizēta. | Pievienota lokalizēta `401` atbilde; tests izgāja. | `1d0edb1` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST3-004 | Apgrieztu datumu ziņojums bija pretrunīgs ar vienas dienas nomu. | Validācijas teksts neizšķīra vienādu un apgrieztu datumu gadījumu. | Precizēts ziņojums; vienāds datums tiek pieņemts, apgriezts noraidīts. | `eb2c191` | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |
| TEST4-INT-001 | Integrētajos testos bija novecojuši statiski pagātnes datumi. | Fiksētie datumi laika gaitā kļuva nederīgi. | Fixtures pārcelti uz relatīviem nākotnes datumiem; order regresija izgāja. | Nav atsevišķa | Marija (PHPUnit), 2026-10-12 — atkārtotā testēšana izgāja | Novērsta |

UI testēšanas ciklā jauni lietotnes defekti netika konstatēti.
