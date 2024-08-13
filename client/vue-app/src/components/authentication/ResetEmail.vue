<template>
    <div>
        <v-card class="pa-8 rounded-xl" max-width="400" height="400" variant="elevated" elevation="6">
            <v-card-title class="text-center font-weight-bold">
                Forgot Password?
            </v-card-title>

            <v-card-subtitle class="py-6 text-center font-weight-light">
                Enter your email to reset your password.
            </v-card-subtitle>
            <br />


            <v-text-field v-model="email" class="my-4" :error-messages="errors" label="Email"></v-text-field>
            <br />

            <div class="my-6 d-flex justify-center">
                <v-btn @click="handleEmailSubmit" type="submit" elevation="4" color="#00ADB5">Verify</v-btn>
            </div>
        </v-card>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "/src/stores/auth";
import { storeToRefs } from "pinia";
import { useRouter } from "vue-router";

const store = useAuthStore();

const router = useRouter();
const { errors } = storeToRefs(store);

const email = ref("");


const handleEmailSubmit = async () => {
    await store.handleEmailVerification(email.value);
    console.log(errors.value);
    if (!errors.value) {
        router.push({ name: "email-confirmation" });
    }
};
</script>
