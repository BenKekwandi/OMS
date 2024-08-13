<style scoped>
#section {
    background-image: url('../../assets/img/background.svg');
    background-color: #071d35;
    background-size: cover;
    background-attachment: fixed !important;
    background-position: center;
    background-repeat: no-repeat;

}

.main {
    position: absolute;
    top: 30%;
    left:10%
}
</style>

<template>
    <v-responsive>
        <v-app id="section">

            <div class="main">
                <p style="color: #4E5C68;" class="text-h1 font-weight-bold py-2">404</p>
                <p class="text-h3 text-white ">Page Not Found</p>
                <p class="text-h6 text-white  py-2">Sorry, the page <code
                        style="color: #00ADB5;">{{ $route.params.notFound }}</code> you are looking for
                    is not found</p>
                <v-btn @click="redirect" class="mt-6">Back to home</v-btn>
            </div>
        </v-app>
    </v-responsive>
</template>

<script setup>
import router from '@/router';
import { useAuthStore } from '@/stores/auth';


const store = useAuthStore()

const redirect = () => {

    if (store.isLoggedIn) {
        if (store.role === "admin")
            router.push({ name: "Admin" });
        else
            router.push({ name: "user" });
    }
    else {
        router.push({ name: 'login' })
    }
}



</script>