<template>
  <v-dialog v-model="dialog" max-width="1000" transition="dialog-top-transition">
    <v-card class="no-scrollbar">
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <div class="text-h6 text-uppercase">
          Label Management
          <br v-if="$vuetify.display.sm || $vuetify.display.xs" />
          For manual shipment
          <span class="font-weight-bold text-primary"><br v-if="$vuetify.display.xs" />
            #{{ label.shipment_id }}</span>
        </div>

        <v-btn icon="mdi-close" size="small" variant="text" @click="dialog = false"></v-btn>
      </v-card-title>
      <v-divider class="mb-4"></v-divider>
      <v-card-text>
        <div v-if="shipment?.label" class="text-uppercase text-body-1">
          Current Label
        </div>
        <v-divider v-if="shipment?.label" class="my-2" color="#00ADB5" opacity="100"></v-divider>

        <v-row dense>
          <v-col v-if="shipment?.label" cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Created At
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-text-field density="compact" v-model="label.created_at" variant="underlined" :readonly="!!label.id">
                </v-text-field>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Shipping Service
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-select density="compact" item-title="title" item-value="id" v-model="label.shipment_service_id"
                  variant="underlined" :items="shipmentServiceStore.collection" readonly menu-icon=""
                  :readonly="!!label.id">
                </v-select>
              </v-col>
            </v-row>
          </v-col>
          <v-col v-if="shipment?.label" cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Updated At
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-text-field density="compact" v-model="label.updated_at" variant="underlined"
                  :readonly="!!label.id"></v-text-field>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Shipping Account
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-select density="compact" variant="underlined" v-model="label.shipment_account_id"
                  :items="shipmentAccountStore.collection" menu-icon="" item-title="title" item-value="id" readonly>
                </v-select>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Expected Collection At
                </div>
              </v-col>

              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-text-field v-if="label.id" density="compact" variant="underlined"
                  v-model="label.expected_collection_at" readonly></v-text-field>

                <DatePicker v-else density="compact" v-model="label.expected_collection_at" />
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">Amount</div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-text-field density="compact" variant="underlined" v-model="label.amount"
                  :readonly="!!label.id"></v-text-field>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Expected Delivery At
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-text-field v-if="label.id" density="compact" variant="underlined"
                  v-model="label.expected_delivery_at" readonly></v-text-field>

                <DatePicker v-else density="compact" v-model="label.expected_delivery_at" />
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Tracking Number
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2"><v-text-field
                  density="compact" variant="underlined" v-model="label.tracking_number"
                  :readonly="!!label.id"></v-text-field>
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" md="6" sm="6">
            <v-row dense>
              <v-col cols="12" md="5" class="d-flex align-center justify-md-end justify-start">
                <div class="text-body-2 text-high-emphasis mr-6">
                  Upload Label
                </div>
              </v-col>
              <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                <v-file-input density="compact" variant="underlined" prepend-icon="mdi-file-download-outline"
                  @change="handleFileData"></v-file-input>
              </v-col>
            </v-row>
          </v-col>
        </v-row>


        <!-- //INVOICE -->
        <v-checkbox label="Attach Invoice to label" color="#00ADB5" v-model="isInvoiceAttached"
          v-if="!shipment?.label"></v-checkbox>

        <v-fade-transition hide-on-leave>
         
          <div v-if="isInvoiceAttached">
            <div class="text-uppercase text-body-1">Invoice Details</div>
            <v-divider class="my-2" color="#00ADB5" opacity="100"></v-divider>

            <v-row dense>
              <v-col cols="12" md="6" sm="6">
                <v-row dense>
                  <v-col cols="12" md="5" class="d-flex align-end justify-md-end justify-start">
                    <div class="text-body-2 text-high-emphasis mr-6">
                      Invoice Date
                    </div>
                  </v-col>
                  <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                    <DatePicker density="compact" hide_details v-model="invoice.date" />
                  </v-col>
                </v-row>
              </v-col>

              <v-col cols="12" md="6" sm="6">
                <v-row dense>
                  <v-col cols="12" md="5" class="d-flex align-end justify-md-end justify-start">
                    <div class="text-body-2 text-high-emphasis mr-6">
                      Serial Number
                    </div>
                  </v-col>
                  <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                    <v-text-field density="compact" hide-details variant="underlined"
                      v-model="invoice.serial_number"></v-text-field>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="6" sm="6">
                <v-row dense>
                  <v-col cols="12" md="5" class="d-flex align-end justify-md-end justify-start">
                    <div class="text-body-2 text-high-emphasis mr-6">Type</div>
                  </v-col>
                  <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                    <v-select density="compact" hide-details variant="underlined" v-model="invoice.type"
                      :items="invoiceTypes" item-value="value"></v-select>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="6" sm="6">
                <v-row dense>
                  <v-col cols="12" md="5" class="d-flex align-end justify-md-end justify-start">
                    <div class="text-body-2 text-high-emphasis mr-6">
                      Expected Delivery At
                    </div>
                  </v-col>
                  <v-col cols="12" md="6" class="d-flex align-end justify-md-center justify-start mt-2">
                    <DatePicker density="compact" hide_details v-model="invoice.expected_delivery_at" />
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
            <br>
            <br>
          </div>
        </v-fade-transition>

        <v-row class="mt-6 mx-2" no-gutters>
          <v-col cols="12" sm="4">
            <v-fade-transition hide-on-leave>
              <div>
                <DatePicker v-model="collected_at" placeholder="Collected At"
                v-if="shipment.status === 'Label Created'" />
              </div>
             
            </v-fade-transition>
            <v-fade-transition hide-on-leave>
              <div>
                <DatePicker v-model="delivered_at" placeholder="Delivered At" v-if="shipment.status === 'Collected'" />
              </div>
             
            </v-fade-transition>
          </v-col>
        </v-row>

        <v-row class="my-2 mx-2">
          <v-fade-transition hide-on-leave>
            
            <div v-if="shipment.status === 'Collected'">
              <v-btn color="green-darken-1" @click="setDelivered" :loading="loading_delivered_at" :disabled="!delivered_at">
                Set Delivered
              </v-btn>
              <v-btn class="ml-2" color="blue-darken-1" @click="setDeliveredToCustomer" :loading="loading_delivered_at"
                :disabled="!delivered_at">
                Set Delivered To Customer
              </v-btn>
            </div>
          </v-fade-transition>
          <v-fade-transition hide-on-leave>
           
            <div v-if="shipment.status === 'Label Created'">
              <v-btn color="green-darken-1" @click="setCollected" :loading="loading_collected_at" :disabled="!collected_at">
                Set Collected
              </v-btn>
            </div>
          </v-fade-transition>

          <v-spacer></v-spacer>
          <div>
            <v-fade-transition hide-on-leave>
              <div>
                <v-btn v-if="shipment.status === 'Label Created'" class=" mr-2" color="orange-darken-4"
                text="Cancel Label" @click="cancelLabel" :loading="loading"></v-btn>
              </div>
   
            </v-fade-transition>
            <v-fade-transition hide-on-leave>
              <div>
                <v-btn v-if="!shipment.label" color="primary" text="Create Label" @click="createLabel"
                :loading="loading"></v-btn>
              </div>
             
            </v-fade-transition>

            <v-fade-transition hide-on-leave>
              <div>
                <v-btn v-if="shipment.status !== 'Label Created' && shipment.status !== 'New'" color="orange-darken-1" @click="setBack"
                :loading="loading_step_back">
                Step Back
              </v-btn>
              </div>
             
            </v-fade-transition>
          </div>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { useShipmentServiceStore } from "@/stores/shipment-services";
import { useShipmentAccountStore } from "@/stores/shipment-accounts";
import { useShipmentStore } from "@/stores/shipment";
import { useLabelStore } from "@/stores/label";
import DatePicker from "../form-elements/DatePicker.vue";
import { ref, watch } from "vue";
import { storeToRefs } from "pinia";


const shipmentServiceStore = useShipmentServiceStore();
const shipmentAccountStore = useShipmentAccountStore();
const labelStore = useLabelStore();
const shipmentStore = useShipmentStore()

const { loading, errors, res, loading_collected_at, loading_step_back, loading_delivered_at } = storeToRefs(labelStore);

const dialog = defineModel({ type: Boolean });
const props = defineProps({ shipment: Object });
const emits = defineEmits(["initialize", "alert", "closeNewShipmentDialog"]);

const collected_at = ref(null);
const delivered_at = ref(null);

const label = ref({
  shipment_id: null,
  shipment_account_id: null,
  shipment_service_id: null,
  expected_collection_at: null,
  expected_delivery_at: null,
  amount: "",
  tracking_number: "",
  file: null,
});

const defaultLabel = ref({
  shipment_id: null,
  shipment_account_id: null,
  shipment_service_id: null,
  expected_collection_at: null,
  expected_delivery_at: null,
  amount: "",
  tracking_number: "",
  file: null,
});

const invoice = ref({
  date: null,
  serial_number: "",
  type: null,
  expected_delivery_at: null,
});

const isInvoiceAttached = ref(false);

const invoiceTypes = ref([
  { title: "Commercial", value: 1 },
  { title: "Performa", value: 2 },
]);

watch(
  () => props.shipment,
  (val) => {
    if (val.id) {
      if (val.label) {
        label.value = Object.assign({}, val.label);
      }


      label.value.shipment_id = val.id;
      label.value.shipment_account_id = val.shipment_account.id;
      label.value.shipment_service_id =
        val.shipment_account.shipment_services.id;
    }
  }
);

const handleFileData = (event) => {
  label.value.file = event.target.files[0];
};

function getFormType() {
  const formData = new FormData();
  for (const key in label.value) {
    formData.append(key, label.value[key]);
  }
  return formData;
}

const setCollected = async () => {
  await labelStore.handleSetCollectedAt(label.value.shipment_id, {
    collected_at: collected_at.value,
  });
  if (res.value) {
    shipmentStore.fetchShipment(label.value.shipment_id)
    emits("alert", res.value.message, res.value.status);
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

const setDelivered = async () => {
  await labelStore.handleSetDeliveredAt(label.value.shipment_id, {
    delivered_at: delivered_at.value,
  });
  if (res.value) {
    shipmentStore.fetchShipment(label.value.shipment_id)
    emits("alert", res.value.message, res.value.status);
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

const setDeliveredToCustomer = async () => {
  await labelStore.handleSetDeliveredAt(label.value.shipment_id, {
    delivered_at: delivered_at.value,
    customer: true,
  });
  if (res.value) {
    shipmentStore.fetchShipment(label.value.shipment_id)
    emits("alert", res.value.message, res.value.status);
  
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

const setBack = async () => {
  await labelStore.handleStepBack(label.value.id);
  if (res.value) {
    shipmentStore.fetchShipment(label.value.shipment_id)
    emits("alert", res.value.message, res.value.status);
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

const createLabel = async () => {
  const data = getFormType();
  await labelStore.handleAddedLabel(
    isInvoiceAttached.value,
    invoice.value,
    data
  );

  if (res.value) {
    emits("initialize");
    emits("alert", res.value.message, res.value.status);
    close();
    emits("closeNewShipmentDialog");
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

const cancelLabel = async () => {

  await labelStore.handleDeletedLabels(label.value.id);

  if (res.value) {
    emits("initialize");
    emits("alert", res.value.message, res.value.status);
    close()
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

function close() {

  label.value = Object.assign({}, defaultLabel.value);
  delivered_at.value = null
  collected_at.value = null
  dialog.value = false;
}

watch(dialog, (val) => {
  val || close();
});
</script>

<style scoped></style>
