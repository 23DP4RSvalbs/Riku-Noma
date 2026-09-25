import { useEffect, useState } from 'react'
import api from '../services/api'
import { AuthContext } from './auth-context'

const TOKEN_KEY = 'riki_noma_token'
const USER_KEY = 'riki_noma_user'

function savedUser() {
  try { return JSON.parse(localStorage.getItem(USER_KEY)) } catch { return null }
}

export function AuthProvider({ children }) {
  const [user, setUser] = useState(savedUser)
  const [loading, setLoading] = useState(Boolean(localStorage.getItem(TOKEN_KEY)))

  useEffect(() => {
    const onLogout = () => { setUser(null); setLoading(false) }
    window.addEventListener('auth:logout', onLogout)
    return () => window.removeEventListener('auth:logout', onLogout)
  }, [])

  useEffect(() => {
    if (!localStorage.getItem(TOKEN_KEY)) return
    api.get('/user').then(({ data }) => {
      setUser(data)
      localStorage.setItem(USER_KEY, JSON.stringify(data))
    }).catch(() => setUser(null)).finally(() => setLoading(false))
  }, [])

  const login = async (credentials) => {
    const { data } = await api.post('/login', credentials)
    localStorage.setItem(TOKEN_KEY, data.token)
    localStorage.setItem(USER_KEY, JSON.stringify(data.user))
    setUser(data.user)
    return data.user
  }

  const register = async (details) => {
    const { data } = await api.post('/register', details)
    localStorage.setItem(TOKEN_KEY, data.token)
    localStorage.setItem(USER_KEY, JSON.stringify(data.user))
    setUser(data.user)
    return data.user
  }

  const logout = async () => {
    try { await api.post('/logout') } catch { /* expired token is already logged out */ }
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    setUser(null)
  }

  const isAdmin = user?.lomas?.some((role) => role.nosaukums?.toLowerCase().includes('admin'))
  return <AuthContext.Provider value={{ user, loading, isAdmin, login, register, logout }}>{children}</AuthContext.Provider>
}

