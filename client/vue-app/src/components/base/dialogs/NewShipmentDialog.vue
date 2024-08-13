<template>
  <v-dialog v-model="dialogNewShipment" max-width="900px">
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <span v-if="shipmentId" class="text-h6">EDIT SHIPMENT
          <span class="font-weight-bold text-primary">#{{ shipmentId }}</span>
        </span>
        <span v-else class="text-h6">CREATE NEW SHIPMENT</span>
        <v-btn icon="mdi-close" variant="text" size="small" @click="close"></v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-container>
        <v-card-text class="mx-4">
          <v-row dense align="center">
            <v-col class="px-md-4" cols="12" sm="6">
              <v-select color="#00ADB5" v-model="editedItem.shipping_service" label="Shipping Service"
                :items="shipmentServiceStore.collection" item-title="title" item-value="id" variant="underlined"
                density="compact" @update:modelValue="getShipmentAccount" clearable>
              </v-select>

              <v-select color="#00ADB5" label="Shipping Account" :items="shipmentAccountStore.collection"
                item-title="title" item-value="id" variant="underlined" density="compact"
                v-model="editedItem.shipment_account_id" clearable></v-select>
            </v-col>

            <v-col cols="12" sm="6" class="d-flex align-center px-md-4">
              <v-radio-group v-model="editedItem.automatic_shipping" density="compact">
                <template v-slot:label>
                  <div class="text-body-2 text-medium-emphasis text-start">
                    Automatic Ship:
                  </div>
                </template>
                <v-radio class="ml-4" :value="true" color="#00ADB5">
                  <template v-slot:label>
                    <div class="text-body-2 text-high-emphasis">Enabled</div>
                  </template>
                </v-radio>
                <v-radio class="ml-4" :value="false" color="#00ADB5">
                  <template v-slot:label>
                    <div class="text-body-2 text-high-emphasis">Disabled</div>
                  </template>
                </v-radio>
              </v-radio-group>

              <v-radio-group v-model="editedItem.shipping_type" density="compact">
                <template v-slot:label>
                  <div class="text-body-2 text-medium-emphasis text-start">
                    Shipping Type:
                  </div>
                </template>
                <v-radio class="ml-4" value="incoming" color="#00ADB5">
                  <template v-slot:label>
                    <div class="text-body-2 text-high-emphasis">Incoming</div>
                  </template>
                </v-radio>
                <v-radio class="ml-4" value="outgoing" color="#00ADB5">
                  <template v-slot:label>
                    <div class="text-body-2 text-high-emphasis">Outgoing</div>
                  </template>
                </v-radio>
              </v-radio-group>
            </v-col>
          </v-row>

          <v-row dense align="center">
            <v-col class="px-md-4" cols="12" sm="6">
              <DatePicker color="#00ADB5" density="compact" label="Pickup Time" v-model="editedItem.pick_up_time"
                clearable />

              <DatePicker color="#00ADB5" density="compact" label="Deadline" v-model="editedItem.deadline" clearable />
            </v-col>
            <v-col class="px-md-4" cols="12" sm="6">
              <v-select color="#00ADB5" label="Ship From" variant="underlined" density="compact"
                v-model="editedItem.ship_from_id" clearable item-title="display" item-value="id"
                :items="formattedAddresses" @update:modelValue="updateShipFromAddress"></v-select>

              <v-select color="#00ADB5" label="Ship To" :items="formattedAddresses" item-title="display" item-value="id"
                variant="underlined" density="compact" v-model="editedItem.ship_to_id" clearable
                @update:modelValue="updateShipToAddress"></v-select>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-title class="d-flex justify-space-between align-center mb-0 pb-1">
          <span class="text-subtitle-1">ADDRESS DETAILS</span>
          <v-btn :text="active ? 'HIDE' : 'SHOW'" :active="active"
            :prepend-icon="active ? 'mdi-chevron-up' : 'mdi-chevron-down'" variant="text"
            @click="showAddressDetails"></v-btn>
        </v-card-title>
        <v-divider color="#00ADB5" opacity="100" class="mx-4"></v-divider>
        <v-card-text>
          <v-expansion-panels v-model="panel" flat>
            <v-expansion-panel value="address">
              <v-expansion-panel-text>
                <v-row>
                  <v-col cols="12" sm="6">
                    <p class="text-subtitle-2">Ship Form:</p>
                    <br />
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipFromAddress.contact_Name" :readonly="isShipFromCustomDisabled">
                      <template v-slot:label>
                        <span>
                          Contact Name <span class="text-red">*</span>
                        </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Company" variant="underlined"
                      v-model="newShipFromAddress.company" :readonly="isShipFromCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipFromAddress.street_1" :readonly="isShipFromCustomDisabled">
                      <template v-slot:label>
                        <span> Street 1 <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Street 2" variant="underlined"
                      v-model="newShipFromAddress.street_2" :readonly="isShipFromCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Street 3" variant="underlined"
                      v-model="newShipFromAddress.street_3" :readonly="isShipFromCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipFromAddress.city" :readonly="isShipFromCustomDisabled">
                      <template v-slot:label>
                        <span> City <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="State" variant="underlined"
                      v-model="newShipFromAddress.state" :readonly="isShipFromCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Postal Code" variant="underlined"
                      v-model="newShipFromAddress.post_code" :readonly="isShipFromCustomDisabled"><template
                        v-slot:label>
                        <span>
                          Postal Code<span class="text-red">*</span>
                        </span>
                      </template></v-text-field>
                    <v-text-field color="#00ADB5" variant="underlined" density="compact"
                      v-model="newShipFromAddress.country" :readonly="isShipFromCustomDisabled">
                      <template v-slot:label>
                        <span> Country <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Tax ID" variant="underlined"
                      v-model="newShipFromAddress.tax" :readonly="isShipFromCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipFromAddress.email" :readonly="isShipFromCustomDisabled">
                      <template v-slot:label>
                        <span> Email <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Phone" variant="underlined"
                      v-model="newShipFromAddress.phone" :readonly="isShipFromCustomDisabled"><template v-slot:label>
                        <span> Phone<span class="text-red">*</span> </span>
                      </template></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <p class="text-subtitle-2">Ship To:</p>
                    <br />
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipToAddress.contact_Name" :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span>
                          Contact Name <span class="text-red">*</span>
                        </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Company" variant="underlined"
                      v-model="newShipToAddress.company" :readonly="isShipToCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipToAddress.street_1" :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span> Street 1 <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Street 2" variant="underlined"
                      v-model="newShipToAddress.street_2" :readonly="isShipToCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Street 3" variant="underlined"
                      v-model="newShipToAddress.street_3" :readonly="isShipToCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined" v-model="newShipToAddress.city"
                      :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span> City <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="State" variant="underlined"
                      v-model="newShipToAddress.state" :readonly="isShipToCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipToAddress.post_code" :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span>
                          Postal Code <span class="text-red">*</span>
                        </span>
                      </template></v-text-field>
                    <v-text-field color="#00ADB5" variant="underlined" density="compact"
                      v-model="newShipToAddress.country" :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span> Country <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Tax ID" variant="underlined"
                      v-model="newShipToAddress.tax" :readonly="isShipToCustomDisabled"></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" variant="underlined"
                      v-model="newShipToAddress.email" :readonly="isShipToCustomDisabled">
                      <template v-slot:label>
                        <span> Email <span class="text-red">*</span> </span>
                      </template></v-text-field>
                    <v-text-field density="compact" color="#00ADB5" label="Phone" variant="underlined"
                      v-model="newShipToAddress.phone" :readonly="isShipToCustomDisabled"><template v-slot:label>
                        <span> Phone<span class="text-red">*</span> </span>
                      </template></v-text-field>
                  </v-col>
                </v-row>
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>

        <!-- Shipment Details -->
        <div v-if="shipmentId">
          <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
            <span class="text-subtitle-1 text-uppercase">Dimensions</span>
          </v-card-title>
          <v-divider color="#00ADB5" opacity="100" class="mx-4"></v-divider>

          <v-card-text>
            <v-row dense>
              <v-spacer></v-spacer>

              <v-col cols="12" sm="6" md="4" class="px-4">
                <div class="d-flex justify-center">
                  <div class="text-body-2 text-medium-emphasis mt-2 mr-2">
                    Box (cm):
                  </div>
                  <v-select density="compact" variant="underlined" :items="boxSelectItems"
                    @update:modelValue="updateBoxDetails" item-title="title" return-object></v-select>
                </div>

                <div class="d-flex align-center">
                  <v-text-field variant="underlined" v-model="editedItem.box_width" density="compact" label="Width"
                    :readonly="isBoxDetailsDisabled"></v-text-field>
                  <span class="mx-2 text-body-1"> &times</span>
                  <v-text-field variant="underlined" v-model="editedItem.box_height" density="compact" label="Height"
                    :readonly="isBoxDetailsDisabled"></v-text-field>
                  <span class="mx-2 text-body-1"> &times</span>
                  <v-text-field variant="underlined" v-model="editedItem.box_depth" density="compact" label="Depth"
                    :readonly="isBoxDetailsDisabled"></v-text-field>
                </div>
              </v-col>

              <v-col cols="12" sm="6" md="4" class="px-4">
                <div class="d-flex">
                  <div class="text-body-2 text-medium-emphasis mt-2 mr-2">
                    Weight (kg):
                  </div>
                  <v-text-field density="compact" variant="underlined" v-model="editedItem.box_weight"></v-text-field>
                </div>
              </v-col>
              <v-spacer></v-spacer>
            </v-row>
          </v-card-text>
        </div>

        <!-- Orders -->

        <div v-if="shipmentId">
          <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
            <span class="text-subtitle-1 text-uppercase">Orders</span>
          </v-card-title>
          <v-divider color="#00ADB5" opacity="100" class="mx-4"></v-divider>
          <v-card-text>
            <v-data-table-virtual :headers="headers" density="comfortable" :items="shipmentStore.orders"
              :mobile="$vuetify.display.xs || $vuetify.display.sm" disable-sort :loading="loading">
              <template v-slot:item.actions="{ item }">
                <v-icon @click="deleteOrder(item.id)" color="red">
                  mdi-close-thick
                </v-icon>
              </template>

              <template v-slot:item="{ item }">
                <tr style="background-color: #fafafa">
                  <td class="text-center">{{ item.id }}</td>
                  <td class="text-center">{{ item.customer.name }}</td>
                  <td class="text-center">{{ item.supplier.name }}</td>
                  <td class="text-center">{{ item.offer.reference_number }}</td>
                  <td class="text-center">{{ item.deadline }}</td>
                  <td class="text-center">{{ item.offer.net_price }}</td>
                  <td class="text-center">
                    <v-icon @click="deleteOrder(item.id)" color="red">
                      mdi-close-thick
                    </v-icon>
                  </td>
                </tr>
              </template>

              <template v-slot:body.append>
                <tr>
                  <td class="text-center">
                    <v-select hide-details placeholder="Order ID" variant="underlined" class="cursor-center"
                      color="#00ADB5" :items="shipmentStore.availableOrders" v-model="newOrderId"></v-select>
                  </td>
                  <td class="text-center">-</td>
                  <td class="text-center">-</td>
                  <td class="text-center">-</td>
                  <td class="text-center">-</td>
                  <td class="text-center">-</td>

                  <td class="text-center">
                    <v-icon @click="addOrder" color="green">mdi-plus-thick</v-icon>
                  </td>
                </tr>
              </template>
            </v-data-table-virtual>
          </v-card-text>
        </div>

        <v-card-actions class="mx-2 my-4">
          <v-btn v-if="shipmentId && !label" class="px-4" color="green-darken-1" variant="elevated"
            @click="createLabel(props.order)">Create
            Label</v-btn>
          <v-spacer></v-spacer>
          <v-btn v-if="!shipmentId" class="px-4" color="blue-darken-1" variant="elevated" :loading="loading"
            @click="createShipment">Create Shipment</v-btn>

          <v-btn v-if="shipmentId" class="px-4" color="blue-darken-1" variant="elevated" :loading="loading"
            @click="updateShipment">Update Shipment</v-btn>
        </v-card-actions>
      </v-container>


    </v-card>
  </v-dialog>
  <LabelShipmentDialog v-model="dialogCreateLabel" :shipment="shipment" @initialize="emits('initialize')"
    @alert="passAlert" @closeNewShipmentDialog="close"/>

</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import DatePicker from "@/components/base/form-elements/DatePicker.vue";
import LabelShipmentDialog from "@/components/base/dialogs/LabelShipmentDialog.vue";
import { useShipmentAccountStore } from "@/stores/shipment-accounts";
import { useShipmentServiceStore } from "@/stores/shipment-services";
import { useShipmentStore } from "@/stores/shipment";
import { useOfficeAddressStore } from "@/stores/office-address";

import { storeToRefs } from "pinia";

const shipmentAccountStore = useShipmentAccountStore();
const shipmentServiceStore = useShipmentServiceStore();
const shipmentStore = useShipmentStore();
const officeAddressStore = useOfficeAddressStore();

const { res, errors, loading } = storeToRefs(shipmentStore);

const { res: addressStoreRes, errors: addressStoreErrors } =
  storeToRefs(officeAddressStore);

const dialogNewShipment = defineModel({ type: Boolean });

const props = defineProps({ orders: Object, order: Object });
const emits = defineEmits(["initialize", "alert"]);

const active = ref(false);
const panel = ref([""]);
const shipmentId = ref(null);
const newOrderId = ref(null);
const dialogCreateLabel = ref(false)
const label = ref(null)

const boxSelectItems = ref([
  { title: "Custom" },
  { title: "box 1 30x30x45", width: "30", height: "30", depth: "45" },
  { title: "box 2 30x30x40", width: "30", height: "30", depth: "40" },
]);

const headers = [
  { title: "ID", align: "center", key: "id", width: "16%" },
  { title: "Customer", align: "center", key: "customer.name" },
  { title: "Supplier", align: "center", key: "supplier.name" },
  { title: "Model", align: "center", key: "offer.reference_number" },
  { title: "Deadline", align: "center", key: "deadline" },
  { title: "Price", align: "center", key: "offer.net_price" },
  { title: "Actions", align: "center", key: "actions" },
];

const editedItem = ref({
  shipping_service: null,
  shipment_account_id: null,
  shipping_type: null,
  automatic_shipping: null,
  pick_up_time: null,
  deadline: null,
  ship_from_id: null,
  ship_to_id: null,
  ship_from_title: null,
  ship_to_title: null,
  box_weight: null,
  box_width: null,
  box_height: null,
  box_depth: null,
});

const defaultEditedItem = {
  shipping_service: null,
  shipment_account_id: null,
  shipping_type: null,
  automatic_shipping: null,
  pick_up_time: null,
  deadline: null,
  ship_from_id: null,
  ship_to_id: null,
  ship_from_title: null,
  ship_to_title: null,
  box_weight: null,
  box_width: null,
  box_height: null,
  box_depth: null,
};

const newShipFromAddress = ref({
  contact_Name: "",
  company: "",
  street_1: "",
  street_2: "",
  street_3: "",
  city: "",
  state: "",
  post_code: "",
  country: null,
  tax: "",
  email: "",
  phone: "",
});

const newShipToAddress = ref({
  contact_Name: "",
  company: "",
  street_1: "",
  street_2: "",
  street_3: "",
  city: "",
  state: "",
  post_code: "",
  country: null,
  tax: "",
  email: "",
  phone: "",
});

const defaultAddress = {
  contact_Name: "",
  company: "",
  street_1: "",
  street_2: "",
  street_3: "",
  city: "",
  state: "",
  post_code: "",
  country: null,
  tax: "",
  email: "",
  phone: "",
};

const isShipFromCustomDisabled = ref(true);
const isShipToCustomDisabled = ref(true);
const isBoxDetailsDisabled = ref(true);

watch(
  () => props.order,
  async (val) => {
    if (val.shipment_id) {

      label.value = val.shipment.label // to check if shipment has a label 
      shipmentId.value = val.shipment.id;
      editedItem.value = Object.assign({}, val.shipment);
      editedItem.value.shipping_service =
        val.shipment.shipment_account.shipment_service_id;


      await shipmentAccountStore.fetchAccounts(
        val.shipment.shipment_account.shipment_service_id

      ); //shipment fields
      await shipmentStore.fetchOrders(val.shipment.id);
      newShipFromAddress.value = officeAddressStore.collection.find(
        (item) => item.id === val.shipment.ship_from_id
      ); // when the dialog opens it address fields get all related datas
      newShipToAddress.value = officeAddressStore.collection.find(
        (item) => item.id === val.shipment.ship_to_id
      ); // same


    }
  }
);

const updateShipFromAddress = (val) => {
  if (val === "custom") {
    isShipFromCustomDisabled.value = false;
  } else isShipFromCustomDisabled.value = true;

  if (val == null || val === "custom") {
    newShipFromAddress.value = Object.assign({}, defaultAddress);
    editedItem.value.ship_from_title = null;
  } else {
    newShipFromAddress.value = officeAddressStore.collection.find(
      (item) => item.id === val
    ); //address fields get all related datas
    const formAddress = formattedAddresses.value.find(
      (item) => item.id === val
    );
    editedItem.value.ship_from_title = formAddress.display; //title finds and gets formatted value from formatted datas
  }
};

const updateShipToAddress = (val) => {
  if (val === "custom") {
    isShipToCustomDisabled.value = false;
  } else isShipToCustomDisabled.value = true;

  if (val == null || val === "custom") {
    newShipToAddress.value = Object.assign({}, defaultAddress);
    editedItem.value.ship_to_title = null;
  } else {
    newShipToAddress.value = officeAddressStore.collection.find(
      (item) => item.id === val
    );

    const formAddress = formattedAddresses.value.find(
      (item) => item.id === val
    );
    editedItem.value.ship_to_title = formAddress.display;
  }
};

const updateBoxDetails = (val) => {
  if (val.title !== "Custom") {
    isBoxDetailsDisabled.value = true;
    editedItem.value.box_width = val.width;
    editedItem.value.box_height = val.height;
    editedItem.value.box_depth = val.depth;
  } else {
    isBoxDetailsDisabled.value = false;
    editedItem.value.box_width = null;
    editedItem.value.box_height = null;
    editedItem.value.box_depth = null;
  }
};

const addOrder = async () => {
  if (newOrderId.value) {
    await shipmentStore.handleLinkOrdertToShipment([
      { order_id: newOrderId.value, shipment_id: String(shipmentId.value) },
    ]);

    if (res.value) {
      emits("initialize"); // refresh logistic table
      emits("alert", res.value.message, res.value.status);
      initializeTable(); // refresh the table inside the dialog
      newOrderId.value = null; //clears the field
    } else {
      emits("alert", errors.value, "error");
    }
    res.value = null;
  }
};

const deleteOrder = async (id) => {
  await shipmentStore.handleDeleteOrdertToShipment([
    { order_id: id, shipment_id: String(shipmentId.value) },
  ]);
  if (res.value) {
    emits("initialize");
    emits("alert", res.value.message, res.value.status);
    initializeTable();
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

onMounted(async () => {
  await shipmentServiceStore.fetchServices();
  await officeAddressStore.fetchOfficeAddresses();
  await shipmentStore.fetchAvailableOrders()
});

const formattedAddresses = computed(() => {
  return [
    { id: "custom", display: "Custom" },
    ...officeAddressStore.collection.map((item) => ({
      id: item.id,
      display: `${item.company} (${item.street_1} ${item.post_code ? ", " + item.post_code : ""
        }), ${item.country}`,
    })),
  ];
});

const getShipmentAccount = async (val) => {
  shipmentAccountStore.fetchAccounts(val);
};

function showAddressDetails() {
  active.value = !active.value;
  active.value ? (panel.value = ["address"]) : (panel.value = [""]);
}

const createShipment = async () => {
  if (editedItem.value.ship_from_id === "custom") {
    const shipFromAddress = await createOfficeAddress(newShipFromAddress.value);
    editedItem.value.ship_from_id = shipFromAddress.id;
    editedItem.value.ship_from_title = `${shipFromAddress.company} (${shipFromAddress.street_1
      }${shipFromAddress.post_code ? ", " + shipFromAddress.post_code : ""}), ${shipFromAddress.country
      }`;
  }

  if (editedItem.value.ship_to_id === "custom") {
    const shipToAddress = await createOfficeAddress(newShipToAddress.value);
    editedItem.value.ship_to_id = shipToAddress.id;
    editedItem.value.ship_to_title = `${shipToAddress.company} (${shipToAddress.street_1
      }${shipToAddress.post_code ? ", " + shipToAddress.post_code : ""}), ${shipToAddress.country
      }`;
  }

  if (editedItem.value.ship_from_id && editedItem.value.ship_to_id) {
    await shipmentStore.handleAddedShipment(editedItem.value, props.orders);
    if (res.value) {
      emits("initialize");
      emits("alert", res.value.message, res.value.status);
      close();
    } else {
      emits("alert", errors.value, "error");
    }
    res.value = null;
  }
};

const createOfficeAddress = async (data) => {
  await officeAddressStore.handleAddedOfficeAddress(data);
  if (addressStoreRes.value) {
    return addressStoreRes.value.data;
  } else {
    emits("alert", addressStoreErrors.value, "error");
  }
  addressStoreRes.value = null;
};

const shipment = ref({})

const createLabel = (item) => {
  dialogCreateLabel.value = true
  shipment.value = item.shipment
}

const updateShipment = async () => {
  await shipmentStore.handleUpdatedShipment(shipmentId.value, editedItem.value);
  if (res.value) {
    emits("initialize");
    emits("alert", res.value.message, res.value.status);
    close();
  } else {
    emits("alert", errors.value, "error");
  }
  res.value = null;
};

async function initializeTable() {
  await shipmentStore.fetchOrders(shipmentId.value);
}

function close() {
  dialogNewShipment.value = false;
  setTimeout(() => {
    editedItem.value = Object.assign({}, defaultEditedItem);
    shipmentId.value = null;
    isShipFromCustomDisabled.value = true; //readonly fields go default
    isShipToCustomDisabled.value = true;
    isBoxDetailsDisabled.value = true;
    newShipFromAddress.value = Object.assign({}, defaultAddress)
    newShipToAddress.value = Object.assign({}, defaultAddress)
  }, 100);
  active.value = false;
  panel.value = [""];
  label.value = null
}

function passAlert (type, message) {
  emits('alert', type, message)
}

watch(dialogNewShipment, (val) => {
  val || close();
});

watch(dialogCreateLabel, (val) => {
  if (!val) {
    shipment.value = {};
  }
});
</script>

<style>
.no-scrollbar ::-webkit-scrollbar {
  display: none;
  /* Chrome, Safari, Edge */
}

.no-scrollbar {
  scrollbar-width: none;
  /* Firefox */
}

.cursor-center input {
  text-align: center;
}
</style>
