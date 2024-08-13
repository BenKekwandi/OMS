<template>
  <v-divider class="mb-2"></v-divider>
  <v-text-field
    v-show="mobile"
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

        <Button
          color="#EF5350"
          variant="flat"
          label="Delete"
          class="mr-2"
          icon="mdi-trash-can-outline"
          @click="deleteItem"
        />

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
                <v-form ref="form">
                  <v-row>
                    <v-col cols="6">
                      <v-text-field
                        label="Name"
                        prepend-inner-icon="mdi-account-circle"
                        v-model="editedItem.name"
                        :rules="[rules.required]"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                      <v-text-field
                        label="Contact Name"
                        prepend-inner-icon="mdi-account-circle"
                        v-model="editedItem.contact_name"
                        :rules="[rules.required]"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="6">
                      <v-autocomplete
                        label="Country"
                        prepend-inner-icon="mdi-earth"
                        v-model="editedItem.country_id"
                        item-title="name"
                        item-value="id"
                        :items="countryStorage.collection"
                        :rules="[rules.required]"
                      ></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
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
                    <v-col cols="12">
                      <v-textarea
                        label="Shipping Address"
                        rows="3"
                        prepend-inner-icon="mdi-map-marker"
                        v-model="editedItem.shipping_address"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <v-textarea
                        label="Billing Address"
                        rows="3"
                        prepend-inner-icon="mdi-map-marker"
                        v-model="editedItem.billing_address"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                      <v-checkbox
                        label="On Credit"
                        v-model="editedItem.is_credit"
                      ></v-checkbox>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>

              <v-card-actions class="mx-4 my-4">
                <v-spacer></v-spacer>
                <v-btn
                  class="px-4"
                  color="blue-darken-1"
                  variant="outlined"
                  @click="close"
                  >Cancel</v-btn
                >
                <v-btn
                  class="px-6"
                  color="blue-darken-1"
                  variant="elevated"
                  :loading="loading"
                  @click="save"
                  >Save</v-btn
                >
              </v-card-actions>
            </v-container>
          </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDelete" max-width="400px">
          <v-card class="pa-3">
            <v-card-title class="text-center"
              >Are you sure you want to <br />
              delete {{ store.name }}(s)?</v-card-title
            >
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                >Cancel</v-btn
              >
              <v-btn
                color="blue-darken-1"
                variant="text"
                :loading="loading"
                @click="deleteItemConfirm"
                >OK</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-text-field
          v-show="!mobile"
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
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { countryStore } from "@/stores/countries.js";
import { computed, nextTick, ref, watch, defineProps } from "vue";
import { rules } from "/src/includes/customValidationRules.js";
import { useDisplay } from "vuetify";
import Button from "../base/form-elements/Button.vue";
import { useSnackbarStore } from "@/stores/snackbar";

const { mobile } = useDisplay();

const props = defineProps(["store"]);
const { store } = props;
const { collection, errors, res, loading } = storeToRefs(store);

const countryStorage = countryStore();

const headers = ref([
  { title: "Name", key: "name" },
  { title: "Email", key: "email" },
  { title: "Country", key: "country.name" },
  { title: "Phone Number", key: "phone" },
  { title: "Shipping Address", key: "shipping_address" },
  { title: "Billing Address", key: "billing_address", sortable: false },
  { title: "Actions", key: "actions", sortable: false, align: "end" },
]);

const editedItem = ref({
  name: "",
  contact_name: "",
  email: "",
  phone: "",
  country_id: "",
  billing_address: "",
  shipping_address: "",
  is_credit: false,
});

const defaultItem = ref({
  name: "",
  contact_name: "",
  email: "",
  phone: "",
  country_id: "",
  billing_address: "",
  shipping_address: "",
  is_credit: false,
});

const dialog = ref(false);
const dialogDelete = ref(false);
const search = ref("");
const selected = ref([]);
const editedIndex = ref(-1);
const form = ref();

async function initialize() {
  await store.fetchItems();
  await countryStorage.fetchItems();
}

const formTitle = computed(() => {
  return `${editedIndex.value === -1 ? `New ` : "Edit a "}${store.name}`;
});

function editItem(managedItem) {
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  dialog.value = true;
}

function deleteItem() {
  if (selected.value.length) {
    dialogDelete.value = true;
  } else {
    snackbarShow("Select Customer(s) to delete first", "error");
  }
}

const deleteItemConfirm = async () => {
  const ids = selected.value.map((id) => {
    return {
      id,
    };
  });

  await store.deleteItemHandler(ids);
  if (res.value) {
    collection.value = collection.value.filter(
      (value) => !selected.value.includes(value.id)
    );
    snackbarShow(res.value.message, "success");
  } else {
    snackbarShow(errors.value, "error");
  }
  res.value = null;
  closeDelete();
};

function close() {
  dialog.value = false;
  nextTick(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
    editedIndex.value = -1;
    errors.value = {};
  });
}

function closeDelete() {
  dialogDelete.value = false;
  nextTick(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
    editedIndex.value = -1;
  });
}

const save = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    if (editedIndex.value > -1) {
      await store.updateItemHandler(editedItem.value.id, editedItem.value);
      if (res.value) {
        Object.assign(collection.value[editedIndex.value], editedItem.value);
        close();
        snackbarShow(res.value.message, res.value.status);
      } else {
        snackbarShow(errors.value, "error");
      }
    } else {
      await store.addItemHandler(editedItem.value);
      if (res.value) {
        await store.fetchItems();
        close();
        snackbarShow(res.value.message, res.value.status);
      } else {
        snackbarShow(errors.value, "error");
      }
    }
    res.value = null;
  }
};

const exportItems = async () => {
  await store.exportItemsHandler();

  const csvContent =
    "data:text/csv;charset=utf-8," +
    encodeURIComponent(
      headers.value.map((header) => header.title).join(",") +
        "\n" +
        store.csvData
    );
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
