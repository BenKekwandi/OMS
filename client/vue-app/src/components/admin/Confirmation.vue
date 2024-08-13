<template>
  <v-data-table :headers="headers" :items="collection" :search="search" :loading="loading"
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

        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" rounded
          single-line flat hide-details variant="solo-filled">
        </v-text-field>
      </v-toolbar>
    </template>

    <template v-slot:item.status="{ item }">
      <v-chip variant="outlined" label :color="item.status === 'Cancelled'
        ? 'deep-orange-darken-2'
        : 'green-darken-3'
        ">
        {{ item.status }}
      </v-chip>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>

  <v-dialog v-model="dialogCancel" max-width="600px">
    <v-card class="pa-3">
      <v-card-title class="text-center">Enter a reason for cancellation
      </v-card-title>
      <v-card-text>
        <v-form ref="form">
          <v-textarea label="Reason for cancellation" rows="3" prepend-inner-icon="mdi-note" v-model="note"
            :rules="[rules.required]">
          </v-textarea>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="closeCancel">
          Cancel
        </v-btn>
        <v-btn color="blue-darken-1" :loading="loading" variant="text" @click="cancelProposal">Confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <ConfirmationDialog v-model="dialogOrder" :proposal="proposal" :permission="true" @update="updateOrderOffer"
    @cancelOffer="pmCancel" @cancelOrder="smCancel" @confirmProposal="confirm" :loading="loading" />

  <v-dialog v-model="dialogConfirm" max-width="600px">
    <v-card class="pa-3">
      <v-card-title class="text-center">Are you sure to confirm this proposal?
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="closeConfirm">
          Cancel
        </v-btn>
        <v-btn color="blue-darken-1" :loading="loading" variant="text" @click="confirmProposal">Confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref } from "vue";
import { proposalStore } from "@/stores/proposal";

import { useSnackbarStore } from "@/stores/snackbar";
import { storeToRefs } from "pinia";
import { rules } from "@/includes/customValidationRules";
import { pmConfirmationStore } from "@/stores/pm-confirmation";
import ConfirmationDialog from "@/components/base/dialogs/ConfirmationDialog.vue";

const store = proposalStore();
const pmConfirmStore = pmConfirmationStore();

const { collection, errors, loading, res } = storeToRefs(store);
const { errors: pmErrors, res: pmRes } = storeToRefs(pmConfirmStore);

const search = ref("");
const form = ref();
const dialogCancel = ref(false);
const dialogOrder = ref(false);
const dialogConfirm = ref(false);
const note = ref("");
const proposalId = ref("");
const supplierId = ref("");
const index = ref();

const proposal = ref({});

async function initialize() {
  await store.fetchItems();
}

const headers = ref([
  { title: "ID", key: "id", align: 'center' },
  { title: "Customer", key: "order.customer.name" },
  { title: "Supplier", key: "offer.supplier.name" },
  { title: "Offer ID", key: "offer.id" },
  { title: "Request ID", key: "order.id" },
  { title: "Model", key: "offer.reference_number" },
  { title: "Confirmation Date", key: "confirmed_at" },
  { title: "Deadline", key: "order.deadline" },
  { title: "Status", key: "status", align: "center" },
]);

const confirm = (item) => {
  dialogConfirm.value = true;
  proposalId.value = item.id;
  supplierId.value = item.offer.supplier_id;
  if (item.status === "Awaits SM confirmation") {
    index.value = "smconfirm";
  } else {
    index.value = "pmconfirm";
  }
};

const openOrderInfo = (item) => {
  dialogOrder.value = true;
  proposal.value = Object.assign({}, item);
};

const smCancel = (item) => {
  dialogCancel.value = true;
  proposalId.value = item.id;
  index.value = "smcancel";
};

const pmCancel = (item) => {
  dialogCancel.value = true;
  proposalId.value = item.id;
  index.value = "pmcancel";
};

const cancelProposal = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    if (index.value == "smcancel") {
      await store.cancelItemHandler(proposalId.value, note.value);
      if (res.value) {
        initialize();
        closeCancel()
        closeOrderDialog()
        snackbarShow(res.value.message, "success");
      } else {
        snackbarShow(errors.value, "error");
      }
      res.value = null;
    } else if (index.value == "pmcancel") {
      await pmConfirmStore.cancelItemHandler(proposalId.value, {
        notes: note.value,
      });
      if (pmRes.value) {
        initialize();
        closeCancel()
        closeOrderDialog()
        snackbarShow(pmRes.value.message, "success");
      } else {
        snackbarShow(pmErrors.value, "error");
      }
      res.value = null;
    }
  }
};

const confirmProposal = async () => {
  if (index.value === "smconfirm") {
    await store.confirmItemHandler(proposalId.value, supplierId.value);
    if (res.value) {
      initialize();
      closeConfirm()
      closeOrderDialog()
      snackbarShow(res.value.message, "success");
    } else {
      snackbarShow(errors.value, "error");
    }
  } else if (index.value === "pmconfirm") {
    await pmConfirmStore.updateItemHandler(proposalId.value, supplierId.value);
    if (pmRes.value) {
      initialize();
      closeConfirm()
      closeOrderDialog()
      snackbarShow(pmRes.value.message, "success");
    } else {
      snackbarShow(pmErrors.value, "error");
    }
  }

  res.value = null;
};

async function updateOrderOffer(item) {
  await store.updateOrderOffer(item.id, item.proposal);
  if (res.value) {
    initialize();
    snackbarShow(res.value.message, "success");
    closeOrderDialog()
  } else {
    snackbarShow(errors.value, "error");
  }

  res.value = null;
}

const closeCancel = () => {
  dialogCancel.value = false;
  note.value = "";
  proposalId.value = "";
  index.value = "";
};

const closeConfirm = () => {
  dialogConfirm.value = false;
  supplierId.value = "";
  proposalId.value = "";
};

const closeOrderDialog = () => {
  dialogOrder.value = false

}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

initialize();
</script>
