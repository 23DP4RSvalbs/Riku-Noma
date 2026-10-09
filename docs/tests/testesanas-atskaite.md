# Testēšanas atskaite

Datums: 2026-10-09

## Kopsavilkums

| Rādītājs | Faktiskais rezultāts | Statuss |
|---|---|---|
| Testa plāns | Plānā ir funkcionālie, robežvērtību un kļūdu testi. | Izgāja |
| PHPUnit ar SQLite | 25 testi, 107 pārbaudes. | Izgāja |
| PHPUnit ar MySQL 8.4.3 | 25 testi, 107 pārbaudes. | Izgāja |
| FT-02 | Administrators saņēma tokenu un lomu `Administrators`. | Izgāja |
| UI BV-03 | Pārlūks rādīja angļu validācijas tekstu. | Neizgāja |
| UI BV-04 | Pārlūks rādīja angļu validācijas tekstu. | Neizgāja |
| UI ER-01 | Dublēts e-pasts tika noraidīts ar latviešu tekstu. | Izgāja |
| UI ER-03 | Aizņemtu dienu nevarēja izvēlēties un rezervācijas poga bija atspējota. | Neizgāja |
| Kļūdu retesti | Iepriekš atrastās kļūdas tika pārbaudītas atkārtoti. | Izgāja |

## Izmantotie dati

Admina konts: `admin@riki-noma.lv` / `admin123`

Klienta konts: `marija@test.lv` / `test123`

Testa datubāze: MySQL `8.4.3`, `riki_noma_test`.

## Secinājumi

Backend testi izgāja gan ar SQLite, gan ar MySQL 8.4.3. FT-02 tests izgāja.

ER-01 UI pārbaude izgāja.

BV-03 un BV-04 neizgāja, jo pārlūks rādīja angļu tekstu `Value must be greater than or equal to 0.`.

ER-03 neizgāja, jo aizņemtu dienu nevarēja izvēlēties un forma neļāva iesniegt rezervāciju.

Šie trīs UI rezultāti ir atradumi, nevis izdomāti panākumi. Tie ir redzami [UI rezultātu tabulā](./screen/ui-results.md).

## Pierādījumi

- [Testa plāns](./testa-plans.md)
- [Testu rezultāti](./testu-rezultati.md)
- [UI rezultāti](./screen/ui-results.md)
- [Kļūdu reģistrs](./kludu-registrs.md)
