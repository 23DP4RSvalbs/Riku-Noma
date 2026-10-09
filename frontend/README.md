# Rīku noma — tīmekļa saskarne

React lietotne izmanto Laravel API, lai rādītu katalogu, pārbaudītu rīku pieejamību un pārvaldītu rezervācijas.

## Palaišana lokāli

```bash
npm install
npm run dev
```

Pēc noklusējuma Vite pārsūta `/api` pieprasījumus uz `http://127.0.0.1:8000`. Pirms lietotnes palaišanas startē Laravel API no `backend` mapes. Pilna datubāzes un backend uzstādīšana aprakstīta repozitorija galvenajā [README](../README.md).

Ja API darbojas citā adresē, izveido `frontend/.env.local` un norādi:

```dotenv
VITE_API_URL=http://localhost:8000/api
```

Ja rīku attēli tiek glabāti citā publiskā adresē, papildus norādi `VITE_STORAGE_URL`. Pretējā gadījumā lietotne attēlus ielādē no Laravel publiskās krātuves.

## Komandas

- `npm run dev` — izstrādes serveris
- `npm run build` — produkcijas būvējums mapē `dist`
- `npm run lint` — ESLint pārbaude

## Testu attēli

[Atvērt klikšķināmo testu attēlu galeriju](../README.md#testēšanas-pierādījumi)
