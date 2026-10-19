# Rīku noma — lietotāja instrukcija

Šī instrukcija paredzēta trīs lomām: **Viesis** (bez konta), **Klients** (reģistrēts lietotājs) un **Administrators**. Visi apzīmējumi atbilst pogu un lauku nosaukumiem lietotnē.

## Saturs

1. [Viesis: katalogs, meklēšana un pieejamība](#1-viesis-katalogs-meklēšana-un-pieejamība)
2. [Klients: reģistrācija un pieslēgšanās](#2-klients-reģistrācija-un-pieslēgšanās)
3. [Klients: rīka rezervēšana](#3-klients-rīka-rezervēšana)
4. [Klients: pasūtījumu vēsture un atcelšana](#4-klients-pasūtījumu-vēsture-un-atcelšana)
5. [Klients: atsauksme pēc pabeigtas nomas](#5-klients-atsauksme-pēc-pabeigtas-nomas)
6. [Klients: profils un paroles maiņa](#6-klients-profils-un-paroles-maiņa)
7. [Administrators: rīku pārvaldība](#7-administrators-rīku-pārvaldība)
8. [Administrators: pasūtījumi un lietotāji](#8-administrators-pasūtījumi-un-lietotāji)
9. [Biežāk uzdotie jautājumi](#9-biežāk-uzdotie-jautājumi)

---

## 1. Viesis: katalogs, meklēšana un pieejamība

Konts nav vajadzīgs, lai apskatītu rīkus.

1. Augšējā izvēlnē nospied **Katalogs**.
2. Lauciņā **Meklēt instrumentu...** ieraksti rīka nosaukuma daļu (piemēram, `Bosch`). Saraksts atjaunojas automātiski.
3. Zem meklēšanas laukā izvēlies kategoriju: **Visi**, **Būvniecības tehnika**, **Dārza instrumenti**, **Elektroinstrumenti**, **Kompaktdarbnīca**, **Mērinstrumenti** vai **Tīrīšanas aprīkojums**. Aktīvā kategorija ir izcelta.
4. Ja nekas netiek atrasts, redzēsi ziņojumu **Netika atrasti instrumenti**. Nomaini meklējamo tekstu vai nospied **Visi**.
5. Nospied uz rīka kartītes. Tiks atvērta rīka lapa ar nosaukumu, kodu, aprakstu, cenu (**€ / dienā**), pieejamības atzīmi (piemēram, **Brīvs**) un citu lietotāju atsauksmēm.
6. Konkrētu pieejamību datumiem redzēsi rezervēšanas kalendārā (skat. 3. nodaļu). Lai rezervētu, vispirms jāpieslēdzas.

## 2. Klients: reģistrācija un pieslēgšanās

### Reģistrācija

1. Augšējā izvēlnē nospied **Ieiet**, tad cilni **Reģistrēties** (vai tieši **Sākt**).
2. Aizpildi laukus **Vārds**, **E-pasts**, **Parole** un **Atkārto paroli**.
   - Parolei jābūt **vismaz 8 rakstzīmēm** un jāsatur gan burti, gan cipari (piemēram, `abcd1234`). Ja parole ir īsāka, parādās ziņojums *Parolei jābūt vismaz 8 rakstzīmes garai.*
   - E-pastam jābūt derīgam, piemēram, `vards@epasts.lv`; citādi redzēsi *Ievadiet derīgu e-pasta adresi.*
   - Ja e-pasts jau ir reģistrēts, redzēsi *Šis e-pasts jau ir reģistrēts.* Pieslēdzies ar esošo kontu.
3. Nospied **Izveidot kontu ↗**. Konts tiek izveidots ar lomu **Klients** un tu uzreiz esi pieslēdzies.

### Pieslēgšanās un iziešana

1. Nospied **Ieiet**, cilnē **Pieslēgties** ieraksti **E-pasts** un **Parole**, tad nospied **Pieslēgties ↗**.
2. Ja dati ir nepareizi, redzēsi *Nepareizs e-pasts vai parole.*
3. Lai izietu, nospied **Iziet** augšējā izvēlnē.

## 3. Klients: rīka rezervēšana

1. Pieslēdzies un atver **Katalogs**, tad izvēlies rīku.
2. Rīka lapā nospied **Rezervēt rīku ↗**. Atveras lapa **Izvēlies nomas laiku**.
3. Kalendārā **Izvēlies periodu**:
   1. Ar bultiņām **←** un **→** pārslēdz mēnesi.
   2. Nospied sākuma dienu, tad beigu dienu. Izvēlētais periods tiek iekrāsots (legenda: **Pieejams**, **Izvēlētais periods**, **Nav pieejams**).
   3. Pagājušās dienas un dienas, kad rīks nav pieejams, ir izslēgtas — tās nevar izvēlēties.
4. Ja rīku ir vairāki eksemplāri, sadaļā **Aprēķins** laukā **Instrumentu daudzums** izvēlies skaitu.
5. Pārbaudi **Nomas ilgums**, **Cena dienā** un **Kopā**. Summa tiek rēķināta par katru dienu, ieskaitot sākuma un beigu dienu.
6. Atzīmē izvēles rūtiņu **Esmu iepazinies ar nomas lietošanas noteikumiem un piekrītu tiem.** Noteikumus vari izlasīt, nospiežot **Lietošanas noteikumi** lapas apakšā. Bez šīs atzīmes redzēsi *Lai turpinātu, iepazīstieties ar noteikumiem un apstipriniet piekrišanu.*
7. Nospied **Apstiprināt rezervāciju ↗**. Parādās paziņojums **Pieteikums saņemts** ar pasūtījuma numuru. Pasūtījums saņem statusu **Jauns** un gaida administratora apstiprinājumu.

Pogu **Apstiprināt rezervāciju ↗** nevar nospiest, kamēr nav izvēlēti abi datumi.

Iespējamie ziņojumi:

| Situācija | Ko redzēsi |
|---|---|
| Sākuma datums ir pagātnē | *Nomas sākuma datumam jābūt šodien vai vēlāk.* |
| Beigu datums ir pirms sākuma | *Nomas beigu datumam jābūt vienādam ar sākuma datumu vai vēlāk.* |
| Rīks šajos datumos ir aizņemts | *Šis instruments jau ir aizņemts šajos datumos* |

## 4. Klients: pasūtījumu vēsture un atcelšana

1. Augšējā izvēlnē nospied **Manas rezervācijas**. Tiek atvērta lapa **Mani pasūtījumi** ar tabulu: **Nr.**, **Rīki**, **Nomas datumi**, **Kopsumma**, **Statuss**.
2. Statusi: **Jauns** (gaida apstiprinājumu), **Apstiprināts**, **Izpildīts** (noma pabeigta), **Atcelts**.
3. Lai atceltu pasūtījumu, rindā ar statusu **Jauns** nospied **Atcelt**.
4. Logā **Atcelt pasūtījumu #…?** izvēlies **Apstiprināt atcelšanu** vai **Paturēt pasūtījumu**.
5. Pēc atcelšanas statuss kļūst **Atcelts** un rīks atkal ir pieejams citiem.

Atcelt var tikai savus pasūtījumus ar statusu **Jauns**. Apstiprinātu nomu atceļ, sazinoties ar nomas punktu.

## 5. Klients: atsauksme pēc pabeigtas nomas

1. Pēc tam, kad administrators pasūtījumu ir atzīmējis kā **Izpildīts**, atver iznomātā rīka lapu (**Katalogs** → rīks).
2. Sadaļā **Atsauksmes** parādās veidlapa **Padalies ar savu pieredzi**.
3. Sadaļā **Vērtējums** izvēlies no 1 līdz 5 zvaigznēm.
4. Laukā **Tava atsauksme** uzraksti vismaz 3 rakstzīmes (līdz 1000).
5. Nospied **Publicēt atsauksmi ↗**. Atsauksme un vidējais vērtējums parādās rīka lapā.

Par vienu rīku vari atstāt vienu atsauksmi. Pēc tam redzēsi *Paldies! Atsauksmi par šo rīku jau esi pievienojis.* Ja noma vēl nav pabeigta, redzēsi *Atsauksmi var pievienot pēc pabeigtas šī rīka nomas.*

## 6. Klients: profils un paroles maiņa

1. Nospied sava vārda burtu augšējā izvēlnē vai atver lapu **Mans konts**.
2. **Konta informācija:** nomaini **Vārds**, **E-pasts** vai **Tālrunis** un nospied **Saglabāt izmaiņas ↗**. Redzēsi *Konta informācija saglabāta.*
3. **Mainīt paroli:** ievadi **Pašreizējā parole**, **Jaunā parole** (vismaz 8 rakstzīmes, burti un cipari) un **Atkārtot jauno paroli**, tad nospied **Nomainīt paroli ↗**. Redzēsi *Parole nomainīta.* Ja pašreizējā parole nav pareiza, redzēsi *Pašreizējā parole nav pareiza.*
4. Zemāk ir saites **Skatīt pasūtījumu vēsturi ↗** un **Lietošanas noteikumi ↗**.

## 7. Administrators: rīku pārvaldība

Pieslēdzies ar administratora kontu un augšējā izvēlnē nospied **Pārvaldība**. Lapas augšā ir kopsavilkums: **Inventārā** (rīku skaits katalogā), **Aktīvie pasūtījumi** un **Apgrozījums** (neatcelto pasūtījumu summa). Nospied **Atjaunot ↻**, lai ielādētu jaunākos datus.

### Rīka pievienošana

1. Sadaļā **Rīki** nospied **Pievienot jaunu rīku**.
2. Aizpildi obligātos laukus (ar *): **Rīka nosaukums**, **Kategorija**, **Cena dienā (€)**, **Daudzums**, **Statuss** (pieejams, iznomāts, apkope, bojāts vai arhivēts).
3. Pēc izvēles: **Redzams katalogā** (**Jā** / **Nē**), **Kods**, **Zīmols**, **Min. nomas ilgums (dienas)**, **Maks. nomas ilgums (dienas)**, **Apraksts**.
4. Laukā **Foto (JPG, PNG, līdz 5 MB)** izvēlies attēla failu.
5. Nospied **Saglabāt rīku ↗**. Rīks parādās tabulā un, ja ir **Redzams** un pieejams, arī katalogā.

### Rediģēšana

1. Tabulā atrodi rīku (izmanto filtrus: nosaukums, **Kategorija**, **Cena līdz (€)**, **Daudzums no**, **Redzamība**) un nospied **Rediģēt**.
2. Izmaini vajadzīgos laukus. Ja nevēlies mainīt attēlu, lauku **Foto** atstāj tukšu — esošais attēls paliek.
3. Nospied **Saglabāt rīku ↗**.

### Arhivēšana (dzēšana)

1. Rīka rindā nospied **Dzēst**.
2. Apstiprinājuma logā nospied **Dzēst rīku**. Rīks tiek **arhivēts**: vairs nav redzams katalogā, bet vēsturiskie pasūtījumi saglabājas. Arhivēto rīku tabulā var atrast ar filtru **Redzamība** → **Slēpti**.
3. Lai atceltu darbību, nospied **Atcelt**.

## 8. Administrators: pasūtījumi un lietotāji

### Pasūtījumu statusa maiņa

1. Sadaļā **Pasūtījumi** katram pasūtījumam ir statusa izvēlne: **Jauns**, **Apstiprināts**, **Izpildīts**, **Atcelts**.
2. Izvēlies jauno statusu — tas tiek saglabāts uzreiz.
3. Parastā secība: **Jauns** → **Apstiprināts** → **Izpildīts**. Kad pasūtījums ir **Izpildīts**, klients var atstāt atsauksmi.
4. Pasūtījumu sarakstu var filtrēt ar laukiem **No**, **Līdz**, **Statuss** un pogu **Filtrēt**; **Notīrīt** atiestata filtrus.

### Lietotāju pārvaldība

1. Sadaļā **Lietotāji** laukā **Vārds, e-pasts vai tālrunis** ieraksti meklējamo tekstu. Saraksts filtrējas automātiski.
2. Slejā **Piekļuves līmenis** izvēlies **Klients** vai **Administrators**. Izmaiņa tiek saglabāta uzreiz.
3. Savu piekļuves līmeni mainīt nevar (izvēlne ir izslēgta) — tā tiek novērsta nejauša administratora tiesību zaudēšana.

Klienti nevar atvērt sadaļu **Pārvaldība**: viņi redz lapu *Šī lapa nav pieejama*. Citu lietotāju pasūtījumi klientiem nav pieejami.

## 9. Biežāk uzdotie jautājumi

**Es aizmirsu paroli. Ko darīt?**
Sistēmā nav paroles atjaunošanas saites. Sazinies ar administratoru; viņš var palīdzēt ar piekļuvi. Ja vari pieslēgties, paroli nomaini sadaļā **Mans konts** → **Mainīt paroli**.

**Kāpēc nevaru izvēlēties noteiktu datumu kalendārā?**
Datums ir pagātnē, vai arī rīks (vai izvēlētais daudzums) šajā dienā jau ir rezervēts. Pamēģini citu periodu vai mazāku daudzumu.

**Kāpēc nevaru nospiest Apstiprināt rezervāciju?**
Vēl nav izvēlēts sākuma un beigu datums, vai periodā nav pieejams pietiekams daudzums rīku.

**Kāpēc pasūtījums ir „Jauns”, nevis „Apstiprināts”?**
Katru pieprasījumu apstiprina administrators. Statusu redzi sadaļā **Manas rezervācijas**.

**Kāpēc nevaru atcelt pasūtījumu?**
Pogu **Atcelt** redz tikai pasūtījumiem ar statusu **Jauns**. Apstiprinātu nomu atceļ, sazinoties ar nomas punktu.

**Kāpēc nevaru uzrakstīt atsauksmi?**
Atsauksmi var pievienot tikai pēc tam, kad pasūtījumam ir statuss **Izpildīts**, un tikai vienu reizi par rīku.

**Rīks katalogā nav redzams.**
Tas var būt arhivēts, slēpts vai pagaidām nepieejams (statuss nav „pieejams”). Administrators to var mainīt sadaļā **Pārvaldība**.
