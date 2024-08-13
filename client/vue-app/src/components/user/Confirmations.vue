<template>
  <v-divider class="mb-2"></v-divider>
  <v-text-field v-show="mobile" class="mb-2" v-model="search" prepend-inner-icon="mdi-magnify" density="compact"
    label="Search" single-line flat hide-details variant="solo-filled">
  </v-text-field>
  <v-data-table v-model="selected" :search="search" :loading="loading" :headers="headers" :items="collection"
    :sort-by="[{ key: 'id', order: 'desc' }]">
    <template v-slot:item.id="{ item }">
      <span @click="openOrderInfo(item)"
        class="text-decoration-underline font-weight-bold text-blue-darken-4 cursor-pointer">
        {{ item.id }}
      </span>
    </template>
    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <v-dialog v-model="dialogCancel" max-width="650px">
          <v-card>
            <v-container>
              <v-card-title class="text-h5">Are you sure you want to cancel this
                {{ store.name }}?</v-card-title>
              <v-divider class="my-2 mx-2"></v-divider>
              <v-card-text>
                <v-form ref="form">
                  <v-textarea label="Reason for cancellation" rows="3" prepend-inner-icon="mdi-note"
                    v-model="proposalToCancel.notes" :rules="[rules.required]"></v-textarea>
                </v-form>

              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="px-4" color="blue-darken-1" variant="text" @click="closeCancel">Exit</v-btn>
                <v-btn class="px-4" color="blue-darken-1" variant="text" :loading="loading"
                  @click="cancelItemConfirm">Confirm</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-container>
          </v-card>
        </v-dialog>

        <v-text-field v-show="!mobile" max-width="400px" v-model="search" prepend-inner-icon="mdi-magnify"
          density="compact" label="Search" rounded single-line flat hide-details variant="solo-filled">
        </v-text-field>
      </v-toolbar>
    </template>

    <template v-slot:item.status="{ item }">
      <v-chip variant="outlined" label v-if="item.status === 'Awaits SM confirmation'" color="#FFA500">
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" label v-else-if="item.status === 'Awaits PM confirmation'" color="#FFA500">
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" label v-else-if="item.status === 'Completed'" color="#00ADB5">
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" label v-else-if="item.status === 'Cancelled'" color="#FF2E63">
        {{ item.status }}
      </v-chip>
    </template>


    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>

  <v-dialog v-model="dialogConfirm" max-width="500px">
    <v-card class="pa-3">
      <v-card-title class="text-center">Are you sure to confirm this proposal?
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="closeConfirm">
          Cancel
        </v-btn>
        <v-btn color="blue-darken-1" :loading="loading" variant="text" @click="confirm">Confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>


  <ConfirmationDialog v-model="dialogOrder" :proposal="proposal" :permission="false" @cancelProposal="cancelItem"
    @confirmProposal="editItem" />
</template>

<script setup>
import { storeToRefs } from "pinia";
import { computed, nextTick, ref, watch, defineProps } from "vue";
import { rules } from "@/includes/customValidationRules.js";
import { useDisplay } from "vuetify";
import { useSnackbarStore } from "@/stores/snackbar";
import ConfirmationDialog from "@/components/base/dialogs/ConfirmationDialog.vue";


const { mobile } = useDisplay();

const props = defineProps(["role", "store"]);
const { role, store } = props;
const { collection, errors, res, loading } = storeToRefs(store)

defineExpose({
  initialize
});

const dialogOrder = ref(false);
const dialogConfirm = ref(false);
const dialogCancel = ref(false);
const search = ref("");
const selected = ref([]);
const editedIndex = ref(-1);

const form = ref()

const headers = ref(
  role === "sm"
    ? [
      { title: "ID", key: "id", align: 'center' },
      { title: "Supplier", key: "offer.supplier.name" },
      { title: "Created", key: "created_at" },
      { title: "Brand", key: "offer.brand.name" },
      { title: "Model", key: "order.reference_number" },
      { title: "Other Features", key: "order.other_features" },
      { title: "RRP", key: "offer.rrp_price" },
      { title: "Discount", key: "offer.discount" },
      { title: "Availability", key: "offer.availability" },
      { title: "Attribute", key: "attribute" },
      { title: "Status", key: "status", align: "center" },
    ]
    : [
      { title: "ID", key: "id", align: 'center' },
      { title: "Customer", key: "order.customer.name" },
      { title: "Supplier", key: "offer.supplier.name" },
      { title: "Offer ID", key: "offer.id" },
      { title: "Request ID", key: "order.id" },
      { title: "Model", key: "offer.reference_number" },
      { title: "Confirmation Date", key: "confirmed_at" },
      { title: "Deadline", key: "order.deadline" },
      { title: "Payment Deadline", key: "delivery_days" },
      { title: "Expected Arrival", key: "delivery_days" },
      { title: "Date of shipment", key: "order.shipment_date" },
      { title: "Expected Delivery", key: "order.expected_delivery_at" },
      { title: "Serial number", key: "offer.serial_number" },
      { title: "Shipping label", key: "shipment_id" },
      { title: "Status", key: "status", align: "center" },
    ]
)

const editedItem = ref({});
const proposal = ref({});

// const defaultItem = ref(
//   role === "sm"
//     ? { name_warranty: "", proposal_id: "" }
//     : { serial_number: "", offer_id: "" }
// );

const proposalToSend = ref({ proposal_id: "", supplier_id: "" });

const proposalToCancel = ref({
  notes: "",
});

async function initialize() {
  await store.fetchItems(role);
}


const openOrderInfo = (item) => {
  dialogOrder.value = true;
  proposal.value = Object.assign({}, item);

};

// Domain specific
function editItem(managedItem) {
  editedIndex.value = managedItem.id;
  editedItem.value = Object.assign({}, managedItem);
  proposalToSend.value.proposal_id = managedItem.id;
  proposalToSend.value.supplier_id = managedItem.offer.supplier.id;
  dialogConfirm.value = true;
}

// Domain specific
function cancelItem(managedItem) {
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  dialogCancel.value = true;
}


const cancelItemConfirm = async () => {
  const { valid } = await form.value.validate()
  if (valid) {
    await store.cancelItemHandler(editedItem.value.id, proposalToCancel.value);
    if (res.value) {
      initialize()
      proposalToCancel.value = { notes: "" };
      closeCancel()
      dialogOrder.value = false
      snackbarShow(res.value.message, res.value.status);
    } else {
      snackbarShow(errors.value, "error");
    }

    res.value = null
  }

};

// Domain specific
function closeCancel() {
  dialogCancel.value = false;
  nextTick(() => {
    proposalToCancel.value = Object.assign({}, { notes: "" });
    editedIndex.value = -1;
  });
}

const closeConfirm = () => {
  dialogConfirm.value = false;
  proposalToSend.value.proposal_id = "";
  proposalToSend.value.supplier_id = "";
};

// Domain specifice
const confirm = async () => {
  if (editedIndex.value > -1) {
    await store.updateItemHandler(editedItem.value.id, proposalToSend.value);
    if (res.value) {
      initialize()
      closeConfirm()
      dialogOrder.value = false
      snackbarShow(res.value.message, res.value.status);
    } else {
      snackbarShow(errors.value, "error");
    }
  }
  res.value = null;
};

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

watch(dialogConfirm, (val) => {
  val || closeConfirm();
});

watch(dialogCancel, (val) => {
  val || closeCancel();
});

initialize();
</script>
