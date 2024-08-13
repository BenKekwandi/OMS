<template>
  <v-btn
    prepend-icon=" mdi-arrow-left"
    @click="goBack"
    variant="text"
    color="#00ADB5"
  >
    Users
  </v-btn>
  <v-card>
    <v-text-field
      v-show="xs"
      class="my-2 mx-2"
      v-model="search"
      prepend-inner-icon="mdi-magnify"
      density="compact"
      label="Search"
      single-line
      flat
      hide-details
      variant="solo-filled"
    >
    </v-text-field>
    <v-data-table
      v-model="selected"
      show-select
      :search="search"
      :headers="mainHeaders"
      :loading="loading"
      :items="collection"
      :mobile="sm || xs"
    >
      <template v-slot:top>
        <v-toolbar color="#071d35" class="px-3" flat>
          <Button
            icon="mdi-file-download"
            color="#66BB6A"
            variant="flat"
            class="mr-2"
            label="Export"
          />
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <Button
            color="#EF5350"
            variant="flat"
            label="Delete"
            class="mr-2"
            icon="mdi-trash-can-outline"
            @click="deleteSupplier"
          />
          <v-text-field
            v-show="!xs"
            v-model="search"
            prepend-inner-icon="mdi-magnify"
            density="compact"
            label="Search"
            rounded
            single-line
            flat
            hide-details
            variant="solo-filled"
          >
          </v-text-field>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn
          @click="transfer(item)"
          color="#193a63"
          icon=" mdi-account-arrow-right"
          variant="text"
        ></v-btn>

        <v-btn
          @click="editItem(item)"
          size="small"
          color="#193a63"
          icon="mdi-pencil"
          variant="text"
        ></v-btn>
      </template>

      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
    </v-data-table>
  </v-card>
  <v-dialog v-model="dialog" max-width="500px" scrollable>
    <v-card>
      <v-container>
        <v-card-title>
          <span class="text-h5">Purchase Managers</span>
        </v-card-title>
        <v-divider></v-divider>

        <v-card-text class="my-4">
          <v-row>
            <v-data-table-virtual
              :headers="dialogHeaders"
              :items="managers"
              height="400"
              item-value="id"
              select-strategy="single"
              show-select
              v-model="selectedManager"
            >
            </v-data-table-virtual>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="close" variant="outlined">
            Cancel
          </v-btn>
          <v-btn
            color="primary"
            @click="handleTransfer"
            :loading="loading"
            variant="flat"
          >
            Transfer
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
  <v-dialog v-model="dialogEdit" max-width="800px">
    <v-card>
      <v-container>
        <v-card-title>
          <span class="text-h5">Edit Supplier</span>
        </v-card-title>
        <v-divider class="my-2 mx-4"></v-divider>

        <v-card-text>
          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                label="Name"
                prepend-inner-icon="mdi-account-circle"
                v-model="editedItem.name"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-autocomplete
                label="Country"
                prepend-inner-icon="mdi-earth"
                auto-select-first="exact"
                item-title="name"
                item-value="id"
                v-model.number="editedItem.country_id"
                :items="countryStorage.collection"
                :rules="[rules.required]"
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                label="Primary Name"
                prepend-inner-icon="mdi-account-circle"
                v-model="editedItem.primary_name"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                label="Phone Number"
                prepend-inner-icon="mdi-phone"
                v-model="editedItem.phone"
                :rules="[rules.phone]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
             <v-col cols="12" sm="12">
              <v-text-field
                label="Email"
                prepend-inner-icon="mdi-at"
                v-model="editedItem.email"
                :rules="[rules.email]"
              ></v-text-field>
            </v-col>
           
          </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                label="Closing Time"
                prepend-inner-icon="mdi-timer-outline"
                type="time"
                v-model="editedItem.closing_time"
              ></v-text-field>
            </v-col>
             <v-col cols="12" sm="6">
              <v-text-field
                label="Opening Time"
                prepend-inner-icon="mdi-timer-outline"
                type="time"
                v-model="editedItem.opening_time"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row> 
             <v-col cols="12" sm="12">
              <v-autocomplete
                label="Brands"
                prepend-inner-icon="mdi-tag-plus"
                v-model="editedItem.brands"
                item-title="name"
                item-value="id"
                :items="brandStorage.collection"
                chips
                multiple
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-textarea
                label="Invoice Delivery Rules"
                rows="3"
                prepend-inner-icon="mdi-map-marker"
                v-model="editedItem.invoice_delivery_rules"
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-textarea
                label="Billing Address"
                rows="3"
                prepend-inner-icon="mdi-map-marker"
                v-model="editedItem.address"
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row no-gutters>
            <v-col>
              <v-checkbox
                label="On Credit"
                v-model="editedItem.is_credit"
              ></v-checkbox>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="mx-2">
          <v-spacer></v-spacer>
          <v-btn
            class="px-4"
            color="blue-darken-1"
            variant="outlined"
            @click="closeEdit"
          >
            Cancel
          </v-btn>
          <v-btn
            class="px-6"
            color="blue-darken-1"
            variant="elevated"
            :loading="loading"
            @click="updateSupplier"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>

  <DeleteDialog
    v-model="dialogDelete"
    :loading="loading"
    item="supplier"
    type="delete"
    @close="closeDelete"
    @confirm="deleteSupplierConfirm"
  />
</template>

<script setup>
import { ref, nextTick, computed, watch, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { supplierStore } from "@/stores/supplier";
import { useUserStore } from "@/stores/users";
import { rules } from "/src/includes/customValidationRules.js";
import { brandStore } from "@/stores/brand.js";
import { countryStore } from "@/stores/countries.js";
import { useSnackbarStore } from "@/stores/snackbar";
import { useDisplay } from "vuetify";
import { storeToRefs } from "pinia";
import Button from "../base/form-elements/Button.vue";
import DeleteDialog from "../base/dialogs/DeleteDialog.vue";

const { xs, sm } = useDisplay();

const route = useRoute();
const router = useRouter();
const id = ref(route.params.id);
const editedIndex = ref(-1);
const dialogDelete = ref(false);
const selected = ref([]);

const suppliers_store = supplierStore();
const users_store = useUserStore();
const brandStorage = brandStore();
const countryStorage = countryStore();

const { collection, errors, loading, res } = storeToRefs(suppliers_store);

async function initialize() {
  await suppliers_store.fetchAssociated(id.value);
}

onMounted(async () => {
  await users_store.fetchUsers();
  await brandStorage.fetchItems();
  await countryStorage.fetchItems();
});

const managers = computed(() => {
  return users_store.collection.filter(
    (item) => item.id != id.value && item.role === "pm"
  );
});

const search = ref("");
const dialog = ref(false);
const dialogEdit = ref(false);
const selectedSupplier = ref();
const selectedManager = ref([]);

const mainHeaders = ref([
  { title: "Name", key: "name", align: "start" },
  { title: "Email", key: "email" },
  { title: "Phone", key: "phone" },
  { title: "Country", key: "country.name" },
  { title: "Actions", key: "actions", align: "end", sortable: false },
]);

const dialogHeaders = ref([
  { title: "Name", key: "name", align: "start" },
  { title: "Email", key: "email" },
]);

const editedItem = ref({
  name: "",
  email: "",
  phone: "",
  country_id: "",
  address: "",
  primary_name: "",
  opening_time: "08:30",
  closing_time: "17:30",
  invoice_delivery_rules: "",
  is_credit: false,
  brands: [],
});
const defaultItem = ref({
  name: "",
  email: "",
  phone: "",
  country_id: "",
  address: "",
  primary_name: "",
  opening_time: "08:30",
  closing_time: "17:30",
  invoice_delivery_rules: "",
  is_credit: false,
  brands: [],
});

const transfer = (supplier) => {
  dialog.value = true;
  selectedSupplier.value = supplier.id;
};

initialize();

const handleTransfer = async () => {
  await suppliers_store.handleTransferedSupplier(
    selectedSupplier.value,
    selectedManager.value[0]
  );
  if (res.value) {
    close();
    snackbarShow(res.value.message, res.value.status);
    initialize();
  } else {
    snackbarShow(errors.value, "error");
  }
  res.value = null;
};

function editItem(managedItem) {
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  dialogEdit.value = true;
}
const updateSupplier = async () => {
  await suppliers_store.updateItemHandler(
    editedItem.value.id,
    editedItem.value
  );
  if (res.value) {
    initialize();
    closeEdit();
    snackbarShow(res.value.message, res.value.status);
  } else {
    snackbarShow(errors.value, "error");
  }
  res.value = null;
};

function deleteSupplier() {
  if (selected.value.length) {
    dialogDelete.value = true;
  } else {
    snackbarShow("Select Supplier(s) to delete first.", "error");
  }
}

const deleteSupplierConfirm = async () => {
  const ids = selected.value.map((id) => {
    return {
      id,
    };
  });

  await suppliers_store.deleteItemHandler(ids);
  if (res.value) {
    initialize();
    snackbarShow(res.value.message, "success");
    closeDelete();
  } else {
    snackbarShow(errors.value, "error");
  }
  res.value = null;
};

function closeEdit() {
  dialogEdit.value = false;
  nextTick(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
    errors.value = {};
  });
}

function close() {
  dialog.value = false;
  selectedManager.value = [];
  selectedSupplier.value = "";
}

function closeDelete() {
  dialogDelete.value = false;
  selected.value = [];
}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

const goBack = () => {
  router.go(-1);
};

watch(dialogEdit, (val) => {
  val || closeEdit();
});

watch(dialogDelete, (val) => {
  val || closeDelete();
});
</script>
