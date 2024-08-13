<template>
    <v-snackbar min-width="100" v-model="snackbar.show" :color="snackbar.color" :timeout="3000">
        <div class="d-flex align-center">
            <v-icon v-show="snackbar.color === 'error'" class="mr-2">mdi-alert-circle-outline</v-icon>
            <v-icon v-show="snackbar.color === 'success'" class="mr-2">mdi-check-circle-outline</v-icon>
            <span class="text-subtitle-1">{{ snackbar.text }}</span>
        </div>
    </v-snackbar>
</template>

<script setup>
import { useSnackbarStore } from '@/stores/snackbar';

const snackbar = useSnackbarStore()


</script>