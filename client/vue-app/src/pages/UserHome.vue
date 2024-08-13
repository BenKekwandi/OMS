<template>
    <v-layout class=" rounded rounded-md">
        <Header />
        <v-main class="">
    
                <router-view></router-view>
           
        </v-main>
    </v-layout>
</template>

<script setup>

import Header from '@/components/layout/Header.vue';

</script>