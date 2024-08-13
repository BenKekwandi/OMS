<template>
  <v-card>
    <v-card-text class="ma-0 pa-0">
      <OrderFilter :panel="panel" :brand_store="brand_store" :supplier_store="supplier_store"
        :customer_store="customer_store" />

      <v-row no-gutters>
        <v-col lg="10" md="12" sm="12">
          <v-tabs v-model="tab" color="#00ADB5" align-tabs="start" :mobile="!(xs || sm || md)">
            <v-tab value="new">New ({{ items.new }})</v-tab>
            <v-tab value="proposed">Proposed ({{ items.proposed }})</v-tab>
            <v-tab value="confirmed">Confirmed ({{ items.confirmed }})</v-tab>
            <v-tab value="invoice">Invoice Received ({{ items.invoice }})</v-tab>
            <v-tab value="cancelled">Cancelled ({{ items.cancelled }})</v-tab>
            <v-tab value="expired">Expired ({{ items.expired }})</v-tab>
            <v-tab value="all">All ({{ items.all }})</v-tab>
          </v-tabs>
        </v-col>

        <v-col lg="2" md="12" sm="12" class="d-flex justify-lg-end px-2">
          <v-btn variant="text" rounded="0" prepend-icon=" mdi-filter" color="#00ADB5" class="my-2" @click="showFilter"
            :active="!!(panel.length)">
            Filter
          </v-btn>
        </v-col>
      </v-row>
      <v-divider class="mb-2"></v-divider>
      <v-window v-model="tab">
        <v-window-item value="new">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow" :status="statuses.new"
            :store="store" @initialize="initialize" :excludeColumns="[
              'Order Confirmation Date',
              'Supplier',
              'Offer ID',
              'Serial Number',
              'Expected Arrival',
              'Expected Delivery',
              'RRP',
              'Net Purchasing Price',
              'Sales Price',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Delivery Date',
              'Invoice for Shipment',
              'Payment Date to Supplier',
              'Profit',
              'Proforma Invoice from Supplier',
              'Order Finalization Date',
            ]" />
        </v-window-item>
        <v-window-item value="all">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow" :status="statuses.all"
            :store="store" @initialize="initialize" />
        </v-window-item>
        <v-window-item value="proposed">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow"
            :status="statuses.proposed" :store="store" @initialize="initialize" :excludeColumns="[
              'Order Confirmation Date',
              'Supplier',
              'Offer ID',
              'Serial Number',
              'Expected Arrival',
              'Expected Delivery',
              'RRP',
              'Net Purchasing Price',
              'Sales Price',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Delivery Date',
              'Invoice for Shipment',
              'Payment Date to Supplier',
              'Profit',
              'Proforma Invoice from Supplier',
              'Order Finalization Date',
              'Actions',
            ]" />
        </v-window-item>

        <v-window-item value="confirmed">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow"
            :status="statuses.confirmed" :store="store" @initialize="initialize" :excludeColumns="[
              'Matches',
              'Expected Arrival',
              'Expected Delivery',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Invoice for Shipment',
              'Order Finalization Date',
              'Payment Date to Supplier',
              'Proforma Invoice from Supplier',
            ]" />
        </v-window-item>

        <v-window-item value="invoice">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow"
            :status="statuses.invoice" :store="store" @initialize="initialize" :excludeColumns="[
              'Matches',
              'Expected Arrival',
              'Expected Delivery',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Invoice for Shipment',
              'Order Finalization Date',
              'Payment Date to Supplier',
              'Proforma Invoice from Supplier',
            ]" />
        </v-window-item>

        <v-window-item value="cancelled">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow"
            :status="statuses.cancelled" :store="store" @initialize="initialize" :excludeColumns="[
              'Matches',
              'Order Confirmation Date',
              'Supplier',
              'Offer ID',
              'Expected Arrival',
              'Expected Delivery',
              'RRP',
              'Net Purchasing Price',
              'Sales Price',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Delivery Date',
              'Invoice for Shipment',
              'Payment Date to Supplier',
              'Profit',
              'Proforma Invoice from Supplier',
              'Order Finalization Date',
              'Actions',
            ]" />
        </v-window-item>

        <v-window-item value="expired">
          <OrdersDatatable :brand_store="brand_store" :permission="true" @alert="snackbarShow"
            :status="statuses.expired" :store="store" @initialize="initialize" :excludeColumns="[
              'Matches',
              'Order Confirmation Date',
              'Supplier',
              'Offer ID',
              'Expected Arrival',
              'Expected Delivery',
              'RRP',
              'Net Purchasing Price',
              'Sales Price',
              'Shipping Cost',
              'Total Expenses',
              'Invoice to the Customer',
              'Payment Date of the Customer',
              'Supplier Shipment Date',
              'Tracking #',
              'Delivery Date',
              'Invoice for Shipment',
              'Payment Date to Supplier',
              'Profit',
              'Proforma Invoice from Supplier',
              'Order Finalization Date',
              'Actions',
            ]" />
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
import { orderStore } from "@/stores/order";
import { brandStore } from "@/stores/brand";
import { supplierStore } from "@/stores/supplier.js";
import { customerStore } from "@/stores/customer.js";
import { useSnackbarStore } from "@/stores/snackbar";
//components
import OrdersDatatable from "../base/tables/OrdersTable.vue";
import OrderFilter from "@/components/base/filter-components/OrderFilter.vue";

const { xs, sm, md } = useDisplay();

const store = orderStore();
const brand_store = brandStore();
const supplier_store = supplierStore();
const customer_store = customerStore();

const { collection } = storeToRefs(store);
const tab = ref(null);
const panel = ref([]);

const items = computed(() => {
  const counts = {
    all: collection.value.length,
    new: collection.value.filter((item) => item.status === "New").length,
    proposed: collection.value.filter((item) => item.status === "Proposed")
      .length,
    confirmed: collection.value.filter(
      (item) => item.status === "PM Confirmed" || item.status === "SM Confirmed"
    ).length,
    invoice: collection.value.filter(
      (item) => item.status === "Invoice Received"
    ).length,
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
  confirmed: "Confirmed",
  invoice: "Invoice Received",
  cancelled: "Cancelled",
  expired: "Expired",
};

async function initialize() {
  await store.fetchItems();
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
  await customer_store.fetchItems();
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
