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
                <router-view></router-view>
            </v-row>
        </v-app>

    </div>
</template>

<script setup>


</script>