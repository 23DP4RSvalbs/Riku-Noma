# Rīku noma: testēšanas atskaite

## Datu avoti un interpretācija

Šī atskaite apkopota tikai no [testu rezultātiem](./testu-rezultati.md) un
[kļūdu reģistra](./kludu-registrs.md). Testa plānā ir 19 pamatgadījumi:
9 funkcionālie, 6 robežvērtību un 4 kļūdu gadījumi. Rezultātu dokumentā
papildus uzskaitītas 9 UI pārbaudes un 10 UI robežvērtību/kļūdu pārbaudes.
Atkārtotās izpildes ir atdalītas no sākotnējās izpildes, lai vienu un to pašu
gadījumu neskaitītu par jaunu plānoto gadījumu.

## 1. Vispārīgs kopsavilkums

| Rādītājs | Faktiskais rezultāts |
|---|---|
| Plānoti pamatgadījumi | 19: 9 funkcionāli, 6 robežvērtību, 4 kļūdu |
| Pamatgadījumu gala statuss | 18 ar izpildes pierādījumu un gala statusu **Izgāja**; 0 gala statusā **Neizgāja**; 1 **Bloķēts** (FT-02 konkrētā administratora pieslēgšanās scenārijam nav atsevišķa pierādījuma) |
| Papildu UI pārbaudes | 19: 9 lietotāja plūsmu/skatu un 10 robežvērtību/kļūdu UI pārbaudes |
| UI pārbaudes gala statuss | 0 izgāja, 0 neizgāja, 19 bloķētas pārlūka vides trūkuma dēļ |
| Sākotnējā backend kopa | 20 testi; kopsavilkumā dokumentēti 14 izgājuši un 6 neizgājuši. Detalizētajās rindās pēdējie sadalās 5 neizgājušos un 1 bloķētā GD trūkuma dēļ. |
| TEST-3 API robežvērtību/kļūdu izpilde | 15 scenāriji: 10 izgāja, 5 neizgāja |
| TEST-4 integrētā backend kopa | 24 testi, 101 assertions; visi izgāja |
| TEST-4 order regresijas kopa | 4 testi, 19 assertions; visi izgāja |
| Frontend pārbaudes | `npm run lint` un `npm run build` izgāja |
| Bloķēta MySQL 8 pārbaude | MySQL 8 / `pdo_mysql` DB izpilde nav veikta; rezultātu dokumentā minēts, ka nebija `pdo_mysql` un MySQL klienta attiecīgajā testēšanas vidē |
| Reģistrētie kļūdu ID | 11: TEST2-001–006, TEST3-001–004 un TEST4-INT-001 |
| Novērsti un atkārtoti pārbaudīti | 11 reģistrētie ID ir uzskaitīti TEST-4 integrētajā retesta tabulā; integrētā pilnā kopa izgāja |

TEST-2/TEST-3 sākotnējās kļūmes netiek dzēstas no vēstures. TEST-4 integrētā
izpilde apliecina backend regresijas kopas stāvokli pēc labojumiem, bet
neaizstāj bloķētās UI un MySQL pārbaudes.

## 2. Pilni testa izpildes rezultāti

Visu ierakstu datums ir **2026-10-02**, ja tabulā nav norādīts citādi.
Testera vērtība atspoguļo avotos norādīto lomu (`Automatizēta izpilde`,
`API` vai `Pārlūks`), nevis konkrētu personu.

### 2.1. Plāna pamatgadījumi

| Testa ID | Faktiskais rezultāts | Statuss | Testeris | Datums | Pierādījums |
|---|---|---|---|---|---|
| FT-01 | Reģistrācijas Feature tests izgāja; BV-01 8 rakstzīmju parole tika pieņemta ar `201` un izveidotu lietotāju. | Izgāja | Automatizēta izpilde / API | 2026-10-02 | `AuthTest::test_user_can_register_with_client_role_and_token`; BV-01 API atbilde un DB skaits [rezultātos](./testu-rezultati.md). |
| FT-02 | Administratora lomas middleware tests izgāja. Atsevišķa administratora pieslēgšanās ar administratora kontu rezultātu avotos nav uzrādīta. | Bloķēts — konkrētā scenārija pierādījuma nav | Automatizēta izpilde | 2026-10-02 | `AuthTest::test_role_admin_middleware_accepts_administrator_role`; nav atsevišķa administratora login rezultāta [rezultātos](./testu-rezultati.md). |
| FT-03 | Sākotnējā kataloga meklēšanas/lapošanas assertion neizgāja; TEST2-005 testa sagaidāmais paginatora JSON tika salāgots ar API atbildi. Fokusētais un integrētais retests izgāja. | Izgāja pēc retesta | Automatizēta izpilde | 2026-10-02 | `ToolApiTest::test_public_catalog_supports_search_category_and_pagination`; TEST2-005 retests un commit `7ca0f01` [kļūdu reģistrā](./kludu-registrs.md). |
| FT-04 | Kategorijas un meklēšanas filtru pārbaudes bija daļa no kataloga testa; TEST2-005 koriģēja testa meklēšanas vērtību un paginatora lauku. Fokusētais un integrētais retests izgāja. | Izgāja pēc retesta | Automatizēta izpilde | 2026-10-02 | `ToolApiTest::test_public_catalog_supports_search_category_and_pagination`; [kļūdu reģistrs](./kludu-registrs.md), commit `7ca0f01`. |
| FT-05 | Order izveides tests sākumā saņēma `422`, jo trūka obligātās noteikumu piekrišanas. TEST2-002 labojumā pievienoti piekrišanas dati; fokusētais un integrētais retests izgāja. | Izgāja pēc retesta | Automatizēta izpilde | 2026-10-02 | `OrderApiTest::test_user_can_create_order_with_total_and_dates`; TEST2-002 [kļūdu reģistrā](./kludu-registrs.md), commit `f7c70be`. |
| FT-06 | Sākotnējā testa sagatavošana apstājās uz dublētas `Klients` lomas. Pēc testa palīgmetodes labošanas pasūtījuma īpašnieka ierobežojuma/atcelšanas fokusa tests un integrētā kopa izgāja. | Izgāja pēc retesta | Automatizēta izpilde | 2026-10-02 | `OrderApiTest::test_user_can_only_see_and_cancel_own_new_orders`; TEST2-003/004 [kļūdu reģistrā](./kludu-registrs.md), commit `6d3d2ef`. |
| FT-07 | Rīka izveides, rediģēšanas, foto saglabāšanas un arhivēšanas tests sākotnēji bija bloķēts, jo trūka GD. TEST-4 izmantoja repozitorijā esošu PNG attēlu; fokusa un integrētais tests izgāja. | Izgāja pēc retesta | Automatizēta izpilde | 2026-10-02 | `ToolApiTest::test_admin_can_create_update_and_archive_tool_with_photo`; TEST2-006 [kļūdu reģistrā](./kludu-registrs.md), commit `769c707`. |
| FT-08 | Administratora rezervāciju filtrēšana un statusa maiņa izgāja. | Izgāja | Automatizēta izpilde | 2026-10-02 | `OrderApiTest::test_admin_can_filter_orders_and_update_status`; [testu rezultāti](./testu-rezultati.md). |
| FT-09 | Rīka publiskās detaļas un pieejamības testi izgāja; pieejamības aprēķins pārbaudīts arī atsevišķi un pa mēneša dienām. | Izgāja | Automatizēta izpilde | 2026-10-02 | `ToolApiTest::test_public_details_and_availability_hide_non_public_tools`, `test_availability_counts_only_overlapping_active_orders` un `test_month_availability_returns_daily_remaining_quantities`; [testu rezultāti](./testu-rezultati.md). |
| BV-01 | Parole ar 7 rakstzīmēm noraidīta ar `422`, ierakstu skaits `0->0`; parole ar 8 rakstzīmēm pieņemta ar `201`, ieraksts `0->1`. UI robeža nav pārbaudīta. | API izgāja; UI bloķēts | API / Pārlūks | 2026-10-02 | API atbildes un lietotāju DB skaiti; BV-01 UI rinda [rezultātos](./testu-rezultati.md). |
| BV-02 | Paroles `12345678` un `Rikikiki` noraidītas ar `422`, latvisku ziņojumu un bez lietotāja ieraksta. UI nav pārbaudīts. | API izgāja; UI bloķēts | API / Pārlūks | 2026-10-02 | API atbildes un lietotāju DB skaiti; BV-02 UI rinda [rezultātos](./testu-rezultati.md). |
| BV-03 | Cena `0.00` pieņemta. Negatīvā cena sākotnēji noraidīta ar `422`, bet angļu ziņojumu; pēc lokalizācijas labojuma fokusētais tests izgāja un ieraksts netika saglabāts. UI nav pārbaudīts. | Izgāja pēc retesta; UI bloķēts | API / Automatizēta izpilde / Pārlūks | 2026-10-02 | API/DB pierādījumi un TEST3-002 retests [rezultātos](./testu-rezultati.md) un [kļūdu reģistrā](./kludu-registrs.md), commit `0b294e5`. |
| BV-04 | Daudzums `0` pieņemts. Negatīvs daudzums sākotnēji noraidīts ar `422`, bet angļu ziņojumu; pēc lokalizācijas labojuma fokusētais tests izgāja bez ieraksta. UI nav pārbaudīts. | Izgāja pēc retesta; UI bloķēts | API / Automatizēta izpilde / Pārlūks | 2026-10-02 | API/DB pierādījumi un TEST3-002 retests [rezultātos](./testu-rezultati.md) un [kļūdu reģistrā](./kludu-registrs.md), commit `0b294e5`. |
| BV-05 | Pagātnes sākuma datums sākotnēji pieņemts (`201`, pasūtījums/rinda `0->1`). Pēc validācijas labojuma saņemts `422`, nav izveidots ne pasūtījums, ne rinda; šodienas vienas dienas noma pieņemta. | Izgāja pēc retesta | API / Automatizēta izpilde | 2026-10-02 | API/DB pierādījumi; `test_order_with_past_start_date_is_rejected_without_saving_records`, 1 tests/8 assertions; integrētā TEST3-001 rinda [rezultātos](./testu-rezultati.md), commit `a1923af`. |
| BV-06 | Vienāds sākuma/beigu datums pieņemts (`201`). Apgriezts periods noraidīts (`422`), ierakstu skaits nemainījās; sākotnējais kļūdas teksts bija pretrunīgs. Teksts labots un fokusētais/integrētais tests izgāja. UI nav pārbaudīts. | Izgāja pēc retesta; UI bloķēts | API / Automatizēta izpilde / Pārlūks | 2026-10-02 | API atbildes, DB skaiti un TEST3-004 retests [rezultātos](./testu-rezultati.md) un [kļūdu reģistrā](./kludu-registrs.md), commit `eb2c191`. |
| ER-01 | Dublēts e-pasts noraidīts ar `422` un latvisku ziņojumu; lietotāju ierakstu skaits nemainījās. UI paziņojums nav pārbaudīts. | API izgāja; UI bloķēts | API / Pārlūks | 2026-10-02 | API atbilde un lietotāja DB skaits [rezultātos](./testu-rezultati.md). |
| ER-02 | Autentificēts klients `GET /api/orders` saņēma `403`; pasūtījumu skaits nemainījās. UI navigācijas atteikums nav pārbaudīts. | API izgāja; UI bloķēts | API / Pārlūks | 2026-10-02 | API atbilde un DB skaits; ER-02 rinda [rezultātos](./testu-rezultati.md). |
| ER-03 | Mēģinājums rezervēt jau pilnībā aizņemtu rīku noraidīts ar `422`; pasūtījumu un rindu skaits nemainījās. UI paziņojums nav pārbaudīts. | API izgāja; UI bloķēts | API / Pārlūks | 2026-10-02 | API atbilde un `pasutijums`/`pasutijuma_riks` skaiti [rezultātos](./testu-rezultati.md). |
| ER-04 | Bez tokena `GET /api/orders` atgrieza `401`, sākotnēji ar angļu ziņojumu. Pēc API kļūdas lokalizācijas labojuma fokusētais un integrētais tests izgāja ar `401` un latvisku ziņojumu. UI nav pārbaudīts. | Izgāja pēc retesta; UI bloķēts | API / Automatizēta izpilde / Pārlūks | 2026-10-02 | API/DB pierādījumi; TEST3-003 retests [rezultātos](./testu-rezultati.md) un [kļūdu reģistrā](./kludu-registrs.md), commit `1d0edb1`. |

### 2.2. UI izpildes, kuras bija bloķētas

Visu turpmāko ierakstu datums ir 2026-10-02, testeris — `Automatizēta izpilde`
(pārlūka scenārija statuss avotā), statuss — **Bloķēts**. Kopīgais iemesls:
nav Chromium/Chrome/Firefox, Playwright vai Puppeteer. Nākamā darbība —
izpildīt attiecīgo scenāriju pārlūkā, kā norādīts [testu rezultātos](./testu-rezultati.md).

| Testa ID | Faktiskais rezultāts | Statuss | Testeris | Datums | Pierādījums / nākamā darbība |
|---|---|---|---|---|---|
| UI: viesis `/` | Sākumlapa pārlūkā netika atvērta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; palaist sākumlapu pārlūkā. |
| UI: viesis `/katalogs` | Meklēšana un kategoriju filtrs pārlūkā netika izmēģināts. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt ar sēklas datiem. |
| UI: viesis `/katalogs/{id}` | Detaļas, pieejamība un atsauksmes pārlūkā netika pārbaudītas. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; atvērt sēklas rīka detaļas. |
| UI: viesis `/ieiet`, `/registracija` | Pieslēgšanās un reģistrācijas formas pārlūkā netika pārbaudītas. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt abas formas. |
| UI: klients `/rezervacijas` | Vēsture, atcelšana, kalendārs un noteikumu piekrišana pārlūkā netika pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt ar testa klientu. |
| UI: klients `/profils` | Kontaktinformācijas un paroles maiņa pārlūkā netika pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt profila formas ar testa klientu. |
| UI: administrators `/admin` | Inventāra, pasūtījumu un lietotāju pārvaldība pārlūkā netika pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt ar administratora testa kontu. |
| UI: `/noteikumi` | Noteikumu lapa un piekrišanas mijiedarbība pārlūkā netika pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; pārbaudīt noteikumu lapu un rezervācijas formu. |
| UI: mobilā navigācija/izkārtojumi | Mobilā izvēlne un dažādu platumu izkārtojumi netika pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt plānā definētos mobilos skatus. |
| BV-01 UI | Paroles 7/8 rakstzīmju robeža lietotāja saskarnē nav pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-01 ar Chromium. |
| BV-02 UI | Paroles sastāva kļūdas paziņojumi UI nav pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-02 ar Chromium. |
| BV-03 UI | Cenas `0`/negatīvas cenas forma un kļūdas paziņojums nav pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-03 ar Chromium. |
| BV-04 UI | Daudzuma `0`/negatīva daudzuma forma un kļūdas paziņojums nav pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-04 ar Chromium. |
| BV-05 UI | Pagātnes datuma izvēle un lietotājam redzamā validācija nav pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-05 ar Chromium. |
| BV-06 UI | Vienādu/apgrieztu datumu formas uzvedība un kļūdas teksts nav pārbaudīti. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt BV-06 ar Chromium. |
| ER-01 UI | Dublēta e-pasta formas kļūdas paziņojums nav pārbaudīts. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt ER-01 ar Chromium. |
| ER-02 UI | Klienta atteikums admina lapai un UI navigācija nav pārbaudīta. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt ER-02 ar Chromium. |
| ER-03 UI | Aizņemta rīka rezervācijas kļūdas paziņojums nav pārbaudīts. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt ER-03 ar Chromium. |
| ER-04 UI | Neautentificētas piekļuves UI un pieslēgšanās piedāvājums nav pārbaudīts. | Bloķēts | Automatizēta izpilde | 2026-10-02 | Pārlūka vide nav pieejama; izpildīt ER-04 ar Chromium. |

### 2.3. Citas dokumentētās izpildes

| Izpilde | Faktiskais rezultāts | Statuss | Testeris | Datums | Pierādījums |
|---|---|---|---|---|---|
| Sākotnējā `cd backend && php artisan test` | 20 testi; kopsavilkumā 14 izgāja un 6 neizgāja. Detalizētajās rindās fiksētas TEST2-001–005 kļūmes un TEST2-006 bloķēts GD trūkuma dēļ. | Daļēji izgāja | Automatizēta izpilde | 2026-10-02 | PHPUnit izvade un konkrētie testi [rezultātos](./testu-rezultati.md). |
| TEST-3 API BV/ER cikls | 15 gadījumi; 10 izgāja, 5 neizgāja. Neizgājušās robežas/kļūdas reģistrētas kā TEST3-001–004; detalizētais sadalījums ir pamatgadījumu tabulā augstāk. | Daļēji izgāja | API | 2026-10-02 | Izolētas SQLite testa DB API atbildes un pirms/pēc ierakstu skaiti [rezultātos](./testu-rezultati.md). |
| `cd frontend && npm run lint` | Linteris izgāja. | Izgāja | Automatizēta izpilde | 2026-10-02 | Komandas rezultāts [testu rezultātos](./testu-rezultati.md). |
| `cd frontend && npm run build` | Vite izveidoja produkcijas būvējumu. | Izgāja | Automatizēta izpilde | 2026-10-02 | Komandas rezultāts [testu rezultātos](./testu-rezultati.md). |
| TEST-4 integrētā `cd backend && php artisan test` | Pēc visu reģistrēto labojumu integrēšanas 24 testi un 101 assertions izgāja. | Izgāja | Automatizēta izpilde | 2026-10-02 | Pilnā izpilde uz `test/TEST-4-integrated-fixes` [rezultātos](./testu-rezultati.md) un [kļūdu reģistrā](./kludu-registrs.md). |
| TEST-4 order datumu regresijas kopa | Derīga rezervācija, pagātnes datuma noraidījums bez ierakstiem, nepieejams krājums un apgriezti datumi; 4 testi, 19 assertions izgāja. | Izgāja | Automatizēta izpilde | 2026-10-02 | Fokusa komanda un rezultāts [testu rezultātos](./testu-rezultati.md). |
| MySQL 8 pārbaude | Netika izpildīta; testēšanas vidē trūka `pdo_mysql` un MySQL klienta, tāpēc MySQL servera versiju un DB darbību nevarēja pārbaudīt. | Bloķēts | Manuāla/vides pārbaude | 2026-10-02 | Bloķēšanas iemesls un nākamais solis [testu rezultātos](./testu-rezultati.md): izpildīt DB pārbaudi vidē ar MySQL 8 un `pdo_mysql`. |

## 3. Kļūdu kopsavilkums

Kļūdu ID un to gala risinājumi ņemti no [kļūdu reģistra](./kludu-registrs.md).
TEST-2/TEST-3 laikā lietotnes kods netika mainīts; labojumi veikti TEST-4.
Visiem ierakstiem atkārtotās pārbaudes datums ir 2026-10-02 un testeris —
`Automatizēta izpilde`.

| Kļūdas ID | Ietekme un atkārtošana | Cēlonis | Labojums | Commit / PR | Retesta rezultāts |
|---|---|---|---|---|---|
| TEST2-001 | Pēc `POST /api/logout` nākamais `GET /api/user` vienā PHPUnit instancē atgrieza `200`, nevis `401`; neatkarīgā HTTP ciklā tokena atsaukšana darbojās. | PHPUnit testa guard stāvokļa kešošana; nav reproducēta kā API kļūda. | Tests pārbauda tokena dzēšanu DB un atiestata guard pirms nākamā pieprasījuma. | `d8963b4`; PR nav izveidots. | Fokusētais un pilnais integrētais tests izgāja; HTTP cikls `200/200/401`. |
| TEST2-002 | Order testa pieprasījums bez noteikumu piekrišanas laukiem saņēma `422`, nevis sagaidīto `201`. | Testa ievade neatbilda obligātajam API piekrišanas līgumam. | Pievienoti `noteikumi_apstiprinati` un `noteikumu_versija`. | `f7c70be`; PR nav izveidots. | Fokusētais un integrētais tests izgāja. |
| TEST2-003 | Pārklājošas rezervācijas tests apstājās SQLite unikālās lomas ierobežojuma dēļ. | Testa palīgmetode katram lietotājam no jauna veidoja `Klients` lomu. | Loma tiek atrasta vai izveidota ar `firstOrCreate()`. | `6d3d2ef`; PR nav izveidots. | Fokusa tests un integrētā pilnā kopa izgāja. |
| TEST2-004 | Pasūtījumu īpašumtiesību/atcelšanas tests apstājās pirms scenārija izpildes ar unikālās lomas kļūdu. | Tā pati dublētas `Klients` lomas izveide testa palīgmetodē. | Koplietota lomas meklēšanas/izveides metode ar `firstOrCreate()`. | `6d3d2ef`; PR nav izveidots. | Abi fokusa testi un integrētā pilnā kopa izgāja. |
| TEST2-005 | Kataloga tests sagaidīja `meta.total`, kas bija `null`, un sākotnējā meklēšanas vērtība neatbilda abu fixture nosaukumiem. | Testa assertions neatbilda paginatora faktiskajai JSON struktūrai un fixture datiem. | Pārbaudīti augšējā līmeņa paginatora lauki un kopīgais meklēšanas fragments. | `7ca0f01`; PR nav izveidots. | Fokusa tests un integrētā pilnā kopa izgāja. |
| TEST2-006 | Attēla testa sagatavošana apstājās pirms API pieprasījuma. | PHP testa vidē nebija GD paplašinājuma, ko izmantoja `UploadedFile::fake()->image()`. | Izmantots repozitorijā esošs derīgs PNG fails; pakotnes un lietotnes kods nav mainīts. | `769c707`; PR nav izveidots. | Foto izveides/redigēšanas/arhivēšanas tests un integrētā pilnā kopa izgāja bez GD. |
| TEST3-001 | Rezervācija ar pagātnes sākuma datumu sākotnēji tika pieņemta (`201`) un izveidoja ierakstus. | API validēja formātu un datumu secību, bet ne to, ka sākums ir šodien vai vēlāk. | Pievienota pagātnes sākuma datuma pārbaude ar `422`; tests pārbauda, ka ieraksti netiek izveidoti. | Integrētajā atrisināšanā `a1923af`; agrākajā atsevišķajā retestā norādīts `e104167`; PR nav izveidots. | Fokusētais tests un integrētā pilnā kopa izgāja; vienas dienas noma ir derīga. |
| TEST3-002 | Negatīva cena un daudzums tika noraidīti ar `422`, bet kļūdu ziņojumi bija angliski. | `min` validācijai nebija lokalizētu ziņojumu un lauku nosaukumu. | Pievienoti latviski cenas un daudzuma kļūdu ziņojumi; Feature tests pārbauda tekstus un ierakstu neesamību. | Integrētajā atrisināšanā `0b294e5`; agrākajā atsevišķajā retestā `d0e4fe5`; PR nav izveidots. | Fokusētais un integrētais tests izgāja ar `422` un bez saglabāta rīka. |
| TEST3-003 | Neautentificēta API atbilde bija `401`, taču teksts bija “Unauthenticated.”. | Laravel noklusētais autentifikācijas izņēmuma JSON ziņojums bija angļu valodā. | API ceļiem pievienota lokalizēta `401` JSON atbilde. | Integrētajā atrisināšanā `1d0edb1`; agrākajā atsevišķajā retestā `ddb1d17`; PR nav izveidots. | Fokusētais un integrētais tests izgāja ar `401` un latvisku ziņojumu. |
| TEST3-004 | Apgriezts periods tika noraidīts, bet kļūdas teksts pretrunā ar atļautu vienas dienas rezervāciju. | Ziņojums teica, ka beigu datumam jābūt vēlākam, nevis vienādam vai vēlākam. | Precizēts paziņojums un tests pārbauda `422` un ierakstu neesamību. | Integrētajā atrisināšanā `eb2c191`; PR nav izveidots. | Fokusētais un integrētais tests izgāja. |
| TEST4-INT-001 | Integrējot pagātnes datuma validāciju, divi derīgi order testi izmantoja statiskus datumus, kas testa dienā jau bija pagātnē. | Test fixtures datumi novecoja pret testa izpildes dienu. | Pārvietoti uz relatīviem nākotnes datumiem; atjaunots apgriezto datumu tests. | Iekļauts TEST-4 integrācijas commitā; hash avotā nav norādīts; PR nav izveidots. | Order regresija: 4 testi/19 assertions; pilnā kopa: 24 testi/101 assertions — izgāja. |

## 4. Regresijas testēšana

Pēc atsevišķajiem labojumiem avotos ir norādīti šādi fokusa retesti:

- TEST2-002: 1 tests, 4 assertions — izgāja.
- TEST2-003 un TEST2-004: 2 testi, 7 assertions — izgāja.
- TEST3-001: 1 tests, 8 assertions — izgāja.
- TEST3-002: 1 tests, 6 assertions — izgāja.
- TEST3-003: 1 tests, 2 assertions — izgāja.
- TEST3-004: fokusētais un integrētais tests — izgāja.
- TEST2-005 un TEST2-006: fokusa pārbaudes — izgāja; to individuālo assertions skaits gala reģistra ierakstos nav norādīts.
- TEST4-INT-001 un datumu regresija: 4 testi, 19 assertions — izgāja.
- Pēc visu labojumu integrēšanas: pilnā backend kopa — 24 testi, 101 assertions, visi izgāja.

Sākotnējie starpposmu pilno kopu rezultāti (piemēram, 15 izgājuši un 6
neizgājuši) ir reģistrēti [rezultātos](./testu-rezultati.md) un [kļūdu
reģistrā](./kludu-registrs.md); tos neaizstāj ar gala retestu, bet gala
integrētās izpildes rezultāts ir atsevišķi uzrādīts iepriekš.

## 5. Secinājumi un prasību pārbaude

Backend API funkcionālā, robežvērtību un kļūdu pārbaude ir veikta ar
automatizētiem testiem un API pieprasījumiem. TEST-4 integrētā backend kopa un
order regresijas kopa izgāja; reģistrētajiem atradumiem ir dokumentēti
atkārtošanas soļi, cēloņi, labojumi un retesta rezultāti. Frontend lint un
produkcijas būvējums izgāja.

Tomēr avoti **nepierāda visu vērtēšanas prasību pilnīgu izpildi**:

- **Visi plānotie testi izpildīti:** nav izpildīts pilnā nozīmē. Pamatgadījumiem
  ir API/automatizācijas rezultāti, bet FT-02 atsevišķa administratora
  pieslēgšanās rezultāts nav dokumentēts. Visas 19 UI pārbaudes ir bloķētas.
- **Funkcionālā, robežvērtību un kļūdu testēšana:** API līmenī dokumentēta;
  lietotāja saskarnes daļa nav pārbaudīta. MySQL 8 pārbaude arī nav izpildīta.
- **Kļūdu dokumentēšana, labošana un retests:** izpildīts 11 reģistrētajiem
  kļūdu ID; TEST2-001 ir klasificēts kā PHPUnit guard testa izolācijas
  problēma, nevis reproducējama API kļūda.
- **Secinājumi un pierādījumi:** ir iekļauti ar norādēm uz izpildes
  dokumentāciju, PHPUnit testiem un API/DB novērojumiem. Atsevišķi
  ekrānuzņēmumu faili vai atsevišķi API pierādījumu pielikumi avotos nav
  norādīti.

Secinājums: backend pārbaudes pēc TEST-4 ir sekmīgas, bet sistēmas pilnu
atbilstību visām prasībām nevar apstiprināt, kamēr nav izpildītas pārlūka UI
pārbaudes un MySQL 8 pārbaude. Atlikušais risks ir neapstiprināta lietotāja
saskarnes uzvedība (tostarp mobilais izkārtojums un formas kļūdu paziņojumi),
administratora pieteikšanās plāna scenārijs un MySQL specifiskā datubāzes
saderība.

## 6. Pielikumi un pierādījumi

- [Testēšanas plāns](./testa-plans.md) — plānotie scenāriji un izpildes kritēriji.
- [Testu rezultāti](./testu-rezultati.md) — PHPUnit, API, UI, frontend un vides
  rezultāti; tajā dokumentētas API atbildes un datubāzes ierakstu skaiti.
- [Kļūdu reģistrs](./kludu-registrs.md) — sākotnējie atradumi, cēloņi, labojumi,
  commit atsauces un retesti.
- Ekrānuzņēmumi: avota dokumentos atsevišķu ekrānuzņēmumu failu vai saišu nav;
  UI scenāriji ir bloķēti, tāpēc ekrānuzņēmumi netiek uzrādīti.
- API pierādījumi: konkrēto API atbilžu un DB skaitu apraksti ir ievietoti
  [testu rezultātu dokumentā](./testu-rezultati.md); atsevišķi eksportēti
  pierādījumu faili avotos nav norādīti.
