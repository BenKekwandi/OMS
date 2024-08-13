<template>

  <Datatable @alert="snackbarShow" :store="store" :headers="headers" :fields="modalFields" :editedItem="editedItem"
    :defaultItem="defaultItem" />
</template>

<script setup>
import { ref } from "vue";
import { useInvoiceCompanyStore } from "@/stores/invoice-companies";
import Datatable from "../admin/tables/Datatable.vue";
import { rules } from "/src/includes/customValidationRules.js";
import { useSnackbarStore } from '@/stores/snackbar';

const store = useInvoiceCompanyStore();

const headers = ref([
  { title: "Company", key: "company", align: "start" },
  { title: "Country", key: "country" },
  { title: "Phone", key: "phone" },
  { title: "Contact Name", key: "contact_name" },
  { title: "Location", key: "location" },
  { title: "Actions", key: "actions", align: "end", sortable: false },
]);

const modalFields = ref([
  {
    type: "text",
    name: "company",
    label: "Company",
    icon: "mdi-invoice-text-plus-outline",
    rules: [rules.required],
    cols: "6",
    sm: "12",
    md: "6",
  },
  {
    type: "text",
    name: "country",
    label: "Country",
    icon: "mdi-flag-plus-outline",
    rules: [rules.required],
    cols: "6",
    sm: "12",
    md: "6",
  },
  {
    type: "text",
    name: "phone",
    label: "Phone",
    icon: "mdi-phone-outline",
    rules: [rules.phone],
    cols: "6",
    sm: "12",
    md: "6",
  },
  {
    type: "text",
    name: "contact_name",
    label: "Contact Name",
    icon: "mdi-card-bulleted-outline",
    rules: [rules.required],
    cols: "6",
    sm: "12",
    md: "6",
  },
  {
    type: "textarea",
    name: "location",
    label: "Location",
    icon: "mdi-map-marker",
    rules: [rules.required],
    cols: "12",
    sm: "12",
    md: "12",
  },
]);

const editedItem = ref({
  company: "",
  country: "",
  phone: "",
  contact_name: "",
  location: "",
});

const defaultItem = ref({
  company: "",
  country: "",
  phone: "",
  contact_name: "",
  location: "",
});

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type)
};

</script>
