<template>
  <Snackbar />
  <router-view />

</template>

<script setup>
import { useSnackbarStore } from '@/stores/snackbar';
</script>

<style scoped></style>
