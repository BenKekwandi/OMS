<template>
  <v-expansion-panels v-model="panel" flat>
    <v-expansion-panel value="filter">
      <v-expansion-panel-text>
        <v-container fluid>
          <v-row>
            <v-col cols="12" md="3">
              <v-text-field label="Shippment ID" v-model="filter.shippment_id" variant="underlined" v-bind="props"
                color="#00ADB5">
              </v-text-field>
            </v-col>
            <v-col cols="12" md="3">
              <v-autocomplete label="Shipping Account:" v-model="filter.shipping_account" placeholder="Select"
                variant="underlined" color="#00ADB5"></v-autocomplete>
            </v-col>
            <v-col cols="12" md="3">
              <v-autocomplete label="Status:" v-model="filter.status" placeholder="Select" variant="underlined"
                color="#00ADB5"></v-autocomplete>
            </v-col>
            <v-col cols="12" md="3">
              <v-checkbox label="Exclude Delivered" v-model="filter.exclude_delivered" color="#00ADB5"></v-checkbox>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="3">
              <v-text-field label="Shipping From:" v-model="filter.shipping_from" variant="underlined"
                color="#00ADB5"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field label="Shipping To:" v-model="filter.shipping_to" variant="underlined" v-bind="props"
                color="#00ADB5">
              </v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col class="d-flex justify-end">
              <v-btn class="mx-2" @click="clear">Clear</v-btn>
              <v-btn color="primary" @click="handleFilter" class="mx-2">Apply</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script setup>
//vue and pinia
import { ref, toRefs, defineProps } from "vue";
//date-fns
import format from "date-fns/format";
//stores
import { orderStore } from "@/stores/order";

//define props
const props = defineProps({
  panel: Array,
  // brand_store: Object,
  // supplier_store: Object,
  // customer_store: Object,
  // sm_store: Object,
  // pm_store: Object,
});
const { panel } = toRefs(props);

const store = orderStore();

const filter = ref({
  shippment_id: "",
  shipping_account: null,
  status: null,
  exclude_delivered: false,
  shipping_from: "",
  shipping_to: "",
});

const defaultValue = {
  shippment_id: "",
  shipping_account: null,
  status: null,
  exclude_delivered: false,
  shipping_from: "",
  shipping_to: "",
};

const statuses = [
  "New",
  "Proposed",
  "Confirmed",
  "Invoice Received",
  "Cancelled",
  "Expired",
];

const menus = ref({
  menu_1: false,
  menu_2: false,
});

const formatDate = (val, filterPropName, menuPropName) => {
  filter.value[filterPropName] = format(val, "do MMM yyyy");
  menus.value[menuPropName] = false;
};

function filterValues() {
  const data = {};
  for (const key in filter.value) {
    if (filter.value[key] !== null && filter.value[key] !== "") {
      data[key] = filter.value[key];
    }
  }
  return data;
}

const handleFilter = async () => {
  const data = filterValues();
  console.log(data);
  await store.filterHandler(data);
};

const clear = () => {
  Object.assign(filter.value, defaultValue);
};
</script>
