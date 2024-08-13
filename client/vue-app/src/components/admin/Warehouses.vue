<template>
    <Datatable @alert="snackbarShow" :headers="headers" :store="warehouses" :fields="modalFields" :editedItem="editedItem"
        :defaultItem="defaultItem" />
</template>

<script setup>
import { ref } from 'vue';
import Datatable from "../admin/tables/Datatable.vue";
import { warehouseStore } from '@/stores/warehouses';
import { useSnackbarStore } from '@/stores/snackbar';
import { rules } from "/src/includes/customValidationRules.js";

const warehouses = warehouseStore()

const headers = ref([

    { title: "Country", key: "country", align: 'start' },
    { title: "Location", key: "location" },
    { title: "Actions", key: "actions", align: 'end', sortable: false },
]);

const modalFields = ref([
    { type: 'text', name: 'country', label: 'Country', icon: ' mdi-store-plus', rules: [rules.required], cols: '12', sm: '12', md: '12' },
    { type: 'text', name: 'location', label: 'Location', icon: 'mdi-map-marker-radius', rules: [rules.required], cols: '12', sm: '12', md: '12' },
])

const editedItem = ref({
    name: "",
    location: ""
});

const defaultItem = ref({
    name: "",
    location: ""
});

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type)
};
</script>