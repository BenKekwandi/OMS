<template>
  <v-card>

    <v-card-text class="ma-0 pa-0">
      <AccFilter :panel="panel" :brand_store="brand_store" :supplier_store="supplier_store"
        :customer_store="customer_store" :invoice_companies="invoice_companies"
        :pmManagers="users_store.collection.filter((item) => item.role === 'pm')"
        :smManagers="users_store.collection.filter((item) => item.role === 'sm')" />

      <v-row no-gutters>
        <v-spacer></v-spacer>
        <v-col lg="2" md="12" sm="12" class="d-flex justify-lg-end px-2">
          <v-btn variant="text" rounded="0" prepend-icon=" mdi-filter" color="#00ADB5" class="my-2" @click="showFilter"
            :active="!!(panel.length)">
            Filter
          </v-btn>
        </v-col>
      </v-row>
      <AccountingDatatable @alert="snackbarShow" @initialize="initialize" :store="order_store" :invoiceCompanyStore="invoice_companies"
        @uploadInvoice="uploadInvoice" @updateInvoice="updateInvoice" v-model="dialogInvoice" />
    </v-card-text>

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
import { useInvoiceCompanyStore } from "@/stores/invoice-companies";
import { useInvoiceStore } from "@/stores/invoices.js";

const invoice_store = useInvoiceStore();

const order_store = orderStore();
const users_store = useUserStore();
const brand_store = brandStore();
const supplier_store = supplierStore();
const customer_store = customerStore();
const invoice_companies = useInvoiceCompanyStore();
const { errors, res } = storeToRefs(order_store);
const {
  errors: invoiceErrors,
  res: invoiceRes,
  loading: invoiceLoading,
} = storeToRefs(invoice_store);

const panel = ref([]);
const dialogInvoice = ref(false);

async function initialize() {
  await order_store.accountingOrdersHandler();
  await users_store.fetchUsers();
  await brand_store.fetchItems();
  await supplier_store.fetchItems();
  await customer_store.fetchItems();
  await invoice_companies.fetchItems();
}

function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

const uploadInvoice = async (type, orderId, data) => {
  if (type === "customer")
    await invoice_store.uplaodInvoiceCustomerHandler(orderId, data);
  else if (type === "supplier")
    await invoice_store.uplaodInvoiceSupplierHandler(orderId, data);
  if (invoiceRes.value) {
    initialize();
    snackbarShow(invoiceRes.value.message, "success");
    dialogInvoice.value = false;
  } else {
    snackbarShow(invoiceErrors.value, "error");
  }
  invoiceRes.value = null;
};

const updateInvoice = async (type, invoiceId, data) => {
  if (type === "customer")
    await invoice_store.updateInvoiceHandler(invoiceId, data);
  else if (type === "supplier")
    await invoice_store.updateInvoiceHandler(invoiceId, data);
  if (invoiceRes.value) {
    initialize();
    snackbarShow(invoiceRes.value.message, "success");
    dialogInvoice.value = false;
  } else {
    snackbarShow(invoiceErrors.value, "error");
  }
  invoiceRes.value = null;
};

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

initialize();
</script>
