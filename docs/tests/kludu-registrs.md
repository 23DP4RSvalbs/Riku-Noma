# Kļūdu reģistrs

Datums: 2026-10-05

| ID | Kas bija nepareizi | Kas tika izdarīts | Rezultāts | Testeris |
|---|---|---|---|---|
| TEST2-001 | Pēc logout PHPUnit testā tokena stāvoklis tika saglabāts. | Tests tika sakārtots un tokens tiek pārbaudīts datubāzē. | Izgāja | Marija (PHPUnit) |
| TEST2-002 | Rezervācijas testā trūka noteikumu piekrišanas datu. | Testam pievienoti vajadzīgie dati. | Izgāja | Marija (PHPUnit) |
| TEST2-003 | Tests izveidoja dublētu Klients lomu. | Izmantota esoša loma. | Izgāja | Marija (PHPUnit) |
| TEST2-004 | Tas pats lomas dublikāta trūkums bija citā testā. | Izmantota esoša loma. | Izgāja | Marija (PHPUnit) |
| TEST2-005 | Kataloga tests gaidīja nepareizu atbildes formu. | Tests salāgots ar API atbildi. | Izgāja | Marija (PHPUnit) |
| TEST2-006 | Foto testam trūka GD. | Izmantots esošs PNG fails un GD ieslēgts PHP. | Izgāja | Marija (PHPUnit) |
| TEST3-001 | Pagātnes datums tika pieņemts rezervācijā. | Pievienota datuma pārbaude. | Izgāja | Marija (PHPUnit) |
| TEST3-002 | Negatīvas cenas un daudzuma kļūdas bija angļu valodā. | Pievienoti latviešu ziņojumi. | Izgāja | Marija (PHPUnit), Ksenija (UI) |
| TEST3-003 | Neielogotam lietotājam bija angļu kļūdas ziņojums. | Pievienots latviešu ziņojums. | Izgāja | Marija (PHPUnit) |
| TEST3-004 | Apgrieztu datumu ziņojums nebija skaidrs. | Ziņojums precizēts. | Izgāja | Marija (PHPUnit) |
| TEST4-INT-001 | Testā bija veci datumi, kas kļuva par pagātni. | Datumi padarīti mainīgi. | Izgāja | Marija (PHPUnit) |

## UI atradumi

| ID | Kas tika redzēts | Statuss | Testeris |
|---|---|---|---|
| UI-BV-03 | Negatīvai cenai pārlūks rādīja angļu validācijas tekstu. | Novērsts. Pēc labošanas redzams `Dienas cenai jābūt vismaz 0.` | Ksenija (UI) |
| UI-BV-04 | Negatīvam daudzumam pārlūks rādīja angļu validācijas tekstu. | Novērsts. Pēc labošanas redzams `Daudzumam jābūt vismaz 0.` | Ksenija (UI) |
| UI-ER-03 | Aizņemtu dienu nevarēja izvēlēties un nevarēja redzēt lietotnes kļūdu. | Novērsts. Pēc labošanas redzams `Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.` | Ksenija (UI) |
