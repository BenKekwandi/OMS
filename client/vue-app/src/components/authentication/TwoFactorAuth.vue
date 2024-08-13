<!-- <template>
    
    <v-card max-width="400" width="100%" class="pa-6 rounded" variant="elevated" elevation="6">
        <h3 class="text-h6 text-center my-6">Verify Your Account</h3>

        <div class="text-center text-body-2">
            Please complete the two-factor authentication process to securely access your account. <br>
            <br>
            Enter the verification code from Google Authenticator
        </div>
        <v-sheet class="text-center my-6">
            <qrcode-vue :value="qrCode" :size="200" level="H" />
        </v-sheet>


        <v-sheet class="my-6">
            <v-otp-input v-model="otp"></v-otp-input>
        </v-sheet>

        <div class="d-flex justify-center">
            <v-btn class="my-6" color="#00ADB5" height="40" text="Verify" variant="flat" width="70%"></v-btn>
        </div>
        <div class="text-caption text-center">
            Didn't receive the code? <a href="#" @click.prevent="otp = ''">Resend</a>
        </div>
    </v-card>
</template> -->


<style scoped>
#bg-rp {
    background-image: url('../assets/img/background.svg');
    background-color: #071d35;
    background-size: cover;
    background-attachment: fixed !important;
    background-position: center;
    background-repeat: no-repeat;

}
</style>

<template>
    <div>
        <v-app>
            <v-row id="bg-rp" class="d-flex justify-center align-center">

                <v-card max-width="400" width="100%" class="pa-6 py-15 rounded" variant="elevated" elevation="6">
                    <h3 class="text-h6 text-center my-6">Verify Your Account</h3>

                    <!-- <div class="text-center text-body-2">
                        Please complete the two-factor authentication process to securely access your account. <br>
                        <br>
                        Enter the verification code from Google Authenticator
                    </div> -->
                    <v-sheet class="text-center my-6">
                        <qrcode-vue :value="qrCode.url" :size="200" level="M" />
                    </v-sheet>

                    <!-- 
                    <v-sheet class="my-6">
                        <v-otp-input v-model="otp"></v-otp-input>
                    </v-sheet>

                    <div class="d-flex justify-center">
                        <v-btn class="my-6" color="#00ADB5" height="40" text="Verify" variant="flat"
                            width="70%"></v-btn>
                    </div>
                    <div class="text-caption text-center">
                        Didn't receive the code? <a href="#" @click.prevent="otp = ''">Resend</a>
                    </div> -->
                </v-card>
            </v-row>
        </v-app>

    </div>
</template>

<script setup>


</script>
<script setup>
import { ref, onMounted } from 'vue';
import QrcodeVue from 'qrcode.vue'
import { useAuthStore } from '@/stores/auth';
import { storeToRefs } from 'pinia';

const store = useAuthStore()
const { qrCode } = storeToRefs(store)

async function initilize() {
    await store.handleQrCode()
}

initilize()


const otp = ref('')
</script>