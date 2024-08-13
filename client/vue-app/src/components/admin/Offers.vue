<template>
  <v-card>
    <v-card-text class="ma-0 pa-0">
    <OfferFilter
      :panel="panel"
      :brand_store="brand_store"
      :supplier_store="supplier_store"
    />
    <v-row no-gutters>
      <v-col lg="10" md="12" sm="12">
        <v-tabs v-model="tab" color="#00ADB5" align-tabs="start" :mobile="!(xs || sm || md)">
          <v-tab value="new">New ({{ items.new }})</v-tab>
          <v-tab value="proposed">Proposed ({{ items.proposed }})</v-tab>
          <v-tab value="confirmed">Confirmed ({{ items.confirm }})</v-tab>
          <v-tab value="expired">Expired ({{ items.expired }})</v-tab>
          <v-tab value="cancelled">Cancelled ({{ items.cancelled }})</v-tab>
          <v-tab value="all">All ({{ items.all }})</v-tab>
        </v-tabs>
      </v-col>

      <v-col lg="2" md="12" sm="12" class="d-flex justify-lg-end px-2">
        <v-btn
          variant="text"
          rounded="0"
          prepend-icon=" mdi-filter"
          color="#00ADB5"
          class="my-2"
          @click="showFilter"
          :active="!!(panel.length)"
        >
          Filter
        </v-btn>
      </v-col>
    </v-row>
    <v-divider class="mb-2"></v-divider>
    <v-window v-model="tab">
      <v-window-item value="new">
        <OffersDatatable
          @alert="snackbarShow"
          :permission="true"
          :status="statuses.new"
          :store="offer_store"
          @initialize="initialize"
          
        />
      </v-window-item>
      <v-window-item value="all">
        <OffersDatatable
          @alert="snackbarShow"
          :permission="true"
          :status="statuses.all"
          :store="offer_store"
          @initialize="initialize"
       
        />
      </v-window-item>
      <v-window-item value="proposed">
        <OffersDatatable
          @alert="snackbarShow"
          :permission="true"
          :status="statuses.proposed"
          :store="offer_store"
          @initialize="initialize"
        />
      </v-window-item>

      <v-window-item value="confirmed">
        <OffersDatatable
          @alert="snackbarShow"
          permission="true"
          :status="statuses.confirm"
          :store="offer_store"
          @initialize="initialize"
          :excludeColumns="['Matches']"
        />
      </v-window-item>

      <v-window-item value="expired">
        <OffersDatatable
          @alert="snackbarShow"
          :permission="true"
          :status="statuses.expired"
          :store="offer_store"
          @initialize="initialize"
        />
      </v-window-item>

      <v-window-item value="cancelled">
        <OffersDatatable
          @alert="snackbarShow"
          :permission="true"
          :status="statuses.cancelled"
          :store="offer_store"
          @initialize="initialize"
          :excludeColumns="['Matches']"
        />
      </v-window-item>
    </v-window>
  </v-card-text>
  </v-card>
</template>

<script setup>
//vue and pinia
import { ref, computed } from "vue";
import { storeToRefs } from "pinia";
import { useDisplay } from "vuetify";

//stores
import { offerStore } from "@/stores/offer";
import { brandStore } from "@/stores/brand";
import { supplierStore } from "@/stores/supplier.js";
import { useSnackbarStore } from "@/stores/snackbar";
//components
import OffersDatatable from "../base/tables/OffersTable.vue";
import OfferFilter from "@/components/base/filter-components/OfferFilter.vue";

const { xs, sm, md } = useDisplay();

const offer_store = offerStore();
const brand_store = brandStore();
const supplier_store = supplierStore();

const { collection } = storeToRefs(offer_store);
const tab = ref(null);
const panel = ref([]);

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

async function initialize() {
  await offer_store.fetchItems();
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
}

function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

initialize();

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};
</script>
