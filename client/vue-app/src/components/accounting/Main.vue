<template>
  <v-card>
    <AccFilter :panel="panel" :brand_store="brand_store" :supplier_store="supplier_store"
      :customer_store="customer_store" :pmManagers="users_store.collection.filter((item) => item.role === 'pm')"
      :smManagers="users_store.collection.filter((item) => item.role === 'sm')" />

    <v-row no-gutters>
      <!-- <v-col lg="10" md="12" sm="12">
        <v-tabs v-model="tab" color="primary" align-tabs="start">
          <v-tab value="customers">Customers</v-tab>
          <v-tab value="suppliers">Suppliers</v-tab>
        </v-tabs>
      </v-col> -->

      <v-col class="d-flex pl-4 justify-lg-end">
        <v-btn variant="tonal" prepend-icon=" mdi-filter"
          :color="panel.length ? 'blue-grey-lighten-1' : 'blue-grey-darken-3'" class="my-2 mr-2" @click="showFilter">
          Show Filter
        </v-btn>
      </v-col>
    </v-row>
    <AccountingDatatable @alert="snackbarShow" @initialize="initialize" :store="order_store"
      @uploadInvoice="uploadInvoice" @updateInvoice="updateInvoice" v-model:dialogInvoice="dialogInvoice"/>
    <!-- <v-window v-model="tab">
      <v-window-item value="customers">
        <AccountingDatatable @alert="snackbarShow" @initialize="initialize" :store="order_store" :tabType="'customers'"
          @uploadInvoice="uploadInvoice" @updateInvoice="updateInvoice" />
      </v-window-item>
      <v-window-item value="suppliers">
        <AccountingDatatable @alert="snackbarShow" @initialize="initialize" :store="order_store" :tabType="'suppliers'"
        @uploadInvoice="uploadInvoice" @updateInvoice="updateInvoice" />
      </v-window-item>
    </v-window> -->
  </v-card>
</template>

<script setup>
//vue and pinia
import { ref } from "vue";
import { storeToRefs } from "pinia";

//components
import AccountingDatatable from "../admin/tables/AccountingDatatable.vue";
import AccFilter from "@/components/base/filter-components/AccFilter.vue";

//stores
import { orderStore } from "@/stores/order";
import { brandStore } from "@/stores/brand";
import { supplierStore } from "@/stores/supplier.js";
import { customerStore } from "@/stores/customer.js";
import { useUserStore } from "@/stores/users";
import { useSnackbarStore } from "@/stores/snackbar";



const order_store = orderStore();
const users_store = useUserStore()
const brand_store = brandStore();
const supplier_store = supplierStore();
const customer_store = customerStore();
const { errors, res } = storeToRefs(order_store);

const tab = ref(null);
const panel = ref([]);
const dialogInvoice = ref(false);

async function initialize() {
  await order_store.accountingOrdersHandler();
  await users_store.fetchUsers()
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
  await customer_store.fetchItems();
}

function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

const uploadInvoice = async (type, orderId, data) => {
  if (type === "customer")
    await order_store.uplaodInvoiceCustomerHandler(orderId, data)
  else if (type === "supplier")
    await order_store.uplaodInvoiceSupplierHandler(orderId, data)
  if (res.value) {
    initialize()
    snackbarShow(res.value.message, "success")
  } else {
    snackbarShow(errors.value, "error")
  }
  dialogInvoice.value = false;
}



const updateInvoice = async (type, invoiceId, data) => {
  if (type === "customer")
    await order_store.updateInvoiceHandler(invoiceId, data)
  else if (type === "supplier")
    await order_store.updateInvoiceHandler(invoiceId, data)
  if (res.value) {
    initialize()
    snackbarShow(res.value.message, "success")
  } else {
    snackbarShow(errors.value, "error")
  }
  dialogInvoice.value = false;
}



const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type)

};


initialize();
</script>
