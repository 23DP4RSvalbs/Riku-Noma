# Kļūdu reģistrs

Datums: 2026-10-05

| ID | Kas bija nepareizi | Kas tika izdarīts | Rezultāts |
|---|---|---|---|
| TEST2-001 | Pēc logout PHPUnit testā tokena stāvoklis tika saglabāts. | Tests tika sakārtots un tokens tiek pārbaudīts datubāzē. | Izgāja |
| TEST2-002 | Rezervācijas testā trūka noteikumu piekrišanas datu. | Testam pievienoti vajadzīgie dati. | Izgāja |
| TEST2-003 | Tests izveidoja dublētu Klients lomu. | Izmantota esoša loma. | Izgāja |
| TEST2-004 | Tas pats lomas dublikāta trūkums bija citā testā. | Izmantota esoša loma. | Izgāja |
| TEST2-005 | Kataloga tests gaidīja nepareizu atbildes formu. | Tests salāgots ar API atbildi. | Izgāja |
| TEST2-006 | Foto testam trūka GD. | Izmantots esošs PNG fails un GD ieslēgts PHP. | Izgāja |
| TEST3-001 | Pagātnes datums tika pieņemts rezervācijā. | Pievienota datuma pārbaude. | Izgāja |
| TEST3-002 | Negatīvas cenas un daudzuma kļūdas bija angļu valodā. | Pievienoti latviešu ziņojumi. | Izgāja |
| TEST3-003 | Neielogotam lietotājam bija angļu kļūdas ziņojums. | Pievienots latviešu ziņojums. | Izgāja |
| TEST3-004 | Apgrieztu datumu ziņojums nebija skaidrs. | Ziņojums precizēts. | Izgāja |
| TEST4-INT-001 | Testā bija veci datumi, kas kļuva par pagātni. | Datumi padarīti mainīgi. | Izgāja |

Šajā reizē jauna lietotnes kļūda netika reģistrēta.

MySQL instalācijas kļūda 1602 ir vides problēma. Tā nav lietotnes kļūda.

## UI atradumi

| ID | Kas tika redzēts | Statuss |
|---|---|---|
| UI-BV-03 | Negatīvai cenai pārlūks rādīja `Value must be greater than or equal to 0.` angļu valodā. | Atvērts |
| UI-BV-04 | Negatīvam daudzumam pārlūks rādīja `Value must be greater than or equal to 0.` angļu valodā. | Atvērts |
| UI-ER-03 | Aizņemtu dienu nevarēja izvēlēties. Rezervācijas poga bija atspējota. | Atvērts |
