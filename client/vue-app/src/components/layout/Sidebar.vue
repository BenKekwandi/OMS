<style scoped>
.title {
  font-size: 12px !important;
}
</style>

<template>

  <v-navigation-drawer class="no-scrollbar" :permanent="!xs" :temporary="xs" :rail="rail" v-model="drawer" color="#071d35">
    <v-list class="mb-2">
      <v-list-item prepend-avatar="/logo-small-HQNnTX16.svg">
        <v-list-item-title class="title">ORDER MANAGEMENT <br />
          SYSTEM</v-list-item-title></v-list-item>
    </v-list>
    <v-list density="comfortable">
      <v-list-item v-for="item in listItems" :key="item.name" :prepend-icon="item.icon" :title="item.title"
        :to="{ name: item.name }"></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { defineProps, computed, watch, ref } from "vue";
import { useDisplay } from "vuetify";
const { xs } = useDisplay();

const rail = computed(() => props.toggle && !xs.value);

const props = defineProps(["toggle"]);


const drawer = defineModel({type: Boolean})


const listItems = [
  { name: "Dashboard", icon: "mdi-view-dashboard-outline", title: "Dashboard" },
  { name: "Users", icon: "mdi-account-multiple-outline", title: "Users" },
  { name: "Brands", icon: "mdi-tag-outline", title: "Brands" },
  { name: "Countries", icon: "mdi-flag-outline", title: "Countries" },
  { name: "Warehouses", icon: "mdi-store-marker-outline", title: "Warehouses" },
  {
    name: "Deactivated Users",
    icon: "mdi-account-cancel-outline",
    title: "Deactivated Users",
  },
  { name: "Security", icon: "mdi-security", title: "Security" },
  {
    name: "Invoice Companies",
    icon: "mdi-invoice-text-multiple-outline",
    title: "Invoice Companies",
  },
  { name: "Orders", icon: "mdi-package-variant-closed", title: "Orders" },
  { name: "Offers", icon: "mdi-sale-outline", title: "Offers" },
  { name: "Confirmation", icon: "mdi-progress-check", title: "Confirmation" },
  {
    name: "Expenses Types",
    icon: "mdi-credit-card-multiple-outline",
    title: "Expenses Types",
  },
  { name: "Shipment Services", icon: "mdi-archive-arrow-up-outline", title: "Shipment Services" },
  { name: "Accounting", icon: "mdi-finance", title: "Accounting" },
  { name: "Logistic", icon: "mdi-warehouse", title: "Logistic" },
  { name: "Settings", icon: "mdi-cog-outline", title: "Settings" },
  
];
</script>

<style>

.no-scrollbar ::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Edge */
}

.no-scrollbar {
  scrollbar-width: none; /* Firefox */
}


</style>
