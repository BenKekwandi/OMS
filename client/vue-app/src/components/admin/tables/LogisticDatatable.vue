<template>
  <v-data-table
    v-model="selected"
    :search="search"
    show-select
    :headers="headers"
    :loading="order_store.loading"
    :items="order_store.collection"
    :sort-by="[{ key: 'id', order: 'desc' }]"
    :mobile="sm || xs"
    item-selectable="existing_shipment"
  >
    <template v-slot:item.id="{ item }">
      <span
        @click="openOrderInfo(item)"
        class="text-decoration-underline text-blue-darken-4 cursor-pointer font-weight-bold"
      >
        {{ item.id }}
      </span>
    </template>

    <template v-slot:item.shipment.id="{ item }">
      <span
        v-if="item.shipment && item.shipment.id"
        @click="showEditShipment(item)"
        class="text-decoration-underline text-blue-darken-4 cursor-pointer font-weight-bold"
      >
        {{ item.shipment.id }}
      </span>
      <span v-else>-</span>
    </template>

    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <!-- <Button color="#66BB6A" @click="exportItems" variant="flat" class="mr-2" label="Export"
          icon="mdi-file-download" /> -->
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <Button
          color="#00ADB5"
          variant="flat"
          label="Add Shipment"
          class="mr-2"
          icon="mdi-plus"
          @click="showAddShipment"
        />

        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          label="Search"
          density="compact"
          rounded
          single-line
          flat
          hide-details
          variant="solo-filled"
        >
        </v-text-field>
      </v-toolbar>
    </template>

    <template v-slot:item.status="{ item }">
      <v-chip
        variant="outlined"
        label
        size="small"
        v-if="item.status === 'PM Confirmed'"
        color="#66BB6A"
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        label
        size="small"
        v-else-if="item.status === 'Invoice Received'"
        color="teal-darken-3"
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Ready for Shipment'"
        color="green-darken-3"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="
          item.status === 'Shipment booked' && item.shipment.status === 'New'
        "
        color="green-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.shipment.status === 'Label Created'"
        color="green-darken-1"
        label
      >
        {{ item.shipment.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.shipment.status === 'Collected'"
        color="green-darken-1"
        label
      >
        {{ item.shipment.status }}
      </v-chip>

      <v-chip
        variant="outlined"
        size="small"
        v-else-if="
          item.shipment.status === 'Delivered' ||
          item.shipment.status === 'Delivered To Customer'
        "
        color="blue-darken-1"
        label
      >
        {{ item.shipment.status }}
      </v-chip>
    </template>

    <template v-slot:item.action="{ item }">
      <v-btn
        @click="showLabel(item)"
        color="#193a63"
        size="small"
        variant="text"
        :disabled="!item.shipment?.label"
        >Label</v-btn
      >
    </template>
  </v-data-table>
  <v-dialog v-model="dialogAddShipment" max-width="750px">
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <span class="text-h6">ADD ORDER #{{ order_ids }} TO:</span>
        <v-btn
          icon="mdi-close"
          size="small"
          variant="text"
          @click="closeAddShipment"
        ></v-btn>
      </v-card-title>

      <v-divider></v-divider>
      <v-container>
        <v-card-text>
          <v-radio-group v-model="shipmentType">
            <v-radio label="New Shipment" value="new"></v-radio>
            <v-radio label="Existing Shipment" value="existing"></v-radio>
            <v-autocomplete
              v-if="shipmentType === 'existing'"
              class="ml-10 w-25"
              label="Shipment ID"
              variant="underlined"
              item-title="id"
              item-value="id"
              :items="shipment_store.collection"
              v-model="shipmentId"
              clearable
            ></v-autocomplete>
          </v-radio-group>
        </v-card-text>
        <v-card-actions class="mx-2 my-4">
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-btn
            class="px-4"
            color="blue-darken-1"
            variant="elevated"
            :loading="loading"
            @click="addShipment"
            >ADD</v-btn
          >
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
  <OrderDialog v-model="dialogOrder" :order="order" />

  <NewShipmentDialog
    v-model="dialogNewShipment"
    @initialize="initialize"
    @alert="snackbarShow"
    :orders="order_ids"
    :order="order"
  />
  <LabelShipmentDialog
    v-model="dialogLabel"
    :shipment="shipment"
    @initialize="initialize"
    @alert="snackbarShow"
  />
</template>

<script setup>
//vue and pinia
import { ref, watch, nextTick } from "vue";
import { storeToRefs } from "pinia";
import { useDisplay } from "vuetify";
//components
import OrderDialog from "@/components/base/dialogs/OrderDialog.vue";
import NewShipmentDialog from "@/components/base/dialogs/NewShipmentDialog.vue";
import Button from "@/components/base/form-elements/Button.vue";
import LabelShipmentDialog from "@/components/base/dialogs/LabelShipmentDialog.vue";
//stores
import { orderStore } from "@/stores/order";
import { useShipmentStore } from "@/stores/shipment";
import { useSnackbarStore } from "@/stores/snackbar";

const order_store = orderStore();
const shipment_store = useShipmentStore();

const { xs, sm } = useDisplay();

// const { collection, errors, loading } = storeToRefs(order_store);
const { res, errors, loading, shipment } = storeToRefs(shipment_store);

const emits = defineEmits(["alert", "initialize", "error"]);

const selected = ref([]);
const search = ref("");

const dialogAddShipment = ref(false);
const dialogNewShipment = ref(false);
const dialogLabel = ref(false);
const dialogOrder = ref(false);
const order = ref({});
const order_ids = ref({});
const shipmentType = ref("");
const shipmentId = ref(null);

const headers = ref([
  { title: "ID", key: "id", align: "end" },
  { title: "Customer", key: "customer.name", align: "end" },
  { title: "Shipment", key: "shipment.id", align: "end" },
  { title: "Supplier", key: "supplier.name", align: "end" },
  { title: "Model", key: "reference_number", align: "end" },
  { title: "Net Price", key: "offer.net_price", align: "end" },
  { title: "Status", key: "status", align: "center", align: "end" },
  {
    title: "Actions",
    key: "action",
    align: "end",

    sortable: false,
  },
]);

const openOrderInfo = (item) => {
  dialogOrder.value = true;
  order.value = Object.assign({}, item);
};

function showAddShipment() {
  if (selected.value.length) {
    order_ids.value = selected.value.map((order_id) => ({ order_id }));
    dialogAddShipment.value = true;
  } else {
    snackbarShow("Select order(s) to add shipment first.", "error");
  }
}

const showLabel = async (item) => {
  await shipment_store.fetchShipment(item.shipment.id);
  dialogLabel.value = true;
};

async function addShipment() {
  if (shipmentType.value == "new") {
    dialogNewShipment.value = true;
  } else {
    const orderShipmentArray = selected.value.map((order_id) => ({
      order_id: order_id,
      shipment_id: shipmentId.value,
    }));

    await shipment_store.handleLinkOrdertToShipment(orderShipmentArray);
    if (res.value) {
      emits("alert", res.value.message, res.value.status);
      initialize();
      shipmentId.value = null; //clears the field
      dialogAddShipment.value = false;
    } else {
      emits("alert", errors.value, "error");
    }
    res.value = null;
  }
}

const showEditShipment = async (item) => {
  order.value = item;
  dialogNewShipment.value = true;
};

function closeAddShipment() {
  dialogAddShipment.value = false;
  nextTick(() => {
    shipmentType.value = "";
  });
}

function closeNewShipment() {
  dialogNewShipment.value = false;
  closeAddShipment();
  order.value = {};
}

watch(dialogAddShipment, (val) => {
  val || closeAddShipment();
});

watch(dialogNewShipment, (val) => {
  val || closeNewShipment();
});

async function initialize() {
  await order_store.accountingOrdersHandler();
  await shipment_store.fetchShipments();
}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

initialize();
</script>
