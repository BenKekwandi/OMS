<template>
    
    <Datatable @alert="snackbarShow" :headers="headers" :store="countries" :fields="modalFields" :editedItem="editedItem"
        :defaultItem="defaultItem" />
</template>

<script setup>
import { ref } from 'vue';
import Datatable from '../admin/tables/Datatable.vue';
import { countryStore } from '@/stores/countries';
import { useSnackbarStore } from '@/stores/snackbar';
import { rules } from "/src/includes/customValidationRules.js";

const countries = countryStore()

const headers = ref([

    { title: "Name", key: "name", align: 'start' },
    { title: "Vat Rate / %", key: "vat" },
    { title: "Actions", key: "actions", align: 'end', sortable: false },
]);

const modalFields = ref([
    { type: 'text', name: 'name', label: 'Name', icon: 'mdi-flag-plus-outline', rules: [rules.required], cols: '12', sm: '12', md: '12' },
    { type: 'number', name: 'vat', label: 'Vat Rate', icon: 'mdi-percent', rules: [rules.required], cols: '12', sm: '12', md: '12' },
])

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