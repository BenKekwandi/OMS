<template>
  <v-snackbar min-width="100" v-model="snackbar.show" :color="snackbar.color" :timeout="3000">
    <div class="d-flex justify-center align-center">
      <div>{{ snackbar.text }}</div>
    </div>
  </v-snackbar>

  <v-btn class="px-0 mx-0" prepend-icon=" mdi-chevron-left" :to="{name: 'Brands'}"  variant="plain" color="primary">
    Brands
  </v-btn>
  <Datatable @alert="snackbarShow" :headers="headers" :store="models" :fields="modalFields"
    :editedItem="editedItem" :defaultItem="defaultItem" :type="type" :modelID="id" />
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import Datatable from "../admin/tables/Datatable.vue";
import { modelStore } from "@/stores/model";
import { useSnackbarStore } from '@/stores/snackbar';
import { rules } from "/src/includes/customValidationRules.js";

const route = useRoute();
const id = ref(route.params.id);


const models = modelStore();

const headers = ref([
  { title: "Name", key: "reference", align: "start" },
  { title: "Actions", key: "actions", align: "end", sortable: false },
]);

const modalFields = ref([
  {
    type: "text", name: "reference", label: "Name", icon: "mdi-tag-plus-outline", rules: [rules.required], cols: "12", sm: "12", md: "12"
  },
  {
    type: "image", name: 'image', cols: "12", sm: "12", md: "12",
  }
]);

const editedItem = ref({
  reference: "",
  brand_id: "",
  image: ""
});

const defaultItem = ref({
  reference: "",
  brand_id: "",
  image: ""
});

const type = ref("hyperlink");

const snackbar = ref({
  show: false,
  text: "",
  color: "",
});

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type)
};


</script>
