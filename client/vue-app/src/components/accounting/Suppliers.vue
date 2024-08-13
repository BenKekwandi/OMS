<template>
  <v-snackbar
    min-width="100"
    v-model="snackbar.show"
    :color="snackbar.color"
    :timeout="3000"
  >
    <div class="d-flex justify-center align-center">
      <div>{{ snackbar.text }}</div>
    </div>
  </v-snackbar>

  <v-tabs class="mb-2" v-model="tab" fixed-tabs>
    <v-tab value="all">All ({{ items.all }})</v-tab>
    <v-tab value="confirmed">Confirmed ({{ items.confirmed }})</v-tab>
    <v-tab value="invoice">Invoice Received ({{ items.invoice }})</v-tab>
  </v-tabs>

  <v-window v-model="tab">
    <v-window-item value="all">
      <AccountingTable
        @alert="snackbarShow"
        :status="statuses.all"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="confirmed">
      <AccountingTable
        @alert="snackbarShow"
        :status="statuses.confirmed"
        :store="store"
        :excludeColumns="[]"
      />
    </v-window-item>

    <v-window-item value="invoice">
      <AccountingTable
        @alert="snackbarShow"
        :status="statuses.invoice"
        :store="store"
        :excludeColumns="[]"
      />
    </v-window-item>

    <v-window-item value="expired">
      <AccountingTable
        @alert="snackbarShow"
        :status="statuses.expired"
        :store="store"
        :excludeColumns="[]"
      />
    </v-window-item>
  </v-window>
</template>

<script setup>
import { ref, computed } from "vue";
import { orderStore } from "@/stores/order";
import AccountingTable from "./AccountingTable.vue";
import { storeToRefs } from "pinia";
const store = orderStore();
const { collection } = storeToRefs(store);
const tab = ref(null);

const items = computed(() => {
  const counts = {
    all: collection.value.length,
    confirmed: collection.value.filter(
      (item) => item.status === "PM Confirmed" || item.status === "SM Confirmed"
    ).length,
    invoice: collection.value.filter(
      (item) => item.status === "Invoice Received"
    ).length,
  };
  return counts;
});

const statuses = {
  all: "All",
  confirmed: "Confirmed",
  invoice: "Invoice Received",
};

async function initialize() {
  await store.fetchItems();
}

const snackbar = ref({
  show: false,
  text: "",
  color: "",
});

const snackbarShow = (message, type) => {
  snackbar.value = {
    show: true,
    text: message,
    color: type,
  };
};

initialize();
</script>

<style scoped>
.v-tab.v-tab.v-btn {
  max-width: none !important;
}
.v-tabs {
  color: #00adb5 !important;
  border-bottom: 1px solid #e6e0e9;
}
</style>
