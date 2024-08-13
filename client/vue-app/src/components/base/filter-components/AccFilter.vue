<template>
  <v-expansion-panels v-model="panel" flat>
    <v-expansion-panel value="filter">
      <v-expansion-panel-text>

        <v-row class="mt-2">

          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <DatePicker v-model="filter.invoice_date_from" label="Date of invoice (from)" color="#00ADB5"  density="compact"/>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <DatePicker v-model="filter.invoice_date_to" label="Date of invoice (to)" color="#00ADB5" density="compact"/>
          </v-col>

          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Invoice Companies:" v-model="filter.invoice_company" placeholder="Select"
              variant="underlined" item-title="company" item-value="id" :items="invoice_companies.collection"
              color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>

        </v-row>
        <v-row>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Customer:" v-model="filter.customer" placeholder="Select" variant="underlined"
              :items="customer_store.collection" item-title="name" item-value="id" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Supplier:" v-model="filter.supplier" placeholder="Select" variant="underlined"
              item-title="name" item-value="id" :items="supplier_store.collection" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Sales Manager:" v-model="filter.sm" placeholder="Select" variant="underlined"
              :items="props.smManagers" item-title="name" item-value="id" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Purchase Manager:" v-model="filter.pm" placeholder="Select" variant="underlined"
              item-title="name" item-value="id" :items="props.pmManagers" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
        </v-row>

        <v-row>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Availability:" v-model="filter.availability" placeholder="Select"
              variant="underlined" item-title="name" item-value="id" :items="availabilities"
              color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Status:" v-model="filter.status" placeholder="Select" variant="underlined"
              :items="statuses" item-title="name" item-value="id" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Brand:" v-model="filter.brand" placeholder="Select" variant="underlined"
              :items="brand_store.collection" item-title="name" item-value="id"
              @update:model-value="(brand) => getModels(brand)" color="#00ADB5" density="compact"></v-autocomplete>
          </v-col>
          <v-col class="py-1" cols="12" lg="3" md="6" sm="6">
            <v-autocomplete label="Model:" v-model="filter.model" placeholder="Select" variant="underlined"
              item-value="reference" item-title="reference" :items="model_store.collection"
              color="#00ADB5" density="compact"></v-autocomplete>
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
import { ref, toRefs, defineProps } from "vue";
import DatePicker from "../form-elements/DatePicker.vue";
//stores
import { modelStore } from "@/stores/model";
import { orderStore } from "@/stores/order";

//define props
const props = defineProps({
  panel: Array,
  brand_store: Object,
  supplier_store: Object,
  customer_store: Object,
  pmManagers: Array,
  smManagers: Array,
  invoice_companies: Object
});
const { panel } = toRefs(props);



const store = orderStore();
const model_store = modelStore();

const filter = ref({
  invoice_date_from: null,
  invoice_date_to: null,
  invoice_company: null,
  customer: null,
  supplier: null,
  availability: null,
  status: null,
  brand: null,
  model: null,

});

const defaultValue = {
  invoice_date_from: null,
  invoice_date_to: null,
  invoice_company: null,
  customer: null,
  supplier: null,
  availability: null,
  status: null,
  brand: null,
  model: null,
};

const statuses = [
  { id: 4, name: "PM Confirmed" },
  { id: 5, name: "Invoice Received" },
  { id: 6, name: "invoice to Supplier Paid" },
  { id: 7, name: "Invoice issued" },
  { id: 8, name: "invoice from Customer Paid" },
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
  await model_store.fetchItems(brand);
};

const handleFilter = async () => {
  const data = filterValues();
  console.log(data);
  await store.filterHandler(data);
};

const clear = () => {
  Object.assign(filter.value, defaultValue);
};
</script>
