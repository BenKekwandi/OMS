<template>
 
  <Datatable @alert="snackbarShow" :store="store" :headers="headers" :fields="modalFields" :editedItem="editedItem"
    :defaultItem="defaultItem" />
</template>

<script setup>
import { ref } from "vue";
import { useExpensesTypesStore } from "@/stores/expenses-types";
import Datatable from "../admin/tables/Datatable.vue";
import { useSnackbarStore } from '@/stores/snackbar';
import { rules } from "/src/includes/customValidationRules.js";

const store = useExpensesTypesStore();

const headers = ref([
  { title: "Expense", key: "name", align: "start" },
  { title: "Actions", key: "actions", align: "end", sortable: false },
]);

const modalFields = ref([
  {
    type: "text",
    name: "name",
    label: "Expense",
    icon: "mdi-invoice-text-plus-outline",
    rules: [rules.required],
    cols: "12",
    sm: "12",
    md: "12",
  },
]);

const editedItem = ref({
  name: "",
});

const defaultItem = ref({
  name: "",
});

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type)
};
</script>
