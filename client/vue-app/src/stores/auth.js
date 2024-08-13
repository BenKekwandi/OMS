import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { csrfCookie, login, getUSer, logout, register, verifyEmail, resetPassword, getUserInfo, getQrCode } from '@/http/auth'
import api from "/src/http/axios-setup.js";

export const useAuthStore = defineStore('authStore', () => {
    const user = ref(null)
    const errors = ref(null)
    const role = ref('')
    const loading = ref(false)
    const message = ref('') //used to pass a message to login page
    const userInfo = ref('')
    const qrCode = ref('')

    const test = ref(null)

    const isLoggedIn = computed(() => !!user.value)

    const fetchUser = async () => {
        try {
            const { data } = await getUSer();
            user.value = data;
            role.value = user.value.role

        } catch (error) {
            user.value = null;
        }
    }

    const handleLogin = async (credentials) => {
        await csrfCookie();
        try {
            loading.value = true
            await login(credentials)
            await fetchUser();
            loading.value = false
            errors.value = null

        }
        catch (error) {
            loading.value = false
            if (error.response)
                errors.value = error.response.data.message

        }
    }

    const handleRegister = async (newUser) => {
        try {
            await register(newUser)
            await handleLogin({
                email: newUser.email,
                password: newUser.password
            })
        } catch (error) {
            if (error.response && error.response.status === 422)
                errors.value = error.response.data.message
        }
    }

    const handleLogout = async () => {
        await logout()
        user.value = null
    }

    const handleEmailVerification = async (email) => {
        try {
            await verifyEmail({ email: email })
            errors.value = null

        } catch (error) {
            errors.value = error.response.data.message
        }

    }

    const handleResetPassword = async (password) => {
        try {
            const { data } = await resetPassword(password)
            message.value = data.status
            errors.value = null

        } catch (error) {
            errors.value = error.response.data.message
        }

    }

    //Profile Page - fetch User Information 

    const handleUserInformation = async () => {
        try {
            const { data } = await getUserInfo()
            userInfo.value = data
        } catch (error) {
            errors.value = error.response.data.message
        }
    }

    const handleQrCode = async () => {
        const object = { email: 'test2@gmail.com', password: '147258369' }
        const { data } = await getQrCode(object)
        qrCode.value = data
    }

    const testSubmit = async (object) => {
        const { data } = await api.post("/auth/2fa-pre-login", object);
        test.value = data
    }

    return { user, errors, isLoggedIn, role, message, userInfo, qrCode, loading, testSubmit, test, handleLogin, fetchUser, handleLogout, handleRegister, handleEmailVerification, handleResetPassword, handleUserInformation, handleQrCode }

})
