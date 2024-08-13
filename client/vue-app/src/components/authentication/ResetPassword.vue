<template>
    <v-snackbar v-model="store.errors" :text="store.errors" color="error" :timeout="3000">

    </v-snackbar>


    <v-card class="pa-8 rounded-xl" max-width="400" variant="elevated" elevation="6">
        <v-card-title class="text-center font-weight-bold">
            Reset your password!
        </v-card-title>

        <v-card-subtitle class="py-6 text-center font-weight-light">
            Enter and confirm your password to reset it.
        </v-card-subtitle>
        <vee-form :validation-schema="schema" @submit="resetPassword">
            <vee-field name="password" :bails="false" v-slot="{ field, errors }">
                <v-text-field :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :type="show1 ? 'text' : 'password'"
                    name="password" @click:append-inner="show1 = !show1" v-model="form.password" type="password"
                    :error-messages="errors" label="Password" v-bind="field"></v-text-field>
            </vee-field>
            <v-row class="mx-1 my-2">
                <v-col class="pa-0 "> <v-progress-linear :color="score.color" style="width: 72px;"
                        :model-value="score.value >= 25 ? 100 : 0" :height="5"></v-progress-linear></v-col>
                <v-col class="pa-0 "> <v-progress-linear :color="score.color" style="width: 72px;"
                        :model-value="score.value >= 50 ? 100 : 0" :height="5"></v-progress-linear></v-col>
                <v-col class="pa-0 "> <v-progress-linear :color="score.color" style="width: 72px;"
                        :model-value="score.value >= 75 ? 100 : 0" :height="5"></v-progress-linear></v-col>
                <v-col class="pa-0 "> <v-progress-linear :color="score.color" style="width: 72px;"
                        :model-value="score.value == 100 ? 100 : 0" :height="5"></v-progress-linear></v-col>

            </v-row>
            <v-row class="d-flex mx-1 my-2 justify-end">
                <p style="font-size: 12px;" v-bind:style="{ color: score.color }">{{ score.status }}</p>
            </v-row>

            <vee-field name="password_confirmation" :bails="false" v-slot="{ field, errors }">
                <v-text-field class="my-8" v-model="form.password_confirmation" type="password"
                    label="Password Confirmation" :error-messages="errors" v-bind="field"></v-text-field>
            </vee-field>
            <div class="d-flex justify-center"> <v-btn type="submit" elevation="4" color="#00ADB5">Submit</v-btn>
            </div>
        </vee-form>
    </v-card>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { zxcvbn } from '@zxcvbn-ts/core'
import { useRoute } from 'vue-router'
import { useAuthStore } from "/src/stores/auth";
import { useRouter } from "vue-router";

const router = useRouter();
const store = useAuthStore();

const show1 = ref(false)

const form = reactive({
    password: "",
    password_confirmation: ""
})

const schema = ref({
    password: "excluded:password|min:8",
    password_confirmation: "password_mismatch:@password"
});


const route = useRoute();

const params = new URLSearchParams(route.fullPath.split('?')[1]);

const email = ref(params.get('email'));
const token = ref(route.params.token)

const resetPassword = async () => {
    await store.handleResetPassword({
        email: email.value,
        token: token.value,
        password: form.password,
        password_confirmation: form.password_confirmation
    })
    if (!store.errors) {
        router.push({ name: "login" });
    }
}




const score = computed(() => {
    const result = zxcvbn(form.password);

    switch (result.score) {
        case 4:
            return {
                color: "#4CAF50",
                value: 100,
                status: "Strong"
            };
        case 3:
            return {
                color: "#8BC34A",
                value: 75,
                status: "Good"
            };
        case 2:
            return {
                color: "#FF9800",
                value: 50,
                status: "Fair"
            };
        case 1:
            return {
                color: "#F44336",
                value: 25,
                status: "Weak"
            };
        default:
            return {
                color: "#F44336",
                value: 0,
                status: " "
            };
    }
});

</script>