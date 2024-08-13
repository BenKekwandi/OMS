<template>
  <v-divider class="mb-2"></v-divider>
  <v-text-field
    v-show="xs"
    class="mb-2"
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
    :search="search"
    show-select
    :loading="loading"
    :headers="headers"
    :items="collection"
    :sort-by="[{ key: 'name' }]"
    :mobile="sm || xs"
  >
    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <Button
          color="#66BB6A"
          @click="exportItems"
          variant="flat"
          class="mr-2"
          label="Export"
          icon="mdi-file-download"
        />
        <Button
          color="#5C6BC0"
          variant="flat"
          label="Import"
          class="mr-2"
          icon="mdi-file-upload"
        />
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ props }">
            <Button
              color="#00ADB5"
              variant="flat"
              label="New"
              class="mr-2"
              icon="mdi-plus"
              v-bind="props"
            />
          </template>

          <v-card>
            <v-container>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
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
                  <v-col cols="12">
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
                      label="Opening Time"
                      prepend-inner-icon="mdi-timer-outline"
                      type="time"
                      v-model="editedItem.opening_time"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      label="Closing Time"
                      prepend-inner-icon="mdi-timer-outline"
                      type="time"
                      v-model="editedItem.closing_time"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
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
                <v-row> </v-row>
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
                  @click="close"
                >
                  Cancel
                </v-btn>
                <v-btn
                  class="px-6"
                  color="blue-darken-1"
                  variant="elevated"
                  :loading="loading"
                  @click="save"
                >
                  Save
                </v-btn>
              </v-card-actions>
            </v-container>
          </v-card>
        </v-dialog>

        <v-text-field
          v-show="!xs"
          max-width="400px"
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
      <v-icon size="small" class="me-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
    </template>

    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
  </v-data-table>
</template>

<script setup>
import { storeToRefs } from "pinia";

import { computed, nextTick, ref, watch, defineProps, onMounted } from "vue";
import { countryStore } from "@/stores/countries.js";
import { brandStore } from "@/stores/brand.js";
import { rules } from "/src/includes/customValidationRules.js";
import { useDisplay } from "vuetify";
import Button from "../base/form-elements/Button.vue";
import { useSnackbarStore } from "@/stores/snackbar";

const { xs, sm } = useDisplay();

// Extracting from top level
const props = defineProps(["store"]);
const { store } = props;
const { collection, errors, res, loading } = storeToRefs(store);

const brandStorage = brandStore();
const countryStorage = countryStore();

// Table header
const headers = ref([
  { title: "Supplier ID", key: "id" },
  { title: "Name", key: "name" },
  { title: "Email", key: "email" },
  { title: "Country", key: "country.name" },
  { title: "Courier address", key: "address" },
  { title: "Open hours", key: "opening_time" },
  { title: "Tax", key: "tax" },
  { title: "Phone", key: "phone" },
  { title: "Actions", key: "actions", align: "end", sortable: false },
]);

// Table and table item state:
const search = ref("");
const selected = ref([]);

const editedIndex = ref(-1);
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

// Dialogs and state for table item
const dialog = ref(false);
const dialogDelete = ref(false);

async function initialize() {
  await store.fetchItems();
}

onMounted(async () => {
  await brandStorage.fetchItems();
  await countryStorage.fetchItems();
});

const formTitle = computed(() => {
  return editedIndex.value === -1 ? `New ${store.name}` : `Edit ${store.name}`;
});

// Domain specific
function editItem(managedItem) {
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  dialog.value = true;
}

// Domain specific
function close() {
  dialog.value = false;
  setTimeout(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
    editedIndex.value = -1;
    errors.value = {};
  }, 250);
}

// Domain specific
function closeDelete() {
  dialogDelete.value = false;
  nextTick(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
    editedIndex.value = -1;
  });
}

// Domain specific
const save = async () => {
  if (editedIndex.value > -1) {
    await store.updateItemHandler(editedItem.value.id, editedItem.value);
    if (res.value) {
      initialize();
      close();
      snackbarShow(res.value.message, res.value.status);
    } else {
      snackbarShow(errors.value, "error");
    }
  } else {
    await store.addItemHandler(editedItem.value);
    if (res.value) {
      initialize();
      close();
      snackbarShow(res.value.message, res.value.status);
    } else {
      snackbarShow(errors.value, "error");
    }
  }
  res.value = null;
};

// Export
const exportItems = async () => {
  await store.exportItemsHandler();

  const csvContent =
    "data:text/csv;charset=utf-8," +
    encodeURIComponent(
      headers.value.map((header) => header.title).join(",") +
        "\n" +
        store.csvData
    );
  console.log(store.csvData);
  const downloadLink = document.createElement("a");
  downloadLink.setAttribute("href", csvContent);
  downloadLink.setAttribute("download", `${store.name}s.csv`);
  document.body.appendChild(downloadLink);

  downloadLink.click();

  document.body.removeChild(downloadLink);
};

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

watch(dialog, (val) => {
  val || close();
});

watch(dialogDelete, (val) => {
  val || closeDelete();
});

initialize();
</script>
