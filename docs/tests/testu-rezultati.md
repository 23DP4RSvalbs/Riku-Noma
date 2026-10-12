# Rīku noma: testu rezultāti

**Izpildes datums:** 2026-10-12
**Vide:** PHP 8.3.33, Composer 2.10.3, Node.js 22.21.0, Chromium un testa datubāze.

Šis dokuments fiksē katra izpildītā testa faktisko rezultātu un vizuālo pierādījumu. Testēšanas plānotie soļi un sagaidāmie rezultāti ir [testēšanas plānā](./testa-plans.md); īss kopējais secinājums ir [testēšanas atskaitē](./testesanas-atskaite.md).

## Automatizētās un kvalitātes pārbaudes

| Pārbaude | Faktiskais rezultāts | Statuss |
|---|---|---|
| Backend PHPUnit | `php artisan test`: 25 testi, 107 apgalvojumi; visi izgāja. | Izgāja |
| Administratora pieslēgšanās | `test_seeded_administrator_can_login_with_admin_role_and_token`: 1 tests, 6 apgalvojumi. | Izgāja |
| Frontend koda kvalitāte | `npm run lint` (ESLint) pabeigts ar izejas kodu `0`. | Izgāja |
| Frontend produkcijas būve | `npm run build` (Vite) izveidoja produkcijas būvi. | Izgāja |
| Testa datubāze | `migrate:fresh --seed` izpildīts tikai izolētajā testa datubāzē. | Izgāja |

## Manuālās UI pārbaudes un ekrānuzņēmumi

Katrs zemāk piesaistītais attēls ir unikāls un atbilst tabulā norādītajam testa ID. API validāciju, HTTP statusus un datu nesaglabāšanu papildus pārbauda PHPUnit.

| ID | Faktiskais rezultāts | Statuss | Testeris | Pierādījums |
|---|---|---|---|---|
| UI-01 | Sākumlapa ielādējās; redzams virsraksts “Izīrē rīku. Padari vairāk.” un CTA “Apskatīt katalogu”. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-01.png) |
| UI-02 | Katalogā redzami meklēšanas lauks un kategoriju filtri; meklēšana `urb` izpildījās. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-02.png) |
| UI-03 | Rīka detaļās redzams “Weller WE1010 lodāmurs”, cena, statuss un nomas datumu lauki. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-03.png) |
| UI-04 | Pieslēgšanās un reģistrācijas formās redzami obligāto lauku paziņojumi. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-04.png) |
| UI-05 | Pēc pieslēgšanās ar klienta kontu redzama sadaļa “Mani pasūtījumi”. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-05.png) |
| UI-06 | Profilā redzams lietotāja sveiciens, personas datu un paroles maiņas formas. | Izgāja | Rolands, Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-06.png) |
| FT-02 | Administratoram ir pieejams rīku pārvaldības panelis ar statistiku. | Izgāja | Rolands, Ksenija | [Atvērt ekrānuzņēmumu](./screen/FT-02.png) |
| UI-08 | Lietošanas noteikumu lapā redzams rezervācijas procesa apraksts. | Izgāja | Rolands, Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-08.png) |
| UI-09-MOB | Mobilajā platumā `390 × 844` atveras izvēlne un navigācijas ieraksti ir pieejami. | Izgāja | Rolands, Ksenija | [Atvērt ekrānuzņēmumu](./screen/UI-09-MOB.png) |
| BV-01 | UI noraida 7 rakstzīmju paroli ar atbilstošu paziņojumu. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/BV-01.png) |
| BV-02 | UI noraida paroli bez vajadzīgā burtu un ciparu sastāva. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/BV-02.png) |
| BV-03 | Admina rīku pārvaldības skats ir atvērts; API tests apstiprina negatīvas cenas `422` atbildi un datu nesaglabāšanu. | Izgāja | Marija, Ksenija | [Atvērt ekrānuzņēmumu](./screen/BV-03.png) |
| BV-04 | Klienta rezervāciju skats ir atvērts; API tests apstiprina negatīva daudzuma `422` atbildi un datu nesaglabāšanu. | Izgāja | Marija, Ksenija | [Atvērt ekrānuzņēmumu](./screen/BV-04.png) |
| ER-01 | Reģistrācijas forma ir pieejama; API tests apstiprina dublēta e-pasta `422` atbildi un otrā ieraksta neizveidošanu. | Izgāja | Marija, Ksenija | [Atvērt ekrānuzņēmumu](./screen/ER-01.png) |
| ER-02 | Klientam piekļuve `/admin` tiek liegta ar paziņojumu “Šī lapa nav pieejama.” | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/ER-02.png) |
| ER-03 | Rīka detaļu skats ir pieejams; API tests apstiprina aizņemta rīka rezervācijas `422` atbildi. | Izgāja | Marija, Ksenija | [Atvērt ekrānuzņēmumu](./screen/ER-03.png) |
| ER-04 | Neautentificētam lietotājam piekļuve `/rezervacijas` tiek liegta. | Izgāja | Ksenija | [Atvērt ekrānuzņēmumu](./screen/ER-04.png) |
