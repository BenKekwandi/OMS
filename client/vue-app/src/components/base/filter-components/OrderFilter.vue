<template>
  <v-expansion-panels v-model="panel" flat>
    <v-expansion-panel value="filter">
      <v-expansion-panel-text>

        <v-row align="center" class="mt-2">
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.confirm_from" label="Confirm: (from)" color="#00ADB5" density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.confirm_to" label="Confirm: (to)" color="#00ADB5" density="compact" />
          </v-col>

          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.expected_arrival_from" label="Expected arrival: (from)" color="#00ADB5"
              density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.expected_arrival_to" label="Expected arrival: (to)" color="#00ADB5"
              density="compact" />
          </v-col>

        </v-row>

        <v-row align="center">
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.deadline_from" label="Deadline Date: (from)" color="#00ADB5"
              density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.deadline_to" label="Deadline Date: (to)" color="#00ADB5" density="compact" />
          </v-col>

          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.actual_arrival_from" label="Actual arrival: (from)" color="#00ADB5"
              density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.actual_arrival_to" label="Actual arrival: (to)" color="#00ADB5"
              density="compact" />
          </v-col>


        </v-row>

        <v-row align="center">
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.payment_deadline_from" label="Payment deadline: (from)" color="#00ADB5"
              density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <DatePicker v-model="filter.payment_deadline_to" label="Payment deadline: (to)" color="#00ADB5"
              density="compact" />
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Brand:" v-model="filter.brand" placeholder="Select" variant="underlined"
              :items="brand_store.collection" item-title="name" item-value="id"
              @update:model-value="(brand) => getModels(brand)" color="#00ADB5" density="compact"
              clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Model:" v-model="filter.model" placeholder="Select" variant="underlined"
              item-value="reference" item-title="reference" :items="model_store.collection" @focus="checkBrandSelection"
              color="#00ADB5" density="compact" clearable :error-messages="error_message"></v-autocomplete>
          </v-col>

        </v-row>

        <v-row>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Customer:" v-model="filter.customer" placeholder="Select" variant="underlined"
              :items="customer_store.collection" item-title="name" item-value="id" color="#00ADB5" density="compact"
              clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Supplier:" v-model="filter.supplier" placeholder="Select" variant="underlined"
              item-title="name" item-value="id" :items="supplier_store.collection" color="#00ADB5" density="compact"
              clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-text-field label="Offer ID:" v-model="filter.offer_id" variant="underlined" color="#00ADB5"
              density="compact" clearable></v-text-field>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-text-field label="Order ID:" v-model="filter.order_id" variant="underlined" color="#00ADB5"
              density="compact" clearable></v-text-field>
          </v-col>

        </v-row>

        <v-row>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Status:" v-model="filter.status" placeholder="Select" variant="underlined"
              :items="statuses" item-title="name" item-value="id" color="#00ADB5" density="compact"
              clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-autocomplete label="Availability:" v-model="filter.availability" placeholder="Select"
              variant="underlined" item-title="name" item-value="id" :items="availabilities" color="#00ADB5"
              density="compact" clearable></v-autocomplete>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-text-field label="Offer Serial number:" v-model="filter.offer_serial_number" variant="underlined"
              color="#00ADB5" density="compact" clearable></v-text-field>
          </v-col>
          <v-col class=" py-1" cols="12" md="3" sm="6">
            <v-checkbox label="Exclude Delivered" v-model="filter.exclude_delivered" color="#00ADB5" density="compact"
              hide-details></v-checkbox>
          </v-col>
        </v-row>

        <v-row>
          <v-col class="d-flex justify-end">
            <v-btn class="mx-2" @click="clear">Clear</v-btn>
            <v-btn color="primary" @click="handleFilter" class="mx-2">Apply</v-btn>
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
import { storeToRefs } from "pinia";
//stores
import { modelStore } from "@/stores/model";
import { orderStore } from "@/stores/order";

import DatePicker from "../form-elements/DatePicker.vue";

//define props
const props = defineProps({
  panel: Array,
  brand_store: Object,
  supplier_store: Object,
  customer_store: Object,
});
const { panel } = toRefs(props);

const store = orderStore();
const model_store = modelStore();


const { collection: models_collection } = storeToRefs(model_store)

const filter = ref({
  confirm_from: null,
  confirm_to: null,
  expected_arrival_from: null,
  expected_arrival_to: null,
  deadline_from: null,
  deadline_to: null,
  actual_arrival_from: null,
  actual_arrival_to: null,
  payment_deadline_from: null,
  payment_deadline_to: null,
  customer: null,
  supplier: null,
  offer_id: "",
  order_id: "",
  status: null,
  brand: null,
  model: null,
  availability: null,
  offer_serial_number: "",
});

const defaultValue = {
  confirm_from: null,
  confirm_to: null,
  expected_arrival_from: null,
  expected_arrival_to: null,
  deadline_from: null,
  deadline_to: null,
  actual_arrival_from: null,
  actual_arrival_to: null,
  payment_deadline_from: null,
  payment_deadline_to: null,
  customer: null,
  supplier: null,
  offer_id: "",
  order_id: "",
  status: null,
  brand: null,
  model: null,
  availability: null,
  offer_serial_number: "",
};

const statuses = [
  { id: 1, name: "New" },
  { id: 2, name: "Proposed" },
  { id: 3, name: "SM Confirmed" },
  { id: 4, name: "PM Confirmed" },
  { id: 5, name: "Invoice Received" },
  { id: 11, name: "Cancelled" },
  { id: 12, name: "Expired" },
];
const availabilities = [
  { id: 1, name: "In shop" },
  { id: 2, name: "In order" },
  { id: 3, name: "In stock" },
];


function filterValues() {
  const data = {};
  for (const key in filter.value) {
    if (filter.value[key] !== null && filter.value[key] !== "") {
      data[key] = filter.value[key];
    }
  }
  return data;
}

const getModels = async (brand) => {
  if (brand) {
    await model_store.fetchItems(brand);
  }
  else
    models_collection.value = []

  filter.value.model = null
};

const handleFilter = async () => {
  const data = filterValues();
  await store.filterHandler(data);
};


const clear = () => {
  filter.value = Object.assign({}, defaultValue);
  error_message.value = null
};

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
