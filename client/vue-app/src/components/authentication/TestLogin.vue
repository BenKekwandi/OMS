<template>
    <v-snackbar min-width="100" v-model="snackbar.show" :color="snackbar.color" :timeout="3000">
        <div class="d-flex justify-center align-center">
            <v-icon clas="mx-2">{{ snackbar.icon }}</v-icon>
            <div>{{ snackbar.text }}</div>
        </div>
    </v-snackbar>
    <v-card max-width="400" width="100%" class="pa-6 rounded" variant="elevated" elevation="6">
        <v-card-title class="text-center font-weight-bold">
            Welcome Back
        </v-card-title>

        <v-card-subtitle class="py-6 text-center font-weight-light">
            Enter your email and password <br />
            to access your account.
        </v-card-subtitle>

        <vee-form :validation-schema="schema" @submit="handleSubmit">
            <vee-field type="email" name="email" :bails="true" v-slot="{ field, errors }">
                <v-text-field class="my-4" v-model="form.email" v-bind="field" label="Email"
                    :error-messages="errors"></v-text-field>
            </vee-field>

            <vee-field name="password" :bails="true" v-slot="{ field, errors }">
                <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-end">
                    <a type="button" class="text-caption text-decoration-none text-blue" @click="forgotPassword"
                        rel="noopener noreferrer">
                        Forgot login password?</a>
                </div>
                <v-text-field v-model="form.password" :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    v-bind="field" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Password"
                    :error-messages="errors" @click:append-inner="show1 = !show1"></v-text-field>
            </vee-field>
            <div class="my-6 d-flex justify-center">
                <v-btn type="submit" elevation="4" color="#00ADB5">Login</v-btn>
            </div>
        </vee-form>
        <!-- <v-btn :to="{ name: '2fa' }"></v-btn> -->
    </v-card>
</template>

<script setup>
import { reactive, ref, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "/src/stores/auth";
import { storeToRefs } from "pinia";


const router = useRouter();
const store = useAuthStore();
const { isLoggedIn, errors, message } = storeToRefs(store);

const schema = ref({
    email: "required|email",
    password: "required|excluded:password",
});

const form = reactive({
    email: "",
    password: "",
});

const show1 = ref(false);

const snackbar = ref({
    show: false,
    text: "",
    color: '',
    icon: ''
});



onMounted(() => {
    errors.value = null;
    if (message.value) {
        snackbar.value = {
            show: true,
            text: message,
            color: 'success',
            icon: 'mdi-check'
        };
    }
});

const handleSubmit = async () => {
    await store.testSubmit(form)

    router.push("/test/2fa")
    // if (errors.value) {
    //     snackbar.value = {
    //         show: true,
    //         text: errors.value,
    //         color: 'error',
    //         icon: '$error'
    //     };
    //     console.log(errors.value);
    // }

    // if (isLoggedIn.value) {
    //     if (store.user.role === "admin")
    //         router.push({ name: "Admin" });
    //     else
    //         router.push({ name: "user" });

    // }
};

const forgotPassword = () => {
    router.push({ name: "email" });
    errors.value = null
}
</script>
