<template>
    <v-expansion-panels v-model="panel" flat>
        <v-expansion-panel value="filter">
            <v-expansion-panel-text>
                <v-container fluid>
                    <v-row>
                        <v-col cols="12" md="3">
                            <v-autocomplete label="Delivery Type:" v-model="filter.delivery_type" placeholder="Select"
                                variant="underlined" color="#00ADB5"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-autocomplete label="Select SAM:" v-model="filter.sam" placeholder="Select"
                                variant="underlined" color="#00ADB5"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="3">

                            <DatePicker v-model="filter.shipment_date_from" label="Shipment date (from):" color="#00ADB5" />
                        </v-col>
                        <v-col cols="12" md="3">

                            <DatePicker v-model="filter.shipment_date_to" label="Shipment date (to):" color="#00ADB5"/>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field label="Customer:" v-model="filter.customer" variant="underlined"
                                color="#00ADB5"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-autocomplete label="Supplier:" v-model="filter.supplier" placeholder="Select"
                                variant="underlined" color="#00ADB5"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field label="Order ID" v-model="filter.order_id" variant="underlined" v-bind="props"
                                color="#00ADB5">
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-autocomplete label="Status:" v-model="filter.status" placeholder="Select"
                                variant="underlined" color="#00ADB5"></v-autocomplete>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field label="Model:" v-model="filter.model" variant="underlined"
                                color="#00ADB5"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-autocomplete label="Brand:" v-model="filter.brand" placeholder="Select"
                                variant="underlined" color="#00ADB5"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field label="Customer Order Number" v-model="filter.customer_order_number"
                                variant="underlined" v-bind="props" color="#00ADB5">
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-checkbox label="Exclude Delivered" v-model="filter.exclude_delivered"
                                color="#00ADB5"></v-checkbox>
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
import { modelStore } from "@/stores/model";
import { orderStore } from "@/stores/order";

import DatePicker from "../form-elements/DatePicker.vue";


//define props
const props = defineProps({
    panel: Array,
});
const { panel } = toRefs(props);

const store = orderStore();
const model_store = modelStore();

const filter = ref({
    delivery_type: null,
    sam: null,
    shipment_date_from: null,
    shipment_date_to: null,
    customer: null,
    supplier: null,
    status: null,
    order_id: '',
    model: null,
    brand: null,
    customer_order_number: null,
    exclude_delivered: false
});

const defaultValue = {
    delivery_type: null,
    sam: null,
    shipment_date_from: null,
    shipment_date_to: null,
    customer: null,
    supplier: null,
    status: null,
    order_id: '',
    model: null,
    brand: null,
    customer_order_number: null,
    exclude_delivered: false
};

const statuses = [
    "New",
    "Proposed",
    "Confirmed",
    "Invoice Received",
    "Cancelled",
    "Expired",
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