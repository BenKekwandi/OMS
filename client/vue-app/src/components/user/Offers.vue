<template>
  <OfferFilter
    :panel="panel"
    :brand_store="brand_store"
    :supplier_store="supplier_store"
  />
  <v-row no-gutters>
    <v-col lg="10" md="12" sm="12">
      <v-tabs v-model="tab" color="#00ADB5" align-tabs="start">
        <v-tab value="new">New ({{ items.new }})</v-tab>
        <v-tab value="proposed">Proposed ({{ items.proposed }})</v-tab>
        <v-tab value="confirmed">Confirmed ({{ items.confirm }})</v-tab>
        <v-tab value="expired">Expired ({{ items.expired }})</v-tab>
        <v-tab value="cancelled">Cancelled ({{ items.cancelled }})</v-tab>
        <v-tab value="all">All ({{ items.all }})</v-tab>
      </v-tabs>
    </v-col>
    <v-col lg="2" md="12" sm="12" class="d-flex justify-lg-end">
      <v-btn
        :variant="panel.length ? 'flat' : 'outlined'"
        rounded="0"
        prepend-icon=" mdi-filter"
        color="#00ADB5"
        class="my-2"
        @click="showFilter"
      >
        Filter
      </v-btn>
    </v-col>
  </v-row>
  <v-divider class="mb-2"></v-divider>
  <v-window disabled v-model="tab">
    <v-window-item value="new">
      <OffersTable
        :status="statuses.new"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="all">
      <OffersTable
        :status="statuses.all"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="proposed">
      <OffersTable
        :status="statuses.proposed"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="confirmed">
      <OffersTable
        :status="statuses.confirm"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="expired">
      <OffersTable
        :status="statuses.expired"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>

    <v-window-item value="cancelled">
      <OffersTable
        :status="statuses.cancelled"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-window-item>
  </v-window>
</template>

<script setup>
import { ref, defineProps, computed } from "vue";
import { offerStore } from "@/stores/offer";
import { brandStore } from "@/stores/brand";
import { supplierStore } from "@/stores/supplier.js";
import { storeToRefs } from "pinia";
import OffersTable from "@/components/base/tables/OffersTable.vue";
import OfferFilter from "@/components/base/filter-components/OfferFilter.vue";
import { useSnackbarStore } from "@/stores/snackbar";

const store = offerStore();
const brand_store = brandStore();
const supplier_store = supplierStore();

const props = defineProps(["permission"]);

const { permission } = props;
const { collection } = storeToRefs(store);

const panel = ref([]);
const tab = ref(null);

const items = computed(() => {
  const counts = {
    all: collection.value.length,
    new: collection.value.filter((item) => item.status === "New").length,
    proposed: collection.value.filter((item) => item.status === "Proposed")
      .length,
    confirm: collection.value.filter((item) => item.status === "Confirmed")
      .length,
    expired: collection.value.filter((item) => item.status === "Expired")
      .length,
    cancelled: collection.value.filter((item) => item.status === "Cancelled")
      .length,
  };
  return counts;
});

const statuses = {
  all: "All",
  new: "New",
  proposed: "Proposed",
  confirm: "Confirmed",
  expired: "Expired",
  cancelled: "Cancelled",
};

function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

async function initialize() {
  await store.fetchItems();
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

initialize();
</script>
