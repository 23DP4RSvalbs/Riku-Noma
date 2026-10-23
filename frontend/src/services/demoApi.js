// Static in-browser API for GitHub Pages; state persists in localStorage.
const DB_KEY = 'riki_noma_demo_db_v1'

const iso = (d) => d.toISOString().slice(0, 10)
const addDays = (n) => { const d = new Date(); d.setHours(12, 0, 0, 0); d.setDate(d.getDate() + n); return d }

const ROLES = { adm: { lomasID: 1, nosaukums: 'Administrators' }, cli: { lomasID: 2, nosaukums: 'Klients' } }

const CATEGORIES = [
  ['Elektroinstrumenti', 'Urbji, zāģi, slīpmašīnas'],
  ['Dārza instrumenti', 'Pļāvēji, trimmeri, zaru zāģi'],
  ['Būvniecības tehnika', 'Betona maisītāji, perforatori'],
  ['Mērinstrumenti', 'Lāzera līmeņi, tālmēri'],
  ['Tīrīšanas aprīkojums', 'Putekļsūcēji, mazgāšanas iekārtas'],
  ['Kompaktdarbnīca', '3D printeri, lodāmuri'],
].map(([nosaukums, apraksts], i) => ({ kategorijaID: i + 1, nosaukums, apraksts }))

const TOOLS = [
  [1, 'Bosch GSB 18V urbis', 'Akumulatora urbis ar 2 baterijām un lādētāju', 12.5, 3, 1, 7, 'bosch-gsb-18v.jpg', 'EL-001', 'Bosch'],
  [1, 'Makita 5008MG ripzāģis', 'Diska zāģis 1800W ar 210mm disku', 15, 2, 1, 5, 'makita-5008mg.jpg', 'EL-002', 'Makita'],
  [1, 'DeWalt DWE4257 slīpmašīna', 'Leņķa slīpmašīna 125mm, 900W', 10, 4, 1, 3, 'dewalt-dwe4257.jpg', 'EL-003', 'DeWalt'],
  [2, 'Husqvarna 130 zāles pļāvējs', 'Benzīna zāles pļāvējs, 38.2cc', 18, 2, 1, 3, 'husqvarna-130.jpg', 'DA-001', 'Husqvarna'],
  [2, 'Stihl FS 55 trimmeris', 'Benzīna trimmeris ar auklas galvu', 14, 3, 1, 3, 'stihl-fs55.jpg', 'DA-002', 'Stihl'],
  [3, 'Betona maisītājs 140L', 'Elektriskais betona maisītājs, 140 litri', 20, 2, 1, 7, 'betona-maisitajs-140l.jpg', 'BT-001', 'Altrad'],
  [3, 'Hilti TE 30 perforators', 'SDS-Plus perforators ar 1050W motoru', 22, 1, 1, 7, 'hilti-te30.jpg', 'BT-002', 'Hilti'],
  [4, 'Bosch GLL 3-80 lāzera līmenis', 'Zaļais lāzera līmenis, 3 plaknes, 80m', 16, 2, 1, 14, 'bosch-gll380.jpg', 'ME-001', 'Bosch'],
  [4, 'Leica DISTO D2 tālmērs', 'Lāzera attāluma mērītājs līdz 60m', 8, 3, 1, 14, 'leica-disto-d2.jpg', 'ME-002', 'Leica'],
  [5, 'Kärcher K5 spiediena mazgātājs', 'Augstspiediena mazgātājs, 145 bar', 17, 2, 1, 3, 'karcher-k5.jpg', 'TI-001', 'Kärcher'],
  [5, 'Nilfisk putekļsūcējs', 'Rūpnieciskais putekļsūcējs, 30L tvertne', 9, 3, 1, 3, 'nilfisk-multi.jpg', 'TI-002', 'Nilfisk'],
  [6, 'Creality Ender 3 3D printeris', 'FDM 3D printeris, 220x220x250mm drukas lauks', 25, 1, 1, 7, 'creality-ender3.jpg', 'KD-001', 'Creality'],
  [6, 'Weller WE1010 lodāmurs', 'Lodāmurs ar temperatūras kontroli 70W', 7, 4, 1, 7, 'weller-we1010.jpg', 'KD-002', 'Weller'],
].map(([kategorijaID, nosaukums, apraksts, cenadiena, daudzums, nomasilgumsmin, nomasilgumsmax, foto, kods, zimols], i) => ({
  rikID: i + 1, kategorijaID, nosaukums, apraksts, daudzums, statuss: 'pieejams', cenadiena,
  nomasilgumsmin, nomasilgumsmax, foto: `riki/${foto}`, kods, zimols, redzamsKatalogs: true,
}))

function seed() {
  const users = [
    { lietotajsID: 1, vards: 'Rolands Admin', epasts: 'admin@riki-noma.lv', parole: 'admin123', telefons: '+37120000001', lomas: [ROLES.adm] },
    { lietotajsID: 2, vards: 'Marija Teste', epasts: 'marija@test.lv', parole: 'test123', telefons: '+37120000002', lomas: [ROLES.cli] },
    { lietotajsID: 3, vards: 'Ksenija Teste', epasts: 'ksenija@test.lv', parole: 'test123', telefons: '+37120000003', lomas: [ROLES.cli] },
  ]
  const specs = [
    [2, 'Izpildits', 'EL-001', 1, -30, 3], [2, 'Izpildits', 'DA-001', 1, -20, 2], [3, 'Izpildits', 'EL-002', 1, -15, 2],
    [2, 'Apstiprinats', 'BT-002', 1, 2, 3], [3, 'Apstiprinats', 'ME-001', 2, 5, 4], [2, 'Jauns', 'KD-001', 1, 7, 5],
    [3, 'Jauns', 'TI-001', 1, 9, 2], [3, 'Atcelts', 'DA-002', 1, 4, 2],
  ]
  const orders = specs.map(([lietotajID, statuss, kods, qty, offset, len], i) => {
    const tool = TOOLS.find((t) => t.kods === kods)
    return {
      pasutijumsID: i + 1, lietotajID, statuss, kopsumma: (tool.cenadiena * qty * len).toFixed(2),
      izveidesdatums: iso(addDays(offset - 3)), noteikumu_versija: '1.0',
      items: [{ rikID: tool.rikID, daudzums_pozicija: qty, nomassakums: iso(addDays(offset)), nomasbeigums: iso(addDays(offset + len - 1)) }],
    }
  })
  const reviews = [
    [2, 'EL-001', 5, 'Ļoti labs urbis, baterijas pietika visai dienai.'],
    [2, 'DA-001', 4, 'Pļāvējs strādāja bez problēmām, nedaudz skaļš.'],
    [3, 'EL-002', 5, 'Zāģis ass un viegli lietojams. Iesaku.'],
  ].map(([lietotajID, kods, vertejums, teksts], i) => ({
    atsauksmeID: i + 1, rikID: TOOLS.find((t) => t.kods === kods).rikID, lietotajID, vertejums, teksts, created_at: new Date().toISOString(),
  }))
  return { tools: TOOLS, users, orders, reviews, nextIds: { tool: 14, user: 4, order: 9, review: 4 } }
}

function loadDb() {
  try { const raw = localStorage.getItem(DB_KEY); if (raw) return JSON.parse(raw) } catch { /* reseed */ }
  const db = seed()
  saveDb(db)
  return db
}
const saveDb = (db) => localStorage.setItem(DB_KEY, JSON.stringify(db))

class HttpError extends Error {
  constructor(status, data) { super(data?.message || `HTTP ${status}`); this.status = status; this.data = data }
}
const fail = (status, message, errors) => { throw new HttpError(status, errors ? { message, errors } : { message }) }

const publicUser = ({ parole, ...u }) => { void parole; return u }
const isAdminUser = (u) => !!u?.lomas.some((r) => r.nosaukums === 'Administrators')
const withCategory = (db, tool) => ({ ...tool, kategorija: CATEGORIES.find((c) => c.kategorijaID === tool.kategorijaID) })
const orderView = (db, o, withUser) => {
  const { items, ...rest } = o
  const view = {
    ...rest,
    riki: items.map((it) => ({ ...db.tools.find((t) => t.rikID === it.rikID), pivot: { ...it } })),
  }
  if (withUser) { const u = db.users.find((x) => x.lietotajsID === o.lietotajID); view.lietotajs = u && publicUser(u) }
  return view
}
const INACTIVE = ['atcelts', 'izpildits', 'izpildīts']
const reservedOn = (db, rikID, from, to) => db.orders
  .filter((o) => !INACTIVE.includes(o.statuss.toLowerCase()))
  .flatMap((o) => o.items)
  .filter((it) => it.rikID === rikID && it.nomassakums <= to && it.nomasbeigums >= from)
  .reduce((sum, it) => sum + it.daudzums_pozicija, 0)

function parseDmy(s) {
  const m = /^(\d{2})\.(\d{2})\.(\d{4})$/.exec(s || '')
  return m ? `${m[3]}-${m[2]}-${m[1]}` : null
}
function eachDay(from, to) {
  const days = []
  for (let d = new Date(`${from}T12:00:00`); iso(d) <= to; d.setDate(d.getDate() + 1)) days.push(iso(d))
  return days
}

function formToObject(body) {
  if (typeof FormData !== 'undefined' && body instanceof FormData) {
    const out = {}
    body.forEach((v, k) => { if (!(v instanceof File)) out[k] = v })
    return out
  }
  if (typeof body === 'string') { try { return JSON.parse(body) } catch { return {} } }
  return body || {}
}

const TOOL_NUMERIC = ['kategorijaID', 'daudzums', 'nomasilgumsmin', 'nomasilgumsmax']

function handle(method, path, params, body, token) {
  const db = loadDb()
  const session = token && token.startsWith('demo-') ? db.users.find((u) => u.lietotajsID === Number(token.slice(5))) : null
  const me = () => session || fail(401, 'Unauthenticated.')
  const admin = () => { if (!isAdminUser(me())) fail(403, 'Nav piekļuves.') }
  let m

  if (method === 'post' && path === '/login') {
    const u = db.users.find((x) => x.epasts === body.epasts)
    if (!u || u.parole !== body.parole) fail(401, 'Nepareizs e-pasts vai parole.')
    return { message: 'Veiksmīga pieslēgšanās.', user: publicUser(u), token: `demo-${u.lietotajsID}` }
  }
  if (method === 'post' && path === '/register') {
    const errors = {}
    if (!body.vards) errors.vards = ['Lauks vārds ir obligāts.']
    if (!/^\S+@\S+\.\S+$/.test(body.epasts || '')) errors.epasts = ['Ievadiet derīgu e-pasta adresi.']
    else if (db.users.some((u) => u.epasts === body.epasts)) errors.epasts = ['Šis e-pasts jau ir reģistrēts.']
    if (!body.parole || body.parole.length < 8) errors.parole = ['Parolei jābūt vismaz 8 rakstzīmes garai.']
    else if (!/[A-Za-z]/.test(body.parole) || !/[0-9]/.test(body.parole)) errors.parole = ['Parolei jāsatur burti un cipari.']
    const confirm = body.parole_confirmation ?? body.paroles_atkartojums
    if (confirm !== body.parole) errors.parole_confirmation = ['Atkārtotā parole nesakrīt.']
    if (Object.keys(errors).length) fail(422, 'Dati nav derīgi.', errors)
    const u = { lietotajsID: db.nextIds.user++, vards: body.vards, epasts: body.epasts, parole: body.parole, telefons: body.telefons || null, lomas: [ROLES.cli] }
    db.users.push(u); saveDb(db)
    return { message: 'Lietotājs veiksmīgi reģistrēts.', user: publicUser(u), token: `demo-${u.lietotajsID}` }
  }
  if (method === 'post' && path === '/logout') return { message: 'Veiksmīgi atvienojāties.' }
  if (method === 'get' && path === '/user') return publicUser(me())
  if (method === 'patch' && path === '/user/profile') {
    const u = me()
    const errors = {}
    if (!body.vards) errors.vards = ['Lauks vārds ir obligāts.']
    if (db.users.some((x) => x.epasts === body.epasts && x !== u)) errors.epasts = ['Šis e-pasts jau ir reģistrēts.']
    if (body.parole) {
      if (body.parole_veca !== u.parole) errors.parole_veca = ['Pašreizējā parole nav pareiza.']
      else if (body.parole.length < 8 || !/[A-Za-z]/.test(body.parole) || !/[0-9]/.test(body.parole)) errors.parole = ['Parolei jābūt vismaz 8 rakstzīmēm ar burtiem un cipariem.']
      else if (body.parole_confirmation !== body.parole) errors.parole_confirmation = ['Atkārtotā parole nesakrīt.']
    }
    if (Object.keys(errors).length) fail(422, Object.values(errors)[0][0], errors)
    Object.assign(u, { vards: body.vards, epasts: body.epasts, telefons: body.telefons || null })
    if (body.parole) u.parole = body.parole
    saveDb(db)
    return publicUser(u)
  }

  if (method === 'get' && path === '/categories') return [...CATEGORIES].sort((a, b) => a.nosaukums.localeCompare(b.nosaukums, 'lv'))

  if (method === 'get' && path === '/tools') {
    const isAdm = isAdminUser(session)
    let list = db.tools.filter((t) => isAdm || (t.redzamsKatalogs && t.statuss === 'pieejams'))
    if (params?.search) list = list.filter((t) => t.nosaukums.toLowerCase().includes(String(params.search).toLowerCase()))
    if (params?.category_id) list = list.filter((t) => t.kategorijaID === Number(params.category_id))
    list = list.sort((a, b) => b.rikID - a.rikID)
    const perPage = Number(params?.per_page) || 15
    const page = Number(params?.page) || 1
    return {
      current_page: page, data: list.slice((page - 1) * perPage, page * perPage).map((t) => withCategory(db, t)),
      per_page: perPage, total: list.length, last_page: Math.max(1, Math.ceil(list.length / perPage)),
    }
  }
  if (method === 'post' && path === '/tools') {
    admin()
    const data = formToObject(body)
    TOOL_NUMERIC.forEach((k) => { if (data[k] !== undefined && data[k] !== '') data[k] = Number(data[k]) })
    if (data.cenadiena !== undefined) data.cenadiena = Number(data.cenadiena)
    if (data.redzamsKatalogs !== undefined) data.redzamsKatalogs = ['1', 'true', true, 1].includes(data.redzamsKatalogs)
    const tool = { redzamsKatalogs: true, foto: null, ...data, rikID: db.nextIds.tool++ }
    db.tools.push(tool); saveDb(db)
    return withCategory(db, tool)
  }

  if ((m = /^\/tools\/(\d+)(?:\/(availability|reviews|review-eligibility))?$/.exec(path))) {
    const id = Number(m[1])
    const tool = db.tools.find((t) => t.rikID === id)
    if (!tool) fail(404, 'Rīks nav atrasts.')
    const sub = m[2]
    const visible = isAdminUser(session) || (tool.redzamsKatalogs && tool.statuss === 'pieejams')

    if (method === 'get' && !sub) { if (!visible) fail(404, 'Rīks nav pieejams katalogā.'); return withCategory(db, tool) }
    if (method === 'patch' && !sub) {
      admin()
      const data = formToObject(body)
      TOOL_NUMERIC.forEach((k) => { if (data[k] !== undefined && data[k] !== '') data[k] = Number(data[k]) })
      if (data.cenadiena !== undefined) data.cenadiena = Number(data.cenadiena)
      if (data.redzamsKatalogs !== undefined) data.redzamsKatalogs = ['1', 'true', true, 1].includes(data.redzamsKatalogs)
      Object.assign(tool, data); saveDb(db)
      return withCategory(db, tool)
    }
    if (method === 'delete' && !sub) {
      admin()
      const mode = params?.dzeshanas_modelis || params?.modelis || body?.dzeshanas_modelis || body?.modelis || 'arhivet'
      if (mode === 'dzest') { db.tools = db.tools.filter((t) => t !== tool); saveDb(db); return { message: 'Rīks dzēsts.' } }
      Object.assign(tool, { redzamsKatalogs: false, statuss: 'arhivets' }); saveDb(db)
      return { message: 'Rīks arhivēts.', tool }
    }
    if (method === 'get' && sub === 'availability') {
      if (!visible) fail(404, 'Rīks nav pieejams katalogā.')
      if (params?.month) {
        const [y, mo] = params.month.split('-').map(Number)
        const from = `${params.month}-01`
        const to = iso(new Date(Date.UTC(y, mo, 0)))
        const days = {}
        eachDay(from, to).forEach((day) => {
          const reserved = reservedOn(db, id, day, day)
          days[day] = { reserved_quantity: reserved, available_quantity: Math.max(0, tool.daudzums - reserved) }
        })
        return { tool_id: id, month: params.month, total_quantity: tool.daudzums, days }
      }
      const reserved = reservedOn(db, id, params.from, params.to)
      return { tool_id: id, from: params.from, to: params.to, total_quantity: tool.daudzums, reserved_quantity: reserved, available_quantity: Math.max(0, tool.daudzums - reserved) }
    }
    if (method === 'get' && sub === 'reviews') {
      const list = db.reviews.filter((r) => r.rikID === id).sort((a, b) => b.atsauksmeID - a.atsauksmeID)
      const avg = list.length ? Math.round((list.reduce((s, r) => s + r.vertejums, 0) / list.length) * 10) / 10 : 0
      return {
        average_rating: avg, review_count: list.length,
        reviews: list.map((r) => ({ ...r, lietotajs: { lietotajsID: r.lietotajID, vards: db.users.find((u) => u.lietotajsID === r.lietotajID)?.vards } })),
      }
    }
    if (sub === 'review-eligibility' || (method === 'post' && sub === 'reviews')) {
      const u = me()
      const completed = db.orders.some((o) => o.lietotajID === u.lietotajsID && o.statuss === 'Izpildits' && o.items.some((it) => it.rikID === id))
      const reviewed = db.reviews.some((r) => r.rikID === id && r.lietotajID === u.lietotajsID)
      if (method === 'get') return { has_completed_rental: completed, has_reviewed: reviewed, can_review: completed && !reviewed }
      if (!completed) fail(403, 'Atsauksmi var pievienot pēc pabeigtas šī rīka nomas.')
      if (reviewed) fail(409, 'Par šo rīku atsauksme jau ir pievienota.')
      const v = Number(body.vertejums)
      if (!(v >= 1 && v <= 5)) fail(422, 'Vērtējumam jābūt no 1 līdz 5 zvaigznēm.', { vertejums: ['Vērtējumam jābūt no 1 līdz 5 zvaigznēm.'] })
      if (!body.teksts || body.teksts.length < 3) fail(422, 'Atsauksmei jābūt vismaz 3 rakstzīmes garai.', { teksts: ['Atsauksmei jābūt vismaz 3 rakstzīmes garai.'] })
      const review = { atsauksmeID: db.nextIds.review++, rikID: id, lietotajID: u.lietotajsID, vertejums: v, teksts: body.teksts, created_at: new Date().toISOString() }
      db.reviews.push(review); saveDb(db)
      return { ...review, lietotajs: { lietotajsID: u.lietotajsID, vards: u.vards } }
    }
  }

  if (method === 'post' && path === '/orders') {
    const u = me()
    if (!Array.isArray(body.riki) || !body.riki.length) fail(422, 'Jānorāda vismaz viens rīks.')
    if (!body.noteikumi_apstiprinati) fail(422, 'Lai nosūtītu rezervāciju, jāpiekrīt nomas noteikumiem.')
    const today = iso(new Date())
    let total = 0
    const items = body.riki.map((it) => {
      const tool = db.tools.find((t) => t.rikID === Number(it.rikID)) || fail(422, 'Rīks nav atrasts.')
      const start = parseDmy(it.nomasSakums)
      const end = parseDmy(it.nomasBeigums)
      if (!start || !end) fail(422, 'Datumam jābūt DD.MM.YYYY formātā.')
      if (start < today) fail(422, 'Nomas sākuma datumam jābūt šodien vai vēlāk.')
      if (end < start) fail(422, 'Nomas beigu datumam jābūt vienādam ar sākuma datumu vai vēlāk.')
      const qty = Number(it.daudzums)
      if (!(qty >= 1)) fail(422, 'Daudzumam jābūt lielākam par 0.')
      if (reservedOn(db, tool.rikID, start, end) + qty > tool.daudzums) fail(422, 'Šis instruments jau ir aizņemts šajos datumos')
      total += tool.cenadiena * eachDay(start, end).length * qty
      return { rikID: tool.rikID, daudzums_pozicija: qty, nomassakums: start, nomasbeigums: end }
    })
    const order = { pasutijumsID: db.nextIds.order++, lietotajID: u.lietotajsID, statuss: 'Jauns', kopsumma: total.toFixed(2), izveidesdatums: today, noteikumu_versija: '1.0', items }
    db.orders.push(order); saveDb(db)
    return orderView(db, order)
  }
  if (method === 'get' && path === '/my-orders') {
    const u = me()
    return db.orders.filter((o) => o.lietotajID === u.lietotajsID).sort((a, b) => b.pasutijumsID - a.pasutijumsID).map((o) => orderView(db, o))
  }
  if (method === 'get' && path === '/orders') {
    admin()
    return [...db.orders].sort((a, b) => b.pasutijumsID - a.pasutijumsID).filter((o) => !params?.statuss || o.statuss === params.statuss).map((o) => orderView(db, o, true))
  }
  if ((m = /^\/orders\/(\d+)\/cancel$/.exec(path)) && method === 'post') {
    const u = me()
    const order = db.orders.find((o) => o.pasutijumsID === Number(m[1])) || fail(404, 'Pasūtījums nav atrasts.')
    if (order.lietotajID !== u.lietotajsID) fail(403, 'Jums nav tiesību atcelt šo pasūtījumu.')
    if (order.statuss !== 'Jauns') fail(422, 'Atcelt var tikai pasūtījumu ar statusu "Jauns".')
    order.statuss = 'Atcelts'; saveDb(db)
    return orderView(db, order)
  }
  if ((m = /^\/orders\/(\d+)\/status$/.exec(path)) && method === 'patch') {
    admin()
    const order = db.orders.find((o) => o.pasutijumsID === Number(m[1])) || fail(404, 'Pasūtījums nav atrasts.')
    if (!['Jauns', 'Apstiprinats', 'Izpildits', 'Atcelts'].includes(body.statuss)) fail(422, 'Nederīgs statuss.')
    order.statuss = body.statuss; saveDb(db)
    return orderView(db, order, true)
  }

  if (method === 'get' && path === '/users') {
    admin()
    const q = (params?.search || '').toLowerCase()
    return [...db.users].reverse()
      .filter((u) => !q || [u.vards, u.epasts, u.telefons].some((v) => (v || '').toLowerCase().includes(q)))
      .map((u) => ({ ...publicUser(u), pasutijumi_count: db.orders.filter((o) => o.lietotajID === u.lietotajsID).length }))
  }
  if ((m = /^\/users\/(\d+)\/role$/.exec(path)) && method === 'patch') {
    admin()
    const u = db.users.find((x) => x.lietotajsID === Number(m[1])) || fail(404, 'Lietotājs nav atrasts.')
    if (u === session) fail(422, 'Jūs nevarat mainīt savu piekļuves līmeni.')
    if (isAdminUser(u) && body.role === 'Klients' && db.users.filter(isAdminUser).length <= 1) fail(422, 'Sistēmā jāpaliek vismaz vienam administratoram.')
    u.lomas = [body.role === 'Administrators' ? ROLES.adm : ROLES.cli]; saveDb(db)
    return { ...publicUser(u), pasutijumi_count: db.orders.filter((o) => o.lietotajID === u.lietotajsID).length }
  }

  return fail(404, 'Not found.')
}

export function demoAdapter(config) {
  const base = (config.baseURL || '').replace(/\/$/, '')
  const path = (config.url || '').replace(base, '').split('?')[0]
  const method = (config.method || 'get').toLowerCase()
  const header = config.headers?.Authorization || config.headers?.get?.('Authorization') || ''
  const token = String(header).replace(/^Bearer /, '')
  let body = formToObject(config.data)
  let params = config.params
  if (method === 'post' && body?._method) { body = { ...body }; delete body._method }
  const override = formToObject(config.data)._method
  const effective = override ? override.toLowerCase() : method
  const response = (status, data) => ({ data, status, statusText: String(status), headers: {}, config, request: {} })
  return new Promise((resolve, reject) => {
    try {
      resolve(response(200, JSON.parse(JSON.stringify(handle(effective, path, params, body, token)))))
    } catch (e) {
      if (!(e instanceof HttpError)) throw e
      const error = new Error(e.message)
      error.config = config
      error.isAxiosError = true
      error.response = response(e.status, e.data)
      reject(error)
    }
  })
}
