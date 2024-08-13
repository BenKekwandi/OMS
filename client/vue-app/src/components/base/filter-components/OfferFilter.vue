<template>
  <v-expansion-panels v-model="panel" flat>
    <v-expansion-panel value="filter">
      <v-expansion-panel-text>

        <v-row class="mt-2">

          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-text-field density="compact" v-model="filter.offer_id" label="Offer ID:" clearable variant="underlined"
              color="#00ADB5"></v-text-field>
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <DatePicker density="compact" v-model="filter.created_from" label="Created: (from)" color="#00ADB5"
              clearable />
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <DatePicker density="compact" v-model="filter.created_to" label="Created: (to)" color="#00ADB5" clearable />
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-text-field density="compact" label="Serial number:" v-model="filter.serial_number" variant="underlined"
              color="#00ADB5" clearable></v-text-field>
          </v-col>

        </v-row>

        <v-row>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete density="compact" label="Brand:" v-model="filter.brand" :items="brand_store.collection"
              item-title="name" item-value="id" placeholder="Select" variant="underlined"
              @update:model-value="(brand) => getModels(brand)" color="#00ADB5" clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete density="compact" label="Model:" v-model="filter.model" placeholder="Select"
              variant="underlined" item-value="reference" item-title="reference" :items="models_collection"
              color="#00ADB5" clearable @focus="checkBrandSelection" :error-messages="error_message"></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete density="compact" label="Supplier:" v-model="filter.supplier"
              :items="supplier_store.collection" item-title="name" item-value="id" placeholder="Select"
              variant="underlined" color="#00ADB5" clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete density="compact" label="Status:" v-model="filter.status" :items="statuses"
              item-title="name" item-value="id" placeholder="Select" variant="underlined" color="#00ADB5" clearable>
            </v-autocomplete>
          </v-col>
        </v-row>

        <v-row>
          <v-col class=" py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete density="compact" label="Availability:" v-model="filter.availability" placeholder="Select"
              variant="underlined" item-title="name" item-value="id" :items="availabilities" color="#00ADB5"
              clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1">
            <v-checkbox density="compact" v-model="filter.my_offers" label="My Offers" color="#00ADB5"
              hide-details></v-checkbox>
          </v-col>
        </v-row>

        <v-row>
          <v-col class="d-flex justify-end">
            <v-btn class="mx-2" @click="clear">Clear</v-btn>
            <v-btn color="primary" class="mx-2" @click="handleFilter">Apply</v-btn>
          </v-col>
        </v-row>
        <br />
        <br />
      </v-expansion-panel-text>
  
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script setup>
//vue and pinia
import { ref, toRefs, defineProps, watch } from "vue";
//stores
import { offerStore } from "@/stores/offer";
import { modelStore } from "@/stores/model";

import DatePicker from "../form-elements/DatePicker.vue";
import { storeToRefs } from "pinia";

//define props
const props = defineProps({
  panel: Array,
  brand_store: Object,
  supplier_store: Object,
});
const { panel } = toRefs(props);

//define stores
const store = offerStore();
const model_store = modelStore();

const { collection: models_collection } = storeToRefs(model_store)

const filter = ref({
  offer_id: "",
  created_from: null,
  created_to: null,
  brand: null,
  model: null,
  supplier: null,
  status: null,
  orders_days_from: "",
  orders_days_to: "",
  availability: null,
  serial_number: "",
  my_offers: false,
});

const defaultValue = {
  offer_id: "",
  created_from: null,
  created_to: null,
  brand: null,
  model: null,
  supplier: null,
  status: null,
  orders_days_from: "",
  orders_days_to: "",
  availability: null,
  serial_number: "",
  my_offers: false,
};

const statuses = [
  { id: 1, name: "New" },
  { id: 2, name: "Proposed" },
  { id: 3, name: "Confirmed" },
  { id: 4, name: "Expired" },
  { id: 5, name: "Cancelled" },
];
const availabilities = [
  { id: 1, name: "In shop" },
  { id: 2, name: "In order" },
  { id: 3, name: "In stock" },
];


// function filterValues() {
//   const data = {};
//   for (const key in filter.value) {
//     if (filter.value[key] !== null && filter.value[key] !== "") {
//       data[key] = filter.value[key];
//     }
//   }
//   return data;
// }

const handleFilter = async () => {
  await store.filterHandler(filter.value);
};



const getModels = async (brand) => {
  if (brand) {
    await model_store.fetchItems(brand);
  }
  else
    models_collection.value = []

  filter.value.model = null
};

const clear = () => {
  filter.value = Object.assign({}, defaultValue);
};

//wth does this do??

// watch(
//   () => filter.value.brand,
//   (newBrand) => {

//     if (!newBrand) {

//       filter.value.model = null;
//     } else {
//       getModels(newBrand);
//     }
//   }
// );

const error_message = ref(null)

const checkBrandSelection = () => {

  if (!filter.value.brand) {
    error_message.value = "Please select a brand first";
  }
  else {
    error_message.value = null
  }
};
</script>
