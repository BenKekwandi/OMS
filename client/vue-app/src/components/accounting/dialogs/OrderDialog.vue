<template>
    <v-dialog v-model="dialog" max-width="1200px">
      <v-card>
        <v-container>
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="text-h5">Order №{{ order.id }}</span>
            <v-btn icon="mdi-close" variant="text" @click="emit('close')"></v-btn>
          </v-card-title>
          <v-divider class="mx-6"></v-divider>
          <v-card-text>
            <v-row>
              <v-col cols="12">
                <div class="d-flex justify-space-between">
                  <span class="text-h5">Order</span>
                  <span class="text-h5">ID #{{ order.id }}</span>
                </div>
                <v-divider
                  :thickness="2"
                  color="#00ADB5"
                  class="border-opacity-100"
                ></v-divider>
                <v-row class="mt-6">
                  <v-col class="py-0">
                    <v-text-field
                      label="Created"
                      v-model="order.created_at"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Shipment Date"
                      v-model="order.shipment_date"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Expected Delivery"
                      v-model="order.expected_delivery_at"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Status"
                      v-model="order.status"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                </v-row>
  
                <v-row>
                  <v-col class="py-0">
                    <v-text-field
                      label="Actual Arrival Date"
                      v-model="order.actual_arrival"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Expected Shipping"
                      v-model="order.shipment_date"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Tracking Number"
                      v-model="order.expected_delivery_at"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Total Expenses"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                </v-row>
  
                <v-row>
                  <v-col class="py-0">
                    <v-text-field
                      label="Profit"
                      v-model="order.proposal.profit"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0">
                    <v-text-field
                      label="Payment Deadline"
                      v-model="order.invoice.payment_deadline"
                      variant="underlined"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col class="py-0"></v-col>
                  <v-col class="py-0"></v-col>
                </v-row>
              </v-col>
              <v-col cols="6">
                <div class="d-flex justify-space-between">
                  <span class="text-h5">Request</span>
                  <span class="text-h5">ID #{{ order.id }}</span>
                </div>
                <v-divider
                  :thickness="2"
                  color="#00ADB5"
                  class="border-opacity-100 mb-2"
                ></v-divider>
                <v-row class="mt-6">
                  <v-col class="py-0">
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Customer"
                          v-model="order.customer.name"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Brand"
                          v-model="order.brand.name"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Model"
                          v-model="order.reference_number"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
  
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Other Features"
                          v-model="order.other_features"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
  
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Deadline Date"
                          v-model="order.deadline"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Name for warranty"
                          v-model="order.customer.name"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col class="py-0 d-flex justify-center">
                    <v-img
                      :src="order.image"
                      width="100"
                      max-height="125"
                      aspect-ratio="1"
                    ></v-img>
                  </v-col>
                </v-row>
              </v-col>
  
              <v-col cols="6">
                <div class="d-flex justify-space-between">
                  <span class="text-h5">Offer</span>
                  <span class="text-h5">ID #{{ order.offer.id }}</span>
                </div>
                <v-divider
                  :thickness="2"
                  color="#00ADB5"
                  class="border-opacity-100 mb-2"
                ></v-divider>
                <v-row class="mt-6">
                  <v-col class="py-0">
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Supplier"
                          v-model="order.supplier.name"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Brand"
                          v-model="order.brand.name"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Model"
                          v-model="order.reference_number"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Other Features"
                          v-model="order.offer.other_features"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Sell Price"
                          v-model="order.proposal.sell_price"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Net Price"
                          v-model="order.offer.net_price"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="RRP"
                          v-model="order.offer.rrp_price"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Availability"
                          v-model="order.offer.availability"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="py-0">
                        <v-text-field
                          label="Delivery"
                          variant="underlined"
                          readonly
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col class="py-0 d-flex justify-center">
                    <v-img
                      :src="order.offer.image"
                      width="100"
                      max-height="125"
                      aspect-ratio="1"
                    ></v-img>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-card-text>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { defineProps, toRefs } from "vue";
  
  const props = defineProps({ order: Object, dialog: Boolean });
  const { order, dialog } = toRefs(props);
  
  const emit = defineEmits(["close"]);
  </script>