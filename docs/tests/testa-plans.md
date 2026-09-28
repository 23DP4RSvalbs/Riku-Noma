# Rīku noma: testēšanas plāns

## 1. Testēšanas mērķi

Testēšanas mērķis ir pārbaudīt, ka **Rīku noma** sistēma droši un paredzami izpilda prasības visām trim lomām:


Testēšana pārbauda:


 saprotamus apstiprinājuma un kļūdas paziņojumus;

| Modulis | Pārbaudāmā funkcionalitāte |
|---|---|
| **Autentifikācija** | Reģistrācija, e-pasta unikalitāte, paroles noteikumi, pieslēgšanās, atteikšanās, tokena izmantošana un lomu piešķiršana. |
| **Katalogs** | Kategoriju un rīku saraksts, meklēšana, filtrēšana, rīka detaļas, redzamība katalogā un pieejamības informācija. |
| **Rezervācijas** | Rezervācijas izveide, daudzums, nomas sākuma un beigu datumi, pieejamības pārbaude, aizņemta rīka noraidīšana, vēsture un atcelšana. |
| **Administrēšana** | Administratora piekļuve, rīku un kategoriju CRUD, lietotāju un rezervāciju pārvaldība, statistika un dzēšanas apstiprinājumi. |
| **Datu pārvaldība** | API un datubāzes validācija, datu tipi, obligātie lauki, unikālie lauki, ārējās atslēgas, pieejamības aprēķins un kļūdu atbilžu saglabāšana. |

## 3. Testēšanas veidi

### 3.1. Funkcionālā testēšana

Pārbauda, vai funkcijas atbilstoši prasībām izpilda lietotāja darbības un API līgumu. Tā ietver visu trīs lomu galvenos scenārijus: katalogu, autentifikāciju, rezervāciju un administratora darbības.

### 3.2. Robežvērtību testēšana

Pārbauda vērtības tieši pie validācijas robežas un abās tās pusēs, piemēram, paroli ar 7 un 8 rakstzīmēm, cenu `0` un negatīvu cenu, kā arī vienādu sākuma un beigu datumu.

### 3.3. Kļūdu testēšana

Pārbauda nederīgu ievadi, nepietiekamas tiesības, neatļautus API pieprasījumus un biznesa konfliktus. Sistēmai jāatgriež kļūdas statuss, saprotams paziņojums un nedrīkst izveidot nederīgu ierakstu.

### 3.4. Regresijas testēšana

Pēc izmaiņām atkārtoti izpilda kritiskos autentifikācijas, kataloga, rezervāciju un administratora scenārijus. Īpaši pārbauda, ka izmaiņas validācijā neietekmē esošās rezervācijas un ka lomu kontrole joprojām darbojas.

### 3.5. UI testēšana

Pārbauda React saskarnes navigāciju, formu validācijas paziņojumus, ielādes un kļūdas stāvokļus, datumu izvēli, rezervācijas apstiprinājumu, administratora skatus un responsīvu attēlojumu darbvirsmā un mobilajā ierīcē.

## 4. Testēšanas vide un dati

- **Backend:** PHP 8.2+, Laravel 11 API, Laravel Sanctum.
- **Frontend:** React 18, Vite, mūsdienīga Chrome pārlūkprogramma.
- **Datubāze:** MySQL 8 ar testēšanas datubāzi.
- **Lietotāji:** viens viesis, viens parasts lietotājs, viens administrators.
- **Rīki:** vismaz viens rīks ar cenu `0`, viens rīks ar daudzumu `0`, kā arī rīks ar zināmu rezervāciju pārbaudāmajam periodam.
- **Rezultātu reģistrēšana:** testētājs aizpilda tabulas kolonnu **Rezultāts** ar faktisko uzvedību un statusu `Nokārtots` vai `Nav nokārtots`.

## 5. Testa gadījumi

| ID | Modulis | Sākuma stāvoklis | Soļi/ievade | Sagaidāmais rezultāts | Rezultāts |
|---|---|---|---|---|---|
| FT-01 | Katalogs | Sistēma ir pieejama; lietotājs nav pieslēdzies. | Atvērt kataloga lapu kā viesim; meklēt rīku un izvēlēties kategoriju. | Tiek parādīti publiski redzamie rīki, un meklēšana/filtrēšana atgriež atbilstošus rezultātus. | |
| FT-02 | Autentifikācija | E-pasts `jauns@example.com` sistēmā nav reģistrēts. | Reģistrēties ar derīgu vārdu, šo e-pastu un paroli `Riki1234`. | Lietotājs tiek izveidots ar parastā lietotāja lomu, tiek atgriezts autentifikācijas tokens un lietotājs var piekļūt savam profilam. | |
| FT-03 | Autentifikācija | Ir reģistrēts aktīvs parastais lietotājs. | Pieslēgties ar pareizu e-pastu un paroli. | Pieslēgšanās ir veiksmīga, un API atgriež derīgu tokenu; lietotājs nonāk lietotāja skatā. | |
| FT-04 | Rezervācijas | Lietotājs ir pieslēdzies; izvēlētajam rīkam ir pietiekams brīvs daudzums. | Izvēlēties rīku, daudzumu `1`, sākuma datumu šodien un beigu datumu pēc 2 dienām; iesniegt rezervāciju. | Rezervācija tiek izveidota, dati ir redzami lietotāja vēsturē un tiek parādīts apstiprinājums. | |
| FT-05 | Administrēšana | Administrators ir pieslēdzies. | Izveidot jaunu kategoriju un rīku ar derīgu nosaukumu, cenu un daudzumu; pēc tam rediģēt rīka aprakstu. | Ieraksti tiek izveidoti un izmaiņas tiek saglabātas; rīks ir redzams katalogā, ja tas ir atzīmēts kā redzams. | |
| FT-06 | Administrēšana | Administrators ir pieslēdzies; sistēmā ir rezervācija. | Atvērt administratora paneli, apskatīt rezervāciju un mainīt tās statusu. | Panelis ir pieejams, rezervācija tiek parādīta un statuss tiek saglabāts. | |
| FT-07 | Autentifikācija | E-pasts sistēmā nav reģistrēts. | Reģistrēt lietotāju ar paroli `Riki1234` (8 rakstzīmes). | Parole, kas atbilst minimālajam garumam un satur burtus un ciparus, tiek pieņemta. | |
| BV-01 | Autentifikācija | E-pasts sistēmā nav reģistrēts. | Reģistrēt lietotāju ar 7 rakstzīmju paroli `Riki123`. | Reģistrācija tiek noraidīta ar paroles validācijas kļūdu; lietotājs netiek izveidots. | |
| BV-02 | Autentifikācija | E-pasts sistēmā nav reģistrēts. | Atkārtot reģistrāciju ar paroli bez burtiem (`12345678`) un bez cipariem (`Rikikiki`). | Ievade tiek noraidīta, jo parolei jāsatur gan burti, gan cipari; lietotājs netiek izveidots. | |
| BV-03 | Katalogs / Datu pārvaldība | Administrators ir rīka izveides formā. | Izveidot vienu rīku ar cenu `0.00` un otru ar cenu `-0.01`. | Cena `0.00` tiek pieņemta; negatīva cena tiek noraidīta un ieraksts netiek saglabāts. | |
| BV-04 | Katalogs / Datu pārvaldība | Administrators ir rīka izveides formā. | Ievadīt daudzumu `0`, pēc tam daudzumu `-1`. | Daudzums `0` tiek pieņemts; negatīvs daudzums tiek noraidīts un ieraksts netiek saglabāts. | |
| BV-05 | Rezervācijas | Lietotājs ir pieslēdzies. | Izveidot rezervāciju ar sākuma datumu vakar un beigu datumu pēc 2 dienām. | Rezervācija tiek noraidīta, jo nomas sākuma datums nedrīkst būt pagātnē. | |
| BV-06 | Rezervācijas | Lietotājs ir pieslēdzies; rīks ir pieejams. | Nosūtīt rezervāciju ar beigu datumu vienādu ar sākuma datumu; pēc tam mēģināt beigu datumu iestatīt pirms sākuma datuma. | Vienādi datumi tiek pieņemti; beigu datums pirms sākuma datuma tiek noraidīts. | |
| ER-01 | Autentifikācija / Datu pārvaldība | E-pasts `lietotajs@example.com` jau eksistē. | Reģistrēt citu lietotāju ar to pašu e-pastu. | Tiek parādīts e-pasta unikalitātes kļūdas paziņojums; otrais lietotāja ieraksts netiek izveidots. | |
| ER-02 | Administrēšana / Autorizācija | Parastais lietotājs ir pieslēdzies ar derīgu tokenu. | Atvērt admina paneli vai nosūtīt pieprasījumu administratora API maršrutam. | Piekļuve tiek liegta ar `403` atbildi vai atbilstošu UI paziņojumu; administratora dati lietotājam nav pieejami. | |
| ER-03 | Rezervācijas | Lietotājs ir pieslēdzies; izvēlētā rīka viss pieejamais daudzums jau rezervēts pārklājošā periodā. | Mēģināt rezervēt šo rīku tajā pašā periodā. | Rezervācija netiek izveidota un lietotājam tiek parādīts kļūdas paziņojums par rīka nepieejamību. | |
| ER-04 | Autentifikācija | Lietotājs nav autentificēts vai tokens ir anulēts. | Nosūtīt aizsargātam API maršrutam pieprasījumu bez derīga Bearer tokena. | API atgriež `401` atbildi, aizsargātie dati netiek atgriezti un UI piedāvā pieslēgties. | |

## 6. Izpildes kritēriji

- Visi kritiskie funkcionālie un kļūdu testa gadījumi ir nokārtoti.
- Neviens negatīvs testa gadījums nerada nederīgu vai daļēji saglabātu ierakstu.
- Validācijas kļūdas ir saprotamas lietotājam un tiek attēlotas pie attiecīgā lauka vai darbības.
- Administratora maršruti ir pieejami tikai administratoram.
- Pēc labojumiem sekmīgi izpildīta regresijas pārbaude galvenajiem autentifikācijas, kataloga un rezervāciju scenārijiem.
