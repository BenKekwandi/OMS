<style scoped>
#bg-img {
  background-image: url("../assets/img/Group 71.svg") !important;
  background-color: #071d35;
  background-size: cover;
  background-position: center;
}

.logo {
  max-width: 200px;
  width: 100%;
  height: auto;
}

@media (max-width: 800px) {
  .logo {
    max-width: 150px;
  }

  .text {
    font-size: 18px;
  }
}
</style>

<template>

  <v-app>

    <v-row no-gutters>

      <v-col v-if="!xs" cols="12" sm="6" id="bg-img" class="d-flex flex-column justify-center align-center">

        <img src="../assets/img/logo.png" class="pb-3 logo" />
        <h1 class="text-white text-center text">ORDER MANAGEMENT SYSTEM</h1>

      </v-col>

      <v-col cols="12" sm="6" class="d-flex align-center justify-center">

        <router-view></router-view>

      </v-col>
    </v-row>
  </v-app>

</template>

<script setup>
import { useDisplay } from "vuetify";

const { xs } = useDisplay()
</script>
