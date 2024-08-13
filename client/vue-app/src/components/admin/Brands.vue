<template>
    <Datatable @alert="snackbarShow" :headers="headers" :store="brands" :fields="modalFields" :editedItem="editedItem"
        :defaultItem="defaultItem" :ifHyperlink="ifHyperlink" />
</template>

<script setup>
import { ref } from 'vue';
import Datatable from '../admin/tables/Datatable.vue';
import { brandStore } from '@/stores/brand';
import { useSnackbarStore } from '@/stores/snackbar';
import { rules } from "/src/includes/customValidationRules.js";


const brands = brandStore()

const headers = ref([

    { title: "Name", key: "name", align: 'start' },
    { title: "Actions", key: "actions", align: 'end', sortable: false },
]);

// item column type that has to be a hyperlink
const ifHyperlink = ref(true)

// model input fields
const modalFields = ref([
    
    { type: 'text', name: 'name', label: 'Name', icon: 'mdi-tag-plus', rules: [rules.required], cols: '12', sm: '12', md: '12' },

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