<template>
  <v-dialog v-model="dialog" max-width="1000px">
    <v-card>
      <v-container>

        <v-card-title class="d-flex align-center">
          <v-row justify="space-between">
            <v-col align-self="center">
              <span class="text-h6 text-uppercase">Proposal
                <span class="font-weight-bold">#{{ proposal.id }}</span> <br v-if="$vuetify.display.xs"> -
                <span class="text-disabled">{{ proposal.status }}</span> </span>
            </v-col>

            <v-col align-self="center" class=" px-0 mx-0">
              <div v-if="!permission" class="text-end">
                <v-btn class="mr-2" prepend-icon="mdi-file-cancel-outline" variant="outlined" color="red-darken-1"
                  @click="emit('cancelProposal', proposal)" size="small">Cancel</v-btn>
                <v-btn class="mr-2" prepend-icon="mdi-file-check-outline" variant="outlined" color="green-darken-1"
                  @click="emit('confirmProposal', proposal)" size="small">Confirm</v-btn>
                <!-- <v-btn icon="mdi-close" variant="text" size="small" @click="dialog = false"></v-btn> -->
              </div>

              <div v-if="permission" class="text-end">

                <v-btn :disabled="proposal.status !== 'Awaits PM confirmation'" prepend-icon="mdi-file-cancel-outline"
                  variant="outlined" color="red-darken-1" @click="emit('cancelOffer', proposal)" size="small"
                  class="mr-2">Cancel
                  Offer</v-btn>
                <br v-if="$vuetify.display.xs">
                <v-btn :disabled="proposal.status === 'Cancelled' || proposal.status === 'Completed'"
                  prepend-icon="mdi-file-cancel-outline" variant="outlined" color="red-darken-1"
                  @click="emit('cancelOrder', proposal)" size="small" class="mr-2">Cancel Order</v-btn>
                <br v-if="$vuetify.display.xs">
                <v-btn :disabled="proposal.status === 'Cancelled' || proposal.status === 'Completed'"
                  prepend-icon="mdi-file-check-outline" variant="outlined" color="green-darken-1"
                  @click="emit('confirmProposal', proposal)" size="small" class="mr-2">Confirm</v-btn>
              </div>
            </v-col>
            <!-- <v-col cols="2" class="text-end">
             
            </v-col> -->
          </v-row>

          <!-- <v-btn class="position-absolute" icon="mdi-close" variant="text" size="small" @click="dialog = false"></v-btn> -->


        </v-card-title>
        <V-divider></V-divider>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="6" sm="12">

              <div class="d-flex justify-space-between">
                <span class="text-h6">Order</span>
                <span class="text-h6">ID <span class="font-weight-bold">#{{ proposal.order.id }}</span></span>
              </div>
              <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100 my-2"></v-divider>
              <v-row class="mt-8">
                <v-col class="py-0">
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Customer" v-model="proposal.order.customer.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Brand" v-model="proposal.offer.brand.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Model" v-model="proposal.order.reference_number"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Other Features" v-model="proposal.order.other_features"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col class="py-0">

                      <DatePicker v-model="editedItem.proposal.deadline" label="Deadline Date" v-if="permission"
                        @dateForRule="confirmSave" />

                      <v-text-field density="comfortable" v-else label="Deadline Date"
                        v-model="editedItem.proposal.deadline" variant="underlined"
                        :readonly="!permission"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Name for warranty"
                        v-model="editedItem.proposal.name_for_warranty" variant="underlined"
                        @update:modelValue="confirmSave" :append-inner-icon="permission ? 'mdi-pencil' : ''"
                        :readonly="!permission"></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col class="py-0 d-flex justify-center">
                  <v-img :src="proposal.order.image" width="100" max-height="125" aspect-ratio="1"></v-img>
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="12" md="6" sm="12">
              <div class="d-flex justify-space-between">
                <span class="text-h6">Offer</span>
                <span class="text-h6">ID <span class="font-weight-bold">#{{ proposal.offer.id }}</span></span>
              </div>
              <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100 my-2"></v-divider>
              <v-row class="mt-8">
                <v-col class="py-0">
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Supplier" v-model="proposal.offer.supplier.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Brand" v-model="proposal.offer.brand.name"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Model" v-model="proposal.offer.reference_number"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Other Features" v-model="proposal.offer.other_features"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Sell Price" v-model="editedItem.proposal.sell_price"
                        variant="underlined" :readonly="!permission" :append-inner-icon="permission ? 'mdi-pencil' : ''"
                        @update:modelValue="confirmSave"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Net Price" v-model="editedItem.proposal.net_price"
                        variant="underlined" :readonly="!permission" :append-inner-icon="permission ? 'mdi-pencil' : ''"
                        @update:modelValue="confirmSave"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="RRP" v-model="proposal.offer.rrp_price"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Availability" v-model="proposal.offer.availability"
                        variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="py-0">
                      <v-text-field density="comfortable" label="Delivery" variant="underlined" readonly></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col class="py-0 d-flex justify-center">
                  <v-img :src="proposal.offer.image" width="100" max-height="125" aspect-ratio="1"></v-img>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

        </v-card-text>
        <v-card-actions class="mx-2 my-4" v-if="permission">
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="text" @click="dialog = false">
            Cancel
          </v-btn>
          <v-btn class="px-4" color="primary" variant="elevated" :loading="loading" @click="emit('update', editedItem)"
            :disabled="disableSave">
            Save
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { defineProps, toRefs, watch, ref } from "vue";
import DatePicker from "../form-elements/DatePicker.vue";

const dialog = defineModel({ type: Boolean })

const props = defineProps({ proposal: Object, permission: Boolean, loading: Boolean });
const { proposal } = toRefs(props);

const emit = defineEmits(["close", "update", "cancelProposal", "confirmProposal", "cancelOrder", "cancelOffer"]);

const editedItem = ref({
  id: '',
  proposal: {
    name_for_warranty: '',
    deadline: '',
    net_price: '',
    sell_price: ''
  }
})

const defaultItem = ref({
  id: '',
  proposal: {
    name_for_warranty: '',
    deadline: '',
    net_price: '',
    sell_price: ''
  }
})

const disableSave = ref(true)

watch(() => dialog.value, (val) => {
  if (!val)
    disableSave.value = true
  editedItem.value = Object.assign({}, defaultItem.value)
})

watch(() => proposal.value, (val) => {
  editedItem.value.id = val.id;
  editedItem.value.proposal.name_for_warranty = val.order.name_for_warranty;
  editedItem.value.proposal.deadline = val.order.deadline;
  editedItem.value.proposal.net_price = val.offer.net_price;
  editedItem.value.proposal.sell_price = val.sell_price;
})

const confirmSave = () => {
  disableSave.value = false
}
</script>
