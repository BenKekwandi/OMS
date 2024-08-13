<template>
  <OrderFilter
    :panel="panel"
    :brand_store="brand_store"
    :supplier_store="supplier_store"
    :customer_store="customer_store"
  />

  <v-row no-gutters>
    <v-col lg="10" md="12" sm="12">
      <v-tabs v-model="tab" color="#00ADB5" align-tabs="start" :mobile="sm">
        <v-tab value="new">New ({{ items.new }})</v-tab>
        <v-tab value="proposed">Proposed ({{ items.proposed }})</v-tab>
        <v-tab value="confirmed">Confirmed ({{ items.confirmed }})</v-tab>
        <v-tab value="invoice">Invoice Received ({{ items.invoice }})</v-tab>
        <v-tab value="cancelled">Cancelled ({{ items.cancelled }})</v-tab>
        <v-tab value="expired">Expired ({{ items.expired }})</v-tab>
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
  <v-tabs-window disabled v-model="tab">
    <v-tabs-window-item value="new">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :status="statuses.new"
        :excludeColumns="[
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
        ]"
        :permission="permission"
        :store="store"
        @alert="snackbarShow"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="all">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :status="statuses.all"
        :permission="permission"
        :store="store"
        @alert="snackbarShow"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="proposed">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :status="statuses.proposed"
        :excludeColumns="[
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
        ]"
        :permission="permission"
        :store="store"
        @alert="snackbarShow"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="confirmed">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :status="statuses.confirmed"
        :excludeColumns="[
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
        ]"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="invoice">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :excludeColumns="[
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
        ]"
        :status="statuses.invoice"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="cancelled">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :excludeColumns="[
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
        ]"
        :status="statuses.cancelled"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-tabs-window-item>

    <v-tabs-window-item value="expired">
      <OrdersTable
        :brand_store="brand_store"
        @initialize="initialize"
        :excludeColumns="[
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
        ]"
        :status="statuses.expired"
        @alert="snackbarShow"
        :permission="permission"
        :store="store"
      />
    </v-tabs-window-item>
  </v-tabs-window>
</template>

<script setup>
import { ref, defineProps, computed } from "vue";
import { useDisplay } from "vuetify";
import { orderStore } from "@/stores/order";
import { brandStore } from "@/stores/brand";
import { supplierStore } from "@/stores/supplier.js";
import { customerStore } from "@/stores/customer.js";
import { storeToRefs } from "pinia";
import { useSnackbarStore } from "@/stores/snackbar";

import OrdersTable from "@/components/base/tables/OrdersTable.vue";
import OrderFilter from "@/components/base/filter-components/OrderFilter.vue";

const { sm } = useDisplay();

const store = orderStore();
const brand_store = brandStore();
const supplier_store = supplierStore();
const customer_store = customerStore();

const props = defineProps(["permission"]);

const { permission } = props;
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

function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

async function initialize() {
  await store.fetchItems();
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
  await customer_store.fetchItems();
}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

initialize();
</script>
