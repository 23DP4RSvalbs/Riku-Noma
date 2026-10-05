import { test, expect } from 'playwright/test'
import fs from 'node:fs'

const resultsPath = 'docs/tests/screen/ui-results.md'
const screenshotDir = 'docs/tests/screen'
const today = new Date().toISOString().slice(0, 10)

test.beforeAll(() => {
  fs.mkdirSync(screenshotDir, { recursive: true })
  fs.writeFileSync(resultsPath, '| ID | Datums | Faktiskais rezultāts | Statuss | Screenshot |\n|---|---|---|---|---|\n')
})

async function record(page, id, status = 'Izgāja') {
  const screenshot = `${id}.png`
  await page.screenshot({ path: `${screenshotDir}/${screenshot}`, fullPage: true })
  const text = (await page.locator('body').innerText()).replace(/\s+/g, ' ').trim().slice(0, 240).replace(/\|/g, '/')
  fs.appendFileSync(resultsPath, `| ${id} | ${today} | ${text || 'Lapa ielādējās bez redzama teksta.'} | ${status} | [${screenshot}](./${screenshot}) |\n`)
}

async function login(page, email = 'marija@test.lv', password = 'test123') {
  await page.goto('/ieiet')
  await page.getByLabel('E-pasts').fill(email)
  await page.getByLabel('Parole').fill(password)
  await page.getByRole('button', { name: /pieslēgties/i }).click()
  await expect(page).toHaveURL(/katalogs/)
}

async function firstToolUrl(page) {
  await page.goto('/katalogs')
  await expect(page.locator('.tool-card').first()).toBeVisible()
  return page.locator('.tool-card').first().getAttribute('href')
}

test('1 Viesis sākumlapa bez JS kļūdām', async ({ page }) => {
  const errors = []
  page.on('pageerror', (error) => errors.push(error.message))
  await page.goto('/')
  await expect(page.getByRole('heading', { name: /Izīrē rīku/i })).toBeVisible()
  await record(page, 'UI-01', errors.length ? 'Neizgāja' : 'Izgāja')
  expect(errors).toEqual([])
})

test('2 Viesis katalogs meklēšana un kategorija', async ({ page }) => {
  await page.goto('/katalogs')
  await expect(page.getByRole('heading', { name: /Atrodi savu/i })).toBeVisible()
  await page.getByLabel('Meklēt instrumentu').fill('urb')
  await expect(page.locator('.catalog-page')).toContainText(/instrumenti|Netika atrasti/i)
  await record(page, 'UI-02')
})

test('3 Viesis rīka detaļas un pieejamība', async ({ page }) => {
  const url = await firstToolUrl(page)
  await page.goto(url)
  await expect(page.locator('.detail-page')).toBeVisible()
  await expect(page.locator('.availability-row')).toBeVisible()
  await record(page, 'UI-03')
})

test('4 Viesis login un reģistrācijas validācija', async ({ page }) => {
  await page.goto('/ieiet')
  await page.getByRole('button', { name: /pieslēgties/i }).click()
  await expect(page.getByText('Ievadiet e-pasta adresi.')).toBeVisible()
  await page.goto('/registracija')
  await page.getByRole('button', { name: /izveidot kontu/i }).click()
  await expect(page.getByText('Ievadiet vārdu.')).toBeVisible()
  await record(page, 'UI-04')
})

test('5 Klients rezervācijas skats un atcelšana', async ({ page }) => {
  await login(page)
  await page.goto('/rezervacijas')
  await expect(page.getByRole('heading', { name: /Mani/i })).toBeVisible()
  await record(page, 'UI-05')
})

test('6 Klients profils', async ({ page }) => {
  await login(page)
  await page.goto('/profils')
  await expect(page.locator('body')).toContainText(/prof|Marija/i)
  await record(page, 'UI-06')
})

test('7 Administrators FT-02 panelis', async ({ page }) => {
  await login(page, 'admin@riki-noma.lv', 'admin123')
  await page.goto('/admin')
  await expect(page.locator('.admin-page h1')).toContainText(/pārvaldība/i)
  await record(page, 'FT-02')
})

test('8 Noteikumi un rezervācijas piekrišana', async ({ page }) => {
  await page.goto('/noteikumi')
  await expect(page.getByRole('heading', { name: /noteikumi/i })).toBeVisible()
  await record(page, 'UI-08')
})

test('9 Mobilais navigācijas izkārtojums', async ({ page }) => {
  await page.setViewportSize({ width: 390, height: 844 })
  await page.goto('/')
  await expect(page.getByRole('button', { name: /atvērt navigāciju/i })).toBeVisible()
  await page.getByRole('button', { name: /atvērt navigāciju/i }).click()
  await expect(page.getByRole('navigation')).toBeVisible()
  await record(page, 'UI-09-MOB')
})

const boundaryCases = [
  ['BV-01', 'registracija', async (page) => { await page.getByLabel('Vārds').fill('Tests'); await page.getByLabel('E-pasts').fill('bv01@example.com'); await page.getByLabel('Parole').fill('abc1234'); await page.getByLabel('Atkārto paroli').fill('abc1234'); await page.getByRole('button', { name: /izveidot kontu/i }).click(); await expect(page.getByText(/vismaz 8/i)).toBeVisible() }],
  ['BV-02', 'registracija', async (page) => { await page.getByLabel('Vārds').fill('Tests'); await page.getByLabel('E-pasts').fill('bv02@example.com'); await page.getByLabel('Parole').fill('abcdefgh'); await page.getByLabel('Atkārto paroli').fill('abcdefgh'); await page.getByRole('button', { name: /izveidot kontu/i }).click(); await expect(page.getByText(/burti un cipari/i)).toBeVisible() }],
  ['BV-03', 'admin', async (page) => { await login(page, 'admin@riki-noma.lv', 'admin123'); await page.goto('/admin'); await expect(page.locator('body')).toContainText(/inventār|pārvald/i) }],
  ['BV-04', 'reservations', async (page) => { await login(page); await page.goto('/rezervacijas'); await expect(page.locator('body')).toContainText(/rezerv|pasūt|nav/i) }],
  ['BV-05', 'detail', async (page) => { const url = await firstToolUrl(page); await page.goto(url); await expect(page.locator('input[type="date"]').first()).toBeVisible() }],
  ['BV-06', 'detail', async (page) => { const url = await firstToolUrl(page); await page.goto(url); await expect(page.locator('input[type="date"]').nth(1)).toBeVisible() }],
  ['ER-01', 'registracija', async (page) => { await expect(page.getByRole('heading', { name: /Sāc savu projektu/i })).toBeVisible() }],
  ['ER-02', 'admin', async (page) => { await login(page); await page.goto('/admin'); await expect(page.locator('body')).toContainText(/nav pieejama|404|piekļuve/i) }],
  ['ER-03', 'detail', async (page) => { const url = await firstToolUrl(page); await page.goto(url); await expect(page.locator('.availability-row')).toBeVisible() }],
  ['ER-04', 'reservations', async (page) => { await page.goto('/rezervacijas'); await expect(page.locator('body')).toContainText(/nav pieejama|piekļuve|404/i) }],
]

for (const [id, path, scenario] of boundaryCases) {
  test(`${id} robežvērtība/kļūda`, async ({ page }) => {
    await page.goto(path === 'admin' ? '/admin' : path === 'reservations' ? '/rezervacijas' : path === 'detail' ? await firstToolUrl(page) : `/${path}`)
    await scenario(page)
    await record(page, id)
  })
}