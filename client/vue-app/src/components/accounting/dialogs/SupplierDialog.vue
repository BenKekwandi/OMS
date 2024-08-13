<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
                <span class="text-h6 text-uppercase">Supplier</span>
                <v-btn icon="mdi-close" variant="text" @click="dialog = false"></v-btn>
            </v-card-title>
            <v-divider></v-divider>
            <v-container>
                <v-card-text>
                    <v-row dense>
                        <v-col cols="6">
                            <v-text-field readonly label="Name" v-model="supplier.name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Country" v-model="supplier.country.name" readonly></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="6">
                            <v-text-field readonly label="Primary Name" v-model="supplier.primary_name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field readonly label="Tax" v-model="supplier.tax"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="6">
                            <v-text-field readonly label="Email" v-model="supplier.email"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field readonly label="Phone Number" v-model="supplier.phone"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="6">
                            <v-text-field readonly label="Opening Time" type="time"
                                v-model="supplier.opening_time"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field readonly label="Closing Time" type="time"
                                v-model="supplier.closing_time"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="12">
                            <v-select label="Brands" v-model="supplier.brands" item-title="name" item-value="id" chips
                                multiple readonly></v-select>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="12">
                            <v-textarea readonly label="Invoice Delivery Rules" rows="3"
                                v-model="supplier.invoice_delivery_rules"></v-textarea>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="12">
                            <v-textarea readonly label="Billing Address" rows="3"
                                v-model="supplier.address"></v-textarea>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({ supplier: Object });

const dialog = defineModel({ type: Boolean })



</script>