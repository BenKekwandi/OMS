<template>
  <v-dialog v-model="dialog" max-width="1200px">
    <v-card class="no-scrollbar">
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <span class="text-h6 text-uppercase">Order #<span class="font-weight-bold">{{ safeOrder.id }}</span></span>
        <v-btn icon="mdi-close" size="small" variant="text" @click="dialog = false"></v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-container>

        <v-card-text>
          <div class="d-flex justify-space-between">
            <span class="text-subtitle-1 text-uppercase">Order</span>
            <span class="text-subtitle-1 text-uppercase">ID #<span class="font-weight-bold">{{ safeOrder.id}}</span></span>
          </div>
          <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100"></v-divider>
          <v-row dense class="mt-6">
            <v-col cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Created" v-model="safeOrder.created_at" variant="underlined"
                readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Shipment Date" v-model="safeOrder.shipment_date"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Expected Delivery" v-model="safeOrder.expected_delivery_at"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Status" v-model="safeOrder.status" variant="underlined"
                readonly></v-text-field>
            </v-col>
          </v-row>

          <v-row dense>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Actual Arrival Date" v-model="safeOrder.actual_arrival"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Expected Shipping" v-model="safeOrder.expected_shipment_at"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Tracking Number" v-model="safeOrder.tracking_number"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Total Expenses" v-model="safeOrder.total_expenses"
                variant="underlined" readonly></v-text-field>
            </v-col>
          </v-row>

          <v-row dense>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Profit" v-model="safeOrder.proposal.profit"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col  cols="12" sm="6" md="3" class="pr-4">
              <v-text-field density="comfortable" label="Payment Deadline"
                v-model="safeOrder.supplier_invoice.payment_deadline" variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col class="pr-4"></v-col>
            <v-col class="pr-4"></v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <div class="d-flex justify-space-between">
                <span class="text-subtitle-1 text-uppercase">Request</span>
                <span class="text-subtitle-1 text-uppercase">ID #<span class="font-weight-bold">{{ safeOrder.id
                    }}</span></span>
              </div>
              <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100"></v-divider>
              <v-row class="mt-6">
                <v-col cols="12" sm="12" md="6" class="pr-4">
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Customer" v-model="safeOrder.customer.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Brand" v-model="safeOrder.brand.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Model" v-model="safeOrder.reference_number"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Other Features" v-model="safeOrder.other_features"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Deadline Date" v-model="safeOrder.deadline"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Name for warranty" v-model="safeOrder.customer.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" sm="12" md="6" class="py-0 d-flex justify-center">
                  <v-img :src="safeOrder.image" width="100" max-height="125" aspect-ratio="1"></v-img>
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="12" sm="6">
              <div class="d-flex justify-space-between">
                <span class="text-subtitle-1 text-uppercase">Offer</span>
                <span class="text-subtitle-1 text-uppercase">ID #<span class="font-weight-bold">{{ safeOrder.offer.id
                    }}</span></span>
              </div>
              <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100"></v-divider>
              <v-row class="mt-6">
                <v-col cols="12" sm="12" md="6" class="pr-4">
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Supplier" v-model="safeOrder.supplier.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Brand" v-model="safeOrder.brand.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Model" v-model="safeOrder.reference_number"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Other Features"
                        v-model="safeOrder.offer.other_features" variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Sell Price" v-model="safeOrder.proposal.sell_price"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Net Price" v-model="safeOrder.offer.net_price"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="RRP" v-model="safeOrder.offer.rrp_price"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Availability" v-model="safeOrder.offer.availability"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col class="pr-4">
                      <v-text-field density="comfortable" label="Delivery" v-model="safeOrder.offer.delivery"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" sm="12" md="6" class="py-0 d-flex justify-center">
                  <v-img :src="safeOrder.offer.image" width="100" max-height="125" aspect-ratio="1"></v-img>
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
import { defineProps, computed } from "vue";

const props = defineProps({ order: Object })

const dialog = defineModel({ type: Boolean })




const safeOrder = computed(() => ({
  id: props.order.id,
  created_at: props.order.created_at,
  shipment_date: props.order.shipment?.created_at || '-',
  expected_shipment_at: props.order.shipment?.deadline || '-',
  expected_delivery_at: props.order.shipment.label?.expected_delivery_at || '-',
  status: props.order.status,
  actual_arrival: props.order.shipment?.collected_at || '-',
  tracking_number: props.order.shipment.label?.tracking_number || '-',
  total_expenses: props.order?.total_expenses || '0',
  proposal: {
    profit: props.order?.proposal?.profit || '-',
    sell_price: props.order?.proposal?.sell_price || '-',
  },
  supplier_invoice: {
    payment_deadline: props.order?.supplier_invoice?.payment_deadline || '-',
  },
  customer: {
    name: props.order?.customer?.name || '-',
  },
  brand: {
    name: props.order?.brand?.name || '-',
  },
  reference_number: props.order?.reference_number || '-',
  other_features: props.order?.other_features || '-',
  deadline: props.order?.deadline || '-',
  image: props.order?.image || '-',
  offer: {
    id: props.order?.offer?.id || '',
    other_features: props.order?.offer?.other_features || '-',
    net_price: props.order?.offer?.net_price || '-',
    rrp_price: props.order?.offer?.rrp_price || '-',
    availability: props.order?.offer?.availability || '-',
    image: props.order?.offer?.image || '-',
    delivery: props.order?.offer?.delivery || '-'
  },
  supplier: {
    name: props.order?.supplier?.name || '-',
  },
}))
</script>
