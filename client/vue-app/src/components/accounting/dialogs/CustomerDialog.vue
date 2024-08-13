<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
        <v-container>
            <v-card-title class="d-flex justify-space-between align-center">
                <span class="text-h5">Customer</span>
                <v-btn icon="mdi-close" variant="text" @click="emit('close')"></v-btn>
            </v-card-title>
            <v-divider class="my-2 mx-4"></v-divider>

            <v-card-text>
            <v-row>
                <v-col cols="6">
                <v-text-field
                    label="Name"
                    prepend-inner-icon="mdi-account-circle"
                    v-model="customer.name"
                ></v-text-field>
                </v-col>
                <v-col cols="6" sm="15" md="15">
                <v-text-field
                    label="Email"
                    prepend-inner-icon="mdi-at"
                    v-model="customer.email"
                ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="6">
                <v-autocomplete
                    label="Country"
                    prepend-inner-icon="mdi-earth"
                    v-model="customer.country.name"
                    item-title="name"
                    item-value="id"
                ></v-autocomplete>
                </v-col>
                <v-col cols="6">
                <v-text-field
                    label="Phone Number"
                    prepend-inner-icon="mdi-phone"
                    v-model="customer.phone"
                ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                <v-textarea
                    label="Shipping Address"
                    rows="3"
                    prepend-inner-icon="mdi-map-marker"
                    v-model="customer.shipping_address"
                ></v-textarea>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                <v-textarea
                    label="Billing Address"
                    rows="3"
                    prepend-inner-icon="mdi-map-marker"
                    v-model="customer.billing_address"
                ></v-textarea>
                </v-col>
            </v-row>
            </v-card-text>
        </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { defineProps, toRefs } from "vue";

const props = defineProps({ customer: Object, dialog: Boolean });
const { customer, dialog } = toRefs(props);

const emit = defineEmits(["close"]);

</script>