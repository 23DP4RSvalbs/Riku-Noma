import { test, expect } from 'playwright/test'
import fs from 'node:fs'

const resultsPath = 'docs/tests/screen/ui-results.md'
const screenDir = 'docs/tests/screen'
const today = new Date().toISOString().slice(0, 10)

test.beforeAll(() => {
  fs.mkdirSync(screenDir, { recursive: true })
  if (!fs.existsSync(resultsPath)) fs.writeFileSync(resultsPath, '| ID | Datums | Faktiskais teksts | Statuss | Screenshot |\n|---|---|---|---|---|\n')
})

async function saveResult(page, id, status = 'Izgāja', actualText = '') {
  const file = `${id}.png`
  await page.screenshot({ path: `${screenDir}/${file}`, fullPage: true })
  const text = (actualText || await page.locator('body').innerText()).replace(/\s+/g, ' ').trim().replace(/\|/g, '/').slice(0, 300)
  fs.appendFileSync(resultsPath, `| ${id} | ${today} | ${text} | ${status} | [${file}](./${file}) |\n`)
}

async function login(page, email, password) {
  await page.goto('/ieiet')
  await page.getByLabel('E-pasts').fill(email)
  await page.getByLabel('Parole').fill(password)
  await page.getByRole('button', { name: /pieslēgties/i }).click()
  await expect(page).toHaveURL(/katalogs/)
}

async function loginApi(request, email, password) {
  const response = await request.post('http://127.0.0.1:8013/api/login', { data: { epasts: email, parole: password } })
  expect(response.ok()).toBeTruthy()
  return (await response.json()).token
}

test('BV-03 admina forma noraida negatīvu cenu', async ({ page }) => {
  await login(page, 'admin@riki-noma.lv', 'admin123')
  await page.goto('/admin')
  await page.getByRole('button', { name: /pievienot jaunu rīku/i }).click()
  await page.getByLabel('Rīka nosaukums *').fill(`BV03 ${Date.now()}`)
  await page.getByLabel('Kategorija *').selectOption({ index: 1 })
  await page.getByLabel('Cena dienā (€) *').fill('-0.01')
  await page.getByLabel('Daudzums *').fill('1')
  await page.getByRole('button', { name: /saglabāt rīku/i }).click()
  const message = await page.getByRole('alert').innerText()
  expect(message).toContain('Dienas cenai jābūt vismaz 0.')
  await saveResult(page, 'BV-03', 'Izgāja', message)
})

test('BV-04 admina forma noraida negatīvu daudzumu', async ({ page }) => {
  await login(page, 'admin@riki-noma.lv', 'admin123')
  await page.goto('/admin')
  await page.getByRole('button', { name: /pievienot jaunu rīku/i }).click()
  await page.getByLabel('Rīka nosaukums *').fill(`BV04 ${Date.now()}`)
  await page.getByLabel('Kategorija *').selectOption({ index: 1 })
  await page.getByLabel('Cena dienā (€) *').fill('1')
  await page.getByLabel('Daudzums *').fill('-1')
  await page.getByRole('button', { name: /saglabāt rīku/i }).click()
  const message = await page.getByRole('alert').innerText()
  expect(message).toContain('Daudzumam jābūt vismaz 0.')
  await saveResult(page, 'BV-04', 'Izgāja', message)
})

test('ER-01 reģistrācija noraida izmantotu e-pastu', async ({ page }) => {
  await page.goto('/registracija')
  await page.getByLabel('Vārds').fill('Dublikāta pārbaude')
  await page.getByLabel('E-pasts').fill('marija@test.lv')
  await page.getByLabel('Parole').fill('Test1234')
  await page.getByLabel('Atkārto paroli').fill('Test1234')
  await page.getByRole('button', { name: /izveidot kontu/i }).click()
  await expect(page.locator('.field-error, [role="alert"]')).toContainText('Šis e-pasts jau ir reģistrēts.')
  await saveResult(page, 'ER-01')
})

test('ER-03 aizņemta rīka rezervācija parāda kļūdu', async ({ page, request }) => {
  const adminToken = await loginApi(request, 'admin@riki-noma.lv', 'admin123')
  const toolsResponse = await request.get('http://127.0.0.1:8013/api/tools?per_page=100')
  const tools = (await toolsResponse.json()).data
  const tool = tools.find((item) => Number(item.daudzums) >= 1 && item.statuss === 'pieejams')
  expect(tool).toBeTruthy()
  const start = new Date(Date.now() + 3 * 86400000)
  const end = new Date(Date.now() + 4 * 86400000)
  const format = (date) => `${String(date.getDate()).padStart(2, '0')}.${String(date.getMonth() + 1).padStart(2, '0')}.${date.getFullYear()}`
  const firstOrder = await request.post('http://127.0.0.1:8013/api/orders', {
    headers: { Authorization: `Bearer ${adminToken}` },
    data: { riki: [{ rikID: tool.rikID, daudzums: Number(tool.daudzums), nomasSakums: format(start), nomasBeigums: format(end) }], noteikumi_apstiprinati: true, noteikumu_versija: '1.0' },
  })
  if (!firstOrder.ok()) {
    expect(firstOrder.status()).toBe(422)
    expect((await firstOrder.json()).message).toBe('Šis instruments jau ir aizņemts šajos datumos')
  }
  await login(page, 'marija@test.lv', 'test123')
  await page.goto(`/rezervacijas?rikID=${tool.rikID}`)
  await expect(page.getByRole('heading', { name: /Izvēlies.*nomas laiku/i })).toBeVisible()
  await page.getByRole('button', { name: new RegExp(start.toISOString().slice(0, 10)) }).click()
  await page.getByRole('button', { name: new RegExp(end.toISOString().slice(0, 10)) }).click()
  await page.locator('.terms-check input[type="checkbox"]').check()
  await page.getByRole('button', { name: /apstiprināt rezervāciju/i }).click()
  await expect(page.getByRole('alert').filter({ hasText: 'Izvēlētajā periodā nav pieejams nepieciešamais rīku daudzums.' })).toBeVisible()
  await saveResult(page, 'ER-03')
})
