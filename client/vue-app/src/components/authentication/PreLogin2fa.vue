<template>
    <v-card max-width="400" width="100%" class="pa-6 rounded" variant="elevated" elevation="6">
        <h3 class="text-h6 text-center my-6">Verify Your Account</h3>

        <div class="text-center text-body-2">
            Please complete the two-factor authentication process to securely access your account. <br>
            <br>
            Enter the verification code from Google Authenticator
        </div>
        <!-- <v-sheet class="text-center my-6">
            <qrcode-vue :value="value" :size="200" level="H" />
        </v-sheet> -->


        <v-sheet class="my-6">
            <v-otp-input v-model="otp"></v-otp-input>
        </v-sheet>

        <div class="d-flex justify-center">
            <v-btn class="my-6" color="#00ADB5" height="40" @click="handleSubmit" text="Verify" variant="flat"
                width="70%"></v-btn>
        </div>
        <div class="text-caption text-center">
            Didn't receive the code? <a href="#" @click.prevent="otp = ''">Resend</a>
        </div>
    </v-card>
</template>

<script setup>
import { ref, reactive } from 'vue';
import QrcodeVue from 'qrcode.vue'
import { useAuthStore } from "/src/stores/auth";
import api from "/src/http/axios-setup.js";
import TestLogin from './TestLogin.vue';
import { useRouter } from "vue-router";
const router = useRouter();

const store = useAuthStore();
const value = ref('BASE32ENCODEDSECRET')
const otp = ref('')
const res = ref('')

const handleSubmit = async () => {
    res.value = await api.post("auth/2fa-challenge", {
        token: store.test.
            two_factor_authentication_token, code: otp.value
    });

    if (res.value) {
        router.push({ name: "Admin" });
    }
}


</script>