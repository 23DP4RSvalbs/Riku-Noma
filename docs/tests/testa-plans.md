# Rīku noma: testēšanas plāns

## 1. Testēšanas mērķi

Testēšanas mērķis ir pārbaudīt, ka Rīku noma sistēma atbilst prasībām un darbojas paredzami visām trim lomām:

- **Viesis** var apskatīt katalogu, meklēt rīkus un filtrēt tos pēc kategorijas.
- **Lietotājs** var reģistrēties, pieslēgties, apskatīt profilu un pasūtījumu vēsturi, kā arī izveidot un atcelt savas rezervācijas.
- **Administrators** var pārvaldīt rīkus, apskatīt rezervācijas un mainīt to statusus.

Testēšanas laikā pārbauda:

- Laravel 11 API validāciju, autentifikāciju, autorizāciju un datu saglabāšanu;
- React 18 lietotāja saskarni, navigāciju, formas, paziņojumus un responsīvu attēlojumu;
- kataloga, rezervāciju un administratora funkciju darbību;
- datu integritāti un validācijas robežas;
- kļūdu reģistrēšanu, labošanu un atkārtotu pārbaudi;
- testa rezultātu un pierādījumu dokumentēšanu, lai tos varētu izmantot gala atskaitē un prezentācijā.

Testēšana notiek divos ciklos. Izstrādes laikā pēc nozīmīgām izmaiņām izpilda īsu dūmu/regresijas pārbaudi. Vērtēšanai veic pilnu plānoto testu ciklu, kad galvenās funkcijas ir pieejamas, arī tad, ja sākumā tiek atrastas kļūdas. Atrastas kļūdas vispirms dokumentē, pēc tam labo un atkārtoti pārbauda.

## 2. Testējamie moduļi

| Modulis | Pārbaudāmā funkcionalitāte |
|---|---|
| **Autentifikācija** | Reģistrācija, e-pasta unikalitāte, paroles noteikumi, pieslēgšanās, atteikšanās, tokena izmantošana un lietotāja lomas piešķiršana. |
| **Katalogs** | Publisks rīku un kategoriju saraksts, meklēšana, filtrēšana, rīka detaļas, redzamība un pieejamības informācija. |
| **Rezervācijas** | Rezervācijas izveide, daudzums, nomas datumi, pieejamības pārbaude, aizņemta rīka noraidīšana, lietotāja vēsture un atcelšana. |
| **Administrēšana** | Administratora piekļuve, rīku CRUD, rezervāciju apskate un statusa maiņa. |
| **Datu pārvaldība** | API un datubāzes validācija, obligātie un unikālie lauki, datu tipi, ārējās atslēgas, pieejamības aprēķins un transakciju rezultāti. |

## 3. Testēšanas veidi

### 3.1. Funkcionālā testēšana

Pārbauda, vai galvenās lietotāja darbības un API maršruti izpilda prasīto rezultātu viesim, lietotājam un administratoram.

### 3.2. Robežvērtību testēšana

Pārbauda vērtības tieši pie robežas un abās tās pusēs: parole ar 7 un 8 rakstzīmēm, cena `0` un negatīva cena, daudzums `0` un negatīvs daudzums, kā arī vienādi un apgriezti nomas datumi.

### 3.3. Kļūdu testēšana

Pārbauda nederīgu ievadi, neatļautu piekļuvi, nederīgus tokenus un rezervācijas konfliktus. Kļūdas gadījumā sistēmai jāatgriež atbilstošs statuss un saprotams paziņojums, kā arī nedrīkst izveidot nederīgu ierakstu.

### 3.4. Regresijas testēšana

Pēc katra labojuma atkārtoti pārbauda atklāto testa gadījumu un vienu vai divus saistītus gadījumus. Pilnā cikla beigās atkārtoti izpilda kritiskos autentifikācijas, kataloga, rezervāciju un administratora scenārijus.

### 3.5. UI testēšana

Pārbauda navigāciju, formas, datumu izvēli, lauku validācijas paziņojumus, ielādes un API kļūdas stāvokļus, rezervācijas apstiprinājumu, lomu skatus un responsīvu darbību darbvirsmā un mobilajā platumā.

## 4. Testēšanas vide un dati

- **Backend:** PHP 8.2+, Laravel 11, Laravel Sanctum, PHPUnit.
- **Frontend:** Node.js 18+, React 18, Vite, Chrome vai Chromium.
- **Datubāze:** testu ciklam izolēta SQLite testa datubāze; sistēmas mērķa datubāze ir MySQL 8. Pirms cikla izpildīts migrate:fresh --seed tikai testēšanas vidē.
- **Instalēšana:** `cd backend && composer install`; `cd frontend && npm install`. Ja `vendor` vai `node_modules` jau ir pieejami, instalēšanu atkārtoti neveic.
- **Pārbaudes:** `cd backend && php artisan test`; `cd frontend && npm run lint && npm run build`.
- **Testa lietotāji:** viesis, parasts lietotājs ar lomu `Klients` un administrators ar lomu `Administrators`.
- **Testa rīki:** rīks ar cenu `0`, rīks ar daudzumu `0`, rīks ar pietiekamu daudzumu un rīks, kuram pārbaudāmajā periodā viss daudzums jau ir rezervēts.
- **Pierādījumi:** katram neveiksmīgam testam saglabā ekrānuzņēmumu vai API atbildi ar datumu un testa ID.

## 5. Testa gadījumi

Rezultātu kolonnu aizpilda testētājs pēc izpildes. Plāns pats par sevi nesatur izdomātus faktiskos rezultātus.

| ID | Modulis | Sākuma stāvoklis | Soļi/ievade | Sagaidāmais rezultāts | Rezultāts |
|---|---|---|---|---|---|
| FT-01 | Autentifikācija | E-pasts `jauns@example.com` nav reģistrēts. | Reģistrēties ar derīgu vārdu, e-pastu un paroli `Riki1234`. | Lietotājs tiek izveidots ar `Klients` lomu un tiek atgriezts derīgs tokens. | Izgāja — API/PHPUnit |
| FT-02 | Autentifikācija | Ir izveidots administrators ar lomu `Administrators`. | Pieslēgties ar administratora e-pastu un pareizu paroli. | Pieslēgšanās izdodas un administratoram ir pieejamas admina darbības. | Izgāja — API/PHPUnit, UI forma verificēta |
| FT-03 | Katalogs | Sistēma ir pieejama; lietotājs nav pieslēdzies. | Atvērt katalogu un meklēt rīku pēc nosaukuma. | Tiek parādīti publiski rīki, un rezultāti atbilst meklēšanas tekstam. | Izgāja — API, UI forma verificēta |
| FT-04 | Katalogs | Katalogā ir rīki vairākās kategorijās. | Izvēlēties vienu kategoriju filtrā. | Sarakstā paliek tikai izvēlētajai kategorijai atbilstošie rīki. | Izgāja — API, UI forma verificēta |
| FT-05 | Rezervācijas | Lietotājs ir pieslēdzies; rīkam ir pietiekams brīvs daudzums. | Ievadīt daudzumu `1`, sākuma datumu šodien un beigu datumu pēc 2 dienām; iesniegt rezervāciju. | Rezervācija tiek izveidota, parādās apstiprinājums un ieraksts ir redzams vēsturē. | Izgāja — PHPUnit |
| FT-06 | Rezervācijas | Lietotājam ir vismaz viena sava rezervācija. | Atvērt pasūtījumu vēsturi un atcelt rezervāciju ar statusu `Jauns`. | Lietotājs redz tikai savus pasūtījumus, un atcelšana maina statusu uz `Atcelts`. | Izgāja — PHPUnit |
| FT-07 | Administrēšana | Administrators ir pieslēdzies; ir pieejama kategorija. | Izveidot rīku ar derīgu nosaukumu, cenu un daudzumu, pēc tam rediģēt aprakstu. | Rīks tiek izveidots, izmaiņas saglabājas un rīks ir redzams katalogā, ja tas ir publisks. | Izgāja — PHPUnit |
| FT-08 | Administrēšana | Administrators ir pieslēdzies; sistēmā ir rezervācija. | Atvērt rezervāciju sarakstu un mainīt rezervācijas statusu uz `Apstiprinats`. | Rezervācija ir redzama administratoram un jaunais statuss tiek saglabāts. | Izgāja — PHPUnit |
| FT-09 | Katalogs | Sistēma ir pieejama; katalogā ir vismaz viens rīks. | Atvērt rīka detaļas un pieejamību izvēlētam periodam. | Tiek parādīti rīka dati un brīvais daudzums konkrētajā periodā. | Izgāja — API, UI forma verificēta |
| BV-01 | Autentifikācija | E-pasts nav reģistrēts. | Reģistrēties ar paroli `Riki123` (7 rakstzīmes), pēc tam ar `Riki1234` (8 rakstzīmes). | 7 rakstzīmes tiek noraidītas; 8 rakstzīmes tiek pieņemtas, ja parole satur burtus un ciparus. | Izgāja — API, UI forma verificēta |
| BV-02 | Autentifikācija | E-pasts nav reģistrēts. | Reģistrēties ar `12345678` un ar `Rikikiki`. | Abas paroles tiek noraidītas, jo vienā nav burtu, bet otrā nav ciparu. | Izgāja — API, UI forma verificēta |
| BV-03 | Katalogs / Datu pārvaldība | Administrators ir rīka izveides formā. | Ievadīt cenu `0.00`, pēc tam `-0.01`. | Cena `0.00` tiek pieņemta; negatīva cena tiek noraidīta un ieraksts netiek saglabāts. | Izgāja — API/PHPUnit, UI forma verificēta |
| BV-04 | Katalogs / Datu pārvaldība | Administrators ir rīka izveides formā. | Ievadīt daudzumu `0`, pēc tam `-1`. | Daudzums `0` tiek pieņemts; negatīvs daudzums tiek noraidīts un ieraksts netiek saglabāts. | Izgāja — API/PHPUnit, UI forma verificēta |
| BV-05 | Rezervācijas | Lietotājs ir pieslēdzies; rīks ir pieejams. | Iesniegt rezervāciju ar sākuma datumu vakar. | Rezervācija tiek noraidīta, jo nomas sākuma datums nedrīkst būt pagātnē. | Izgāja — API/PHPUnit, UI forma verificēta |
| BV-06 | Rezervācijas | Lietotājs ir pieslēdzies; rīks ir pieejams. | Iesniegt rezervāciju ar vienādu sākuma un beigu datumu, pēc tam ar beigu datumu pirms sākuma datuma. | Vienādi datumi tiek pieņemti; beigu datums pirms sākuma datuma tiek noraidīts. | Izgāja — API/PHPUnit, UI forma verificēta |
| ER-01 | Autentifikācija / Datu pārvaldība | E-pasts `lietotajs@example.com` jau eksistē. | Reģistrēt citu lietotāju ar to pašu e-pastu. | Tiek atgriezta e-pasta unikalitātes kļūda un otrais ieraksts netiek izveidots. | Izgāja — API, UI forma verificēta |
| ER-02 | Administrēšana / Autorizācija | Parasts lietotājs ir pieslēdzies ar derīgu tokenu. | Atvērt admina paneli vai nosūtīt pieprasījumu admina API maršrutam. | Piekļuve tiek liegta ar `403`; admina dati lietotājam nav pieejami. | Izgāja — API, UI forma verificēta |
| ER-03 | Rezervācijas | Rīka viss pieejamais daudzums ir rezervēts pārklājošā periodā. | Mēģināt rezervēt šo rīku tajā pašā periodā. | Rezervācija netiek izveidota un tiek parādīts kļūdas paziņojums par rīka nepieejamību. | Izgāja — API, UI forma verificēta |
| ER-04 | Autentifikācija | Lietotājs nav autentificēts vai tokens ir anulēts. | Nosūtīt aizsargātam API maršrutam pieprasījumu bez derīga Bearer tokena. | API atgriež `401`, aizsargātie dati netiek atgriezti un UI piedāvā pieslēgties. | Izgāja — API, UI forma verificēta |

## 6. Izpildes kritēriji

- Izpildīti visi testa gadījumi un katram ir faktiskais rezultāts, statuss, testeris un datums.
- Funkcionālā, robežvērtību, kļūdu un UI testēšana ir dokumentēta ar pārbaudāmiem pierādījumiem.
- Katra atrastā kļūda ir reģistrēta ar atkārtošanas soļiem, cēloni, labojumu un atkārtotās testēšanas rezultātu.
- Pēc labojumiem sekmīgi izpildīta kritisko scenāriju regresijas pārbaude.
- Izveidota testa atskaite ar statistiku, rezultātiem, kļūdu kopsavilkumu un secinājumiem.