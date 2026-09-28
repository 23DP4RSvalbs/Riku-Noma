import { useEffect, useState } from 'react'
import { BrowserRouter, Link, NavLink, Outlet, Route, Routes, useNavigate, useParams } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext'
import { useAuth } from './context/useAuth'
import api from './services/api'
import './App.css'

const fallbackTools = [
  { id: 1, nosaukums: 'Akumulatora urbjmašīna', kategorija: 'Urbji', cena: 8, apraksts: 'Viegls un jaudīgs instruments ikdienas darbiem.', accent: 'lime' },
  { id: 2, nosaukums: 'Leņķa slīpmašīna', kategorija: 'Slīpmašīnas', cena: 12, apraksts: 'Precīziem griešanas un slīpēšanas darbiem.', accent: 'orange' },
  { id: 3, nosaukums: 'Celtniecības putekļsūcējs', kategorija: 'Tīrīšana', cena: 15, apraksts: 'Jaudīga tīrīšana pēc lieliem darbiem.', accent: 'blue' },
  { id: 4, nosaukums: 'Lāzera līmeņrādis', kategorija: 'Mērīšana', cena: 10, apraksts: 'Lai katrs mērījums būtu precīzs ar pirmo reizi.', accent: 'pink' },
]

function Layout() {
  const { user, isAdmin, logout } = useAuth()
  const navigate = useNavigate()
  return <div className="app-shell">
    <header className="topbar">
      <Link className="brand" to="/"><span className="brand-mark">RN</span><span>Rīku<br />noma</span></Link>
      <nav className="main-nav" aria-label="Galvenā navigācija">
        <NavLink to="/katalogs">Katalogs</NavLink><NavLink to="/par-mums">Par mums</NavLink>
        {user && <NavLink to="/rezervacijas">Manas rezervācijas</NavLink>}{isAdmin && <NavLink to="/admin">Pārvaldība</NavLink>}
      </nav>
      <div className="account-actions">{user ? <><Link className="avatar" to="/profils" title="Atvērt profilu">{user.vards?.slice(0, 1).toUpperCase()}</Link><button className="text-button" onClick={() => { logout(); navigate('/') }}>Iziet</button></> : <><Link className="text-button" to="/ieiet">Ieiet</Link><Link className="button button-small" to="/registracija">Sākt</Link></>}</div>
    </header><main><Outlet /></main>
    <footer><span>RĪKU NOMA © 2026</span><span>Rīga, Latvija · Izveidots meistariem</span></footer>
  </div>
}

function Home() { return <><section className="hero-section page-width"><div className="hero-copy"><p className="eyebrow">INSTRUMENTI, KAD TIE VAJADZĪGI</p><h1>Izīrē rīku.<br /><em>Padari vairāk.</em></h1><p className="hero-text">Profesionāli instrumenti bez liekiem izdevumiem. Izvēlies, rezervē un saņem tepat Rīgā.</p><Link className="button" to="/katalogs">Apskatīt katalogu <span>↗</span></Link></div><div className="hero-art"><div className="tool-illustration">↗</div><div className="art-label">LABS RĪKS<br />LABAM DARBAM</div></div></section><section className="ticker"><span>ĀTRA REZERVĀCIJA</span><span>◼</span><span>UZTICAMI RĪKI</span><span>◼</span><span>LATVIJAS MEISTARIEM</span><span>◼</span></section><section className="page-width home-catalog"><div className="section-heading"><div><p className="eyebrow">IZVĒLIES SAVU</p><h2>Populārākie rīki</h2></div><Link className="arrow-link" to="/katalogs">Viss katalogs ↗</Link></div><ToolGrid tools={fallbackTools.slice(0, 3)} /></section></> }
function getToolId(tool) { return tool.rikID || tool.id }
function getToolCategory(tool) { return tool.kategorija?.nosaukums || tool.kategorija || 'Instruments' }
function getToolImage(tool) {
  if (!tool.foto) return null
  if (tool.foto.startsWith('http')) return tool.foto
  const storageUrl = import.meta.env.VITE_STORAGE_URL || api.defaults.baseURL.replace(/\/api\/?$/, '') + '/storage'
  return `${storageUrl}/${tool.foto.replace(/^\//, '')}`
}

function ToolGrid({ tools }) {
  return <div className="tool-grid">{tools.map((tool) => {
    const imageUrl = getToolImage(tool)
    return <Link className="tool-card" to={`/katalogs/${getToolId(tool)}`} key={getToolId(tool)}>
      <div className={`tool-image ${tool.accent || 'lime'}`}>
        {imageUrl ? <img src={imageUrl} alt={tool.nosaukums} /> : <span>✦</span>}
      </div>
      <div className="tool-info"><p>{getToolCategory(tool)}</p><h3>{tool.nosaukums}</h3><strong>{tool.cenadiena ?? tool.cena} € <small>/ dienā</small></strong></div>
      <span className="card-arrow">↗</span>
    </Link>
  })}</div>
}

function Catalog() {
  const [query, setQuery] = useState('')
  const [debouncedQuery, setDebouncedQuery] = useState('')
  const [categoryId, setCategoryId] = useState('')
  const [categories, setCategories] = useState([])
  const [tools, setTools] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    const timeout = setTimeout(() => setDebouncedQuery(query.trim()), 350)
    return () => clearTimeout(timeout)
  }, [query])

  useEffect(() => {
    let active = true
    api.get('/categories').then(({ data }) => {
      if (active) setCategories(data.data || data)
    }).catch(() => {
      if (active) setError('Neizdevās ielādēt kategorijas.')
    })
    return () => { active = false }
  }, [])

  useEffect(() => {
    let active = true
    api.get('/tools', { params: { search: debouncedQuery || undefined, category_id: categoryId || undefined, per_page: 100 } })
      .then(({ data }) => {
        if (active) setTools(data.data || data)
      })
      .catch(() => {
        if (active) {
          setTools([])
          setError('Neizdevās ielādēt instrumentus. Mēģiniet vēlreiz.')
        }
      })
      .finally(() => {
        if (active) setLoading(false)
      })
    return () => { active = false }
  }, [categoryId, debouncedQuery])

  const updateQuery = (value) => {
    setQuery(value)
    setLoading(true)
    setError('')
  }
  const updateCategory = (value) => {
    setCategoryId(value)
    setLoading(true)
    setError('')
  }

  return <section className="page-width catalog-page"><div className="page-intro"><div><p className="eyebrow">INVENTĀRS / 2026</p><h1>Atrodi savu<br /><em>instrumentu.</em></h1></div><p>Viss, kas vajadzīgs nākamajam projektam.<br />Pārbaudīts un gatavs darbam.</p></div><div className="catalog-controls"><label className="search"><span>⌕</span><input value={query} onChange={(event) => updateQuery(event.target.value)} placeholder="Meklēt instrumentu..." aria-label="Meklēt instrumentu" /></label><div className="filters"><button className={!categoryId ? 'active' : ''} onClick={() => updateCategory('')}>Visi</button>{categories.map((category) => <button className={String(categoryId) === String(category.kategorijaID) ? 'active' : ''} key={category.kategorijaID} onClick={() => updateCategory(category.kategorijaID)}>{category.nosaukums}</button>)}</div></div>{error && <p className="catalog-error" role="alert">{error}</p>}{loading ? <div className="catalog-state">Ielādē instrumentus...</div> : tools.length === 0 ? <div className="catalog-state"><h2>Netika atrasti instrumenti</h2><p>Mainiet meklēšanas tekstu vai izvēlieties citu kategoriju.</p></div> : <><p className="result-count">{tools.length} instrumenti</p><ToolGrid tools={tools} /></>}</section>
}

function ToolDetail() {
  const { id } = useParams()
  const { user } = useAuth()
  const [tool, setTool] = useState(null)
  const [dates, setDates] = useState({ from: '', to: '' })
  const [availability, setAvailability] = useState(null)
  const [loading, setLoading] = useState(true)
  const [availabilityLoading, setAvailabilityLoading] = useState(false)
  const [error, setError] = useState('')

  useEffect(() => {
    let active = true
    api.get(`/tools/${id}`).then(({ data }) => {
      if (active) setTool(data)
    }).catch(() => {
      if (active) setError('Neizdevās ielādēt rīka informāciju.')
    }).finally(() => {
      if (active) setLoading(false)
    })
    return () => { active = false }
  }, [id])

  useEffect(() => {
    if (!dates.from || !dates.to || dates.to < dates.from) {
      return undefined
    }

    let active = true
    Promise.resolve().then(() => {
      if (active) setAvailabilityLoading(true)
      return api.get(`/tools/${id}/availability`, { params: dates })
    }).then(({ data }) => {
      if (active) setAvailability(data)
    }).catch(() => {
      if (active) setAvailability(null)
    }).finally(() => {
      if (active) setAvailabilityLoading(false)
    })
    return () => { active = false }
  }, [dates, id])

  if (loading) return <div className="loading">Ielādē rīku...</div>
  if (error || !tool) return <section className="page-width error-page"><h1>{error || 'Rīks nav atrasts.'}</h1><Link className="button" to="/katalogs">Atgriezties katalogā ↗</Link></section>

  const category = tool.kategorija?.nosaukums || tool.kategorija || 'Instruments'
  const imageUrl = tool.foto ? (tool.foto.startsWith('http') ? tool.foto : `${import.meta.env.VITE_STORAGE_URL || api.defaults.baseURL.replace(/\/api\/?$/, '') + '/storage'}/${tool.foto}`) : null
  const isAvailable = availability ? availability.available_quantity > 0 : tool.statuss === 'pieejams' && tool.daudzums > 0
  const reservationPath = `/rezervacijas?rikID=${tool.rikID}&nomasSakums=${dates.from}&nomasBeigums=${dates.to}`

  return <section className="page-width detail-page">
    <Link className="back-link" to="/katalogs">← Atpakaļ uz katalogu</Link>
    <div className="detail-layout">
      <div className="detail-image tool-image lime">
        {imageUrl ? <img src={imageUrl} alt={tool.nosaukums} /> : <span>✦</span>}
      </div>
      <div className="detail-copy">
        <p className="eyebrow">{category}</p>
        <h1>{tool.nosaukums}</h1>
        <div className="tool-meta"><span>{tool.zimols || 'ToolRent'}</span>{tool.kods && <span>Kods: {tool.kods}</span>}</div>
        <p>{tool.apraksts || 'Profesionāls rīks, kas gatavs nākamajam darbam.'}</p>
        <div className="price-line"><strong>{tool.cenadiena} €</strong><span>/ dienā</span></div>
        <div className="availability-row"><span className={`availability-dot ${isAvailable ? 'is-free' : 'is-busy'}`} />{isAvailable ? 'Brīvs' : 'Aizņemts'}</div>
        <div className="rental-dates">
          <label><span>Nomas sākums</span><input type="date" value={dates.from} onChange={(event) => setDates((current) => ({ ...current, from: event.target.value }))} /></label>
          <label><span>Nomas beigas</span><input type="date" min={dates.from || undefined} value={dates.to} onChange={(event) => setDates((current) => ({ ...current, to: event.target.value }))} /></label>
        </div>
        {availabilityLoading && <p className="availability-note">Pārbauda pieejamību...</p>}
        {!availabilityLoading && availability && <p className="availability-note">Pieejami {availability.available_quantity} no {availability.total_quantity} vienībām.</p>}
        <Link className={`button ${!isAvailable ? 'button-disabled' : ''}`} to={user ? reservationPath : '/ieiet'}>Rezervēt rīku <span>↗</span></Link>
      </div>
    </div>
  </section>
}

function validateAuthForm(form, isLogin) {
  const errors = {}
  const email = form.epasts.trim()

  if (!isLogin && !form.vards.trim()) errors.vards = 'Ievadiet vārdu.'
  else if (!isLogin && form.vards.trim().length > 100) errors.vards = 'Vārds nedrīkst pārsniegt 100 rakstzīmes.'

  if (!email) errors.epasts = 'Ievadiet e-pasta adresi.'
  else if (email.length > 100) errors.epasts = 'E-pasts nedrīkst pārsniegt 100 rakstzīmes.'
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.epasts = 'Ievadiet derīgu e-pasta adresi.'

  if (!form.parole) errors.parole = 'Ievadiet paroli.'
  else if (!isLogin && form.parole.length < 8) errors.parole = 'Parolei jābūt vismaz 8 rakstzīmes garai.'
  else if (!isLogin && (!/[A-Za-z]/.test(form.parole) || !/[0-9]/.test(form.parole))) errors.parole = 'Parolei jāsatur burti un cipari.'

  if (!isLogin && !form.parole_confirmation) errors.parole_confirmation = 'Atkārtoti ievadiet paroli.'
  else if (!isLogin && form.parole_confirmation !== form.parole) errors.parole_confirmation = 'Atkārtotā parole nesakrīt.'

  return errors
}

function AuthPage({ mode }) {
  const isLogin = mode === 'login'
  const { login, register } = useAuth()
  const navigate = useNavigate()
  const [form, setForm] = useState({ vards: '', epasts: '', parole: '', parole_confirmation: '' })
  const [fieldErrors, setFieldErrors] = useState({})
  const [error, setError] = useState('')
  const [busy, setBusy] = useState(false)

  const updateField = (event) => {
    const { name, value } = event.target
    setForm((current) => ({ ...current, [name]: value }))
    setFieldErrors((current) => ({ ...current, [name]: '' }))
    setError('')
  }

  const submit = async (event) => {
    event.preventDefault()
    const validationErrors = validateAuthForm(form, isLogin)
    setFieldErrors(validationErrors)
    setError('')
    if (Object.keys(validationErrors).length > 0) return

    setBusy(true)
    try {
      const credentials = { epasts: form.epasts.trim(), parole: form.parole }
      await (isLogin ? login(credentials) : register({ ...form, epasts: form.epasts.trim(), vards: form.vards.trim() }))
      navigate('/katalogs')
    } catch (requestError) {
      const serverErrors = requestError.response?.data?.errors
      if (serverErrors) {
        setFieldErrors(Object.fromEntries(Object.entries(serverErrors).map(([name, messages]) => [name, Array.isArray(messages) ? messages[0] : messages])))
      } else {
        setError(requestError.response?.data?.message || 'Neizdevās pabeigt darbību. Mēģiniet vēlreiz.')
      }
    } finally {
      setBusy(false)
    }
  }

  return <section className="auth-page">
    <div className="auth-panel">
      <p className="eyebrow">RĪKU NOMA</p>
      <div className="auth-tabs" role="tablist" aria-label="Konta piekļuve">
        <button type="button" role="tab" aria-selected={isLogin} className={isLogin ? 'active' : ''} onClick={() => navigate('/ieiet')}>Pieslēgties</button>
        <button type="button" role="tab" aria-selected={!isLogin} className={!isLogin ? 'active' : ''} onClick={() => navigate('/registracija')}>Reģistrēties</button>
      </div>
      <h1>{isLogin ? 'Prieks redzēt.' : 'Sāc savu projektu.'}</h1>
      <p>{isLogin ? 'Ieej, lai pārvaldītu rezervācijas.' : 'Izveido kontu un rezervē vajadzīgo rīku.'}</p>
      <form onSubmit={submit} noValidate>
        {!isLogin && <Field label="Vārds" name="vards" value={form.vards} error={fieldErrors.vards} onChange={updateField} autoComplete="name" />}
        <Field label="E-pasts" name="epasts" type="email" value={form.epasts} error={fieldErrors.epasts} onChange={updateField} autoComplete="email" />
        <Field label="Parole" name="parole" type="password" value={form.parole} error={fieldErrors.parole} onChange={updateField} autoComplete={isLogin ? 'current-password' : 'new-password'} />
        {!isLogin && <Field label="Atkārto paroli" name="parole_confirmation" type="password" value={form.parole_confirmation} error={fieldErrors.parole_confirmation} onChange={updateField} autoComplete="new-password" />}
        {error && <p className="form-error" role="alert">{error}</p>}
        <button className="button submit-button" disabled={busy}>{busy ? 'Apstrādā...' : isLogin ? 'Pieslēgties ↗' : 'Izveidot kontu ↗'}</button>
      </form>
    </div>
    <div className="auth-note"><span>RN</span><p>Rīki, kas palīdz<br /><em>izdarīt vairāk.</em></p></div>
  </section>
}

function Field({ label, name, type = 'text', value, error, onChange, autoComplete }) {
  const errorId = `${name}-error`
  return <label className="field"><span>{label}</span><input name={name} type={type} value={value} onChange={onChange} autoComplete={autoComplete} aria-invalid={Boolean(error)} aria-describedby={error ? errorId : undefined} />{error && <span className="field-error" id={errorId} role="alert">{error}</span>}</label>
}
function Profile() { const { user } = useAuth(); return <section className="page-width simple-page"><p className="eyebrow">MANS KONTS</p><h1>Sveiks, <em>{user?.vards}</em>.</h1><div className="profile-box"><span className="large-avatar">{user?.vards?.slice(0, 1)}</span><div><p>Vārds</p><strong>{user?.vards}</strong><p>E-pasts</p><strong>{user?.epasts}</strong></div></div></section> }
function Reservations() { return <section className="page-width simple-page"><p className="eyebrow">MANAS REZERVĀCIJAS</p><h1>Manas<br /><em>rezervācijas.</em></h1><div className="empty-state"><span>◌</span><h2>Vēl nav rezervāciju</h2><p>Atrodi rīku katalogā un sāc savu nākamo projektu.</p><Link className="button" to="/katalogs">Apskatīt katalogu ↗</Link></div></section> }
const orderStatuses = ['Jauns', 'Apstiprinats', 'Izpildits', 'Atcelts']

function formatDate(date) {
  if (!date) return '-'
  const value = new Date(date)
  if (Number.isNaN(value.getTime())) return '-'
  return value.toLocaleDateString('lv-LV')
}

function toApiDate(value) {
  if (!value) return undefined
  const [year, month, day] = value.split('-')
  return `${day}.${month}.${year}`
}

function formatMoney(value) {
  return `${Number(value || 0).toLocaleString('lv-LV', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} €`
}

async function fetchDashboard(currentFilters) {
  const params = {
    statuss: currentFilters.statuss || undefined,
    datums_no: toApiDate(currentFilters.datums_no),
    datums_lidz: toApiDate(currentFilters.datums_lidz),
  }
  const [{ data: toolResponse }, { data: orderResponse }] = await Promise.all([
    api.get('/tools', { params: { per_page: 100 } }),
    api.get('/orders', { params }),
  ])
  const { data: allOrdersResponse } = await api.get('/orders')
  return { tools: toolResponse.data || toolResponse, orders: orderResponse.data || orderResponse, allOrders: allOrdersResponse.data || allOrdersResponse }
}

function Admin() {
  const [tools, setTools] = useState([])
  const [orders, setOrders] = useState([])
  const [allOrders, setAllOrders] = useState([])
  const [filters, setFilters] = useState({ statuss: '', datums_no: '', datums_lidz: '' })
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [updatingOrder, setUpdatingOrder] = useState(null)

  const loadDashboard = async (currentFilters = filters) => {
    setLoading(true)
    setError('')
    try {
      const dashboard = await fetchDashboard(currentFilters)
      setTools(dashboard.tools)
      setOrders(dashboard.orders)
      setAllOrders(dashboard.allOrders)
    } catch (requestError) {
      setError(requestError.response?.data?.message || 'Neizdevās ielādēt pārvaldības datus.')
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchDashboard({ statuss: '', datums_no: '', datums_lidz: '' }).then((dashboard) => {
      setTools(dashboard.tools)
      setOrders(dashboard.orders)
      setAllOrders(dashboard.allOrders)
    }).catch((requestError) => {
      setError(requestError.response?.data?.message || 'Neizdevās ielādēt pārvaldības datus.')
    }).finally(() => setLoading(false))
  }, [])

  const updateFilter = (name, value) => setFilters((current) => ({ ...current, [name]: value }))
  const activeOrders = allOrders.filter((order) => !['Atcelts', 'Izpildits'].includes(order.statuss))
  const turnover = allOrders.filter((order) => order.statuss !== 'Atcelts').reduce((total, order) => total + Number(order.kopsumma || 0), 0)

  const changeStatus = async (orderId, statuss) => {
    setUpdatingOrder(orderId)
    try {
      const { data } = await api.patch(`/orders/${orderId}/status`, { statuss })
      setOrders((current) => current.map((order) => order.pasutijumsID === data.pasutijumsID ? data : order))
      setAllOrders((current) => current.map((order) => order.pasutijumsID === data.pasutijumsID ? data : order))
    } catch (requestError) {
      setError(requestError.response?.data?.message || 'Neizdevās mainīt pasūtījuma statusu.')
    } finally {
      setUpdatingOrder(null)
    }
  }

  return <section className="page-width simple-page admin-page">
    <div className="admin-heading"><div><p className="eyebrow">ADMINISTRATORA PANELIS</p><h1>Rīku<br /><em>pārvaldība.</em></h1></div><button className="button refresh-button" onClick={() => loadDashboard()} disabled={loading}>{loading ? 'Ielādē...' : 'Atjaunot ↻'}</button></div>
    <div className="admin-stats">
      <div><span>INVENTĀRĀ</span><strong>{tools.length}</strong><small>rīki katalogā</small></div>
      <div><span>AKTĪVIE PASŪTĪJUMI</span><strong>{activeOrders.length}</strong><small>neatcelti pasūtījumi</small></div>
      <div><span>APGROZĪJUMS</span><strong>{formatMoney(turnover)}</strong><small>neatcelti pasūtījumi</small></div>
    </div>
    <div className="orders-section">
      <div className="section-heading"><div><p className="eyebrow">PĒDĒJĀ AKTIVITĀTE</p><h2>Pasūtījumi</h2></div><span className="result-count">{orders.length} ieraksti</span></div>
      <div className="order-filters">
        <label><span>No</span><input type="date" value={filters.datums_no} onChange={(event) => updateFilter('datums_no', event.target.value)} /></label>
        <label><span>Līdz</span><input type="date" value={filters.datums_lidz} onChange={(event) => updateFilter('datums_lidz', event.target.value)} /></label>
        <label><span>Statuss</span><select value={filters.statuss} onChange={(event) => updateFilter('statuss', event.target.value)}><option value="">Visi statusi</option>{orderStatuses.map((status) => <option key={status} value={status}>{status}</option>)}</select></label>
        <button className="button filter-button" onClick={() => loadDashboard()} disabled={loading}>Filtrēt</button>
        <button className="clear-button" onClick={() => { const emptyFilters = { statuss: '', datums_no: '', datums_lidz: '' }; setFilters(emptyFilters); loadDashboard(emptyFilters) }} disabled={loading}>Notīrīt</button>
      </div>
      {error && <p className="form-error admin-error">{error}</p>}
      {loading ? <div className="admin-empty">Ielādē pasūtījumus...</div> : orders.length === 0 ? <div className="admin-empty">Šim filtram pasūtījumu nav.</div> : <div className="orders-table-wrap"><table className="orders-table"><thead><tr><th>Pasūtījums</th><th>Klients</th><th>Datums</th><th>Summa</th><th>Statuss</th></tr></thead><tbody>{orders.map((order) => <tr key={order.pasutijumsID}><td>#{order.pasutijumsID}</td><td>{order.lietotajs?.vards || 'Nezināms klients'}</td><td>{formatDate(order.izveidesdatums)}</td><td>{formatMoney(order.kopsumma)}</td><td><select className={`status-select status-${order.statuss?.toLowerCase()}`} value={order.statuss} onChange={(event) => changeStatus(order.pasutijumsID, event.target.value)} disabled={updatingOrder === order.pasutijumsID}>{orderStatuses.map((status) => <option key={status} value={status}>{status}</option>)}</select></td></tr>)}</tbody></table></div>}
    </div>
  </section>
}
function About() { return <section className="page-width simple-page about"><p className="eyebrow">PAR RĪKU NOMU</p><h1>Labs rīks maina<br /><em>darba sajūtu.</em></h1><p className="wide-copy">Mēs padarām profesionālus instrumentus pieejamus ikvienam Latvijas meistaram. Bez pirkšanas, bez putekļiem skapī. Tikai tas, kas vajadzīgs, tieši tad, kad vajadzīgs.</p></section> }
function ErrorPage() { return <section className="page-width error-page"><span>404</span><h1>Šī lapa nav<br /><em>pieejama.</em></h1><p>Jums nav piekļuves šai sadaļai vai lapa vairs nepastāv.</p><Link className="button" to="/">Atgriezties sākumā ↗</Link></section> }
function ProtectedRoute({ children, adminOnly = false }) { const { user, loading, isAdmin } = useAuth(); if (loading) return <div className="loading">Ielādē...</div>; if (!user || (adminOnly && !isAdmin)) return <ErrorPage />; return children }

export default function App() { return <BrowserRouter><AuthProvider><Routes><Route element={<Layout />}><Route index element={<Home />} /><Route path="katalogs" element={<Catalog />} /><Route path="katalogs/:id" element={<ToolDetail />} /><Route path="par-mums" element={<About />} /><Route path="ieiet" element={<AuthPage mode="login" />} /><Route path="registracija" element={<AuthPage mode="register" />} /><Route path="profils" element={<ProtectedRoute><Profile /></ProtectedRoute>} /><Route path="rezervacijas" element={<ProtectedRoute><Reservations /></ProtectedRoute>} /><Route path="admin" element={<ProtectedRoute adminOnly><Admin /></ProtectedRoute>} /><Route path="*" element={<ErrorPage />} /></Route></Routes></AuthProvider></BrowserRouter> }
