
import api from "./axios-setup.js"

// User auth and related actions
export const csrfCookie = () => api.get('/sanctum/csrf-cookie')

export const login = ($credentials) => api.post('/auth/login', $credentials)

export const logout = () => api.post('/auth/logout')

export const register = ($user) => api.post('/auth/register', $user)

export const getUSer = () => api.get('/api/user')

export const verifyEmail = ($email) => api.post('/auth/forgot-password', $email)

export const resetPassword = ($password) => api.post('/auth/reset-password', $password)

export const getUserInfo = () => api.get('api/user-info')

export const getQrCode = (user) => api.post('auth/2fa-qrcode', user)