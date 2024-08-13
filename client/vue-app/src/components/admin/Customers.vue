<template>
  <v-snackbar min-width="100" v-model="snackbar.show" :color="snackbar.color" :timeout="3000">
    <div class="d-flex justify-center align-center">
      <div>{{ snackbar.text }}</div>
    </div>
  </v-snackbar>

  <v-btn prepend-icon=" mdi-arrow-left" @click="goBack" variant="text" color="#00ADB5">
    Users
  </v-btn>
  <v-data-table :headers="mainHeaders" :loading="loading" :items="collection">
    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <v-btn prepend-icon="mdi-file-table-outline" color="#66BB6A" variant="flat" class="mx-2">Export</v-btn>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" rounded
          single-line flat hide-details variant="solo-filled">
        </v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-btn @click="transfer(item)" color="#193a63" icon=" mdi-account-arrow-right" variant="plain"></v-btn>
      <v-btn @click="editItem(item)" color="#193a63" icon="mdi-pencil" variant="plain"></v-btn>
    </template>

    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>

  <v-dialog v-model="dialog" max-width="500px" scrollable>
    <v-card>
      <v-container>
        <v-card-title>
          <span class="text-h5">Purchase Managers</span>
        </v-card-title>
        <v-divider></v-divider>

        <v-card-text>
          <v-row>
            <v-data-table-virtual :headers="dialogHeaders" :items="managers" height="400" item-value="id"
              select-strategy="single" show-select v-model="selectedManager" density="compact">
            </v-data-table-virtual>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="#00ADB5" @click="close" variant="outlined">
            Cancel
          </v-btn>
          <v-btn color="#00ADB5" @click="handleTransfer" :loading="loading" variant="flat">
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
          <span class="text-h5">Edit Customer</span>
        </v-card-title>
        <v-divider class="my-2 mx-4"></v-divider>

        <v-card-text>
          <v-form ref="form">
            <v-row>
              <v-col cols="6">
                <v-text-field label="Name" prepend-inner-icon="mdi-account-circle" v-model="editedItem.name"
                  :rules="[rules.required]"></v-text-field>
              </v-col>
              <v-col cols="6" sm="15" md="15">
                <v-text-field label="Email" prepend-inner-icon="mdi-at" v-model="editedItem.email"
                  :rules="[rules.email]"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-autocomplete label="Country" prepend-inner-icon="mdi-earth" v-model="editedItem.country_id"
                  item-title="name" item-value="id" :items="countryStorage.collection"
                  :rules="[rules.required]"></v-autocomplete>
              </v-col>
              <v-col cols="6">
                <v-text-field label="Phone Number" prepend-inner-icon="mdi-phone" v-model="editedItem.phone"
                  :rules="[rules.phone]"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-textarea label="Shipping Address" rows="3" prepend-inner-icon="mdi-map-marker"
                  v-model="editedItem.shipping_address"></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-textarea label="Billing Address" rows="3" prepend-inner-icon="mdi-map-marker"
                  v-model="editedItem.billing_address"></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-checkbox label="On Credit" v-model="editedItem.is_credit"></v-checkbox>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <v-card-actions class="mx-4 my-4">
          <v-spacer></v-spacer>
          <v-btn class="px-4" color="blue-darken-1" variant="outlined" @click="closeEdit">Cancel</v-btn>
          <v-btn class="px-6" color="blue-darken-1" variant="elevated" :loading="loading" @click="update">Update</v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, watch, nextTick, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { customerStore } from "@/stores/customer";
import { countryStore } from "@/stores/countries.js";
import { rules } from "/src/includes/customValidationRules.js";
import { storeToRefs } from "pinia";
import { useUserStore } from "@/stores/users";

const route = useRoute();
const router = useRouter();
const id = ref(route.params.id);

const customers_store = customerStore();
const users_store = useUserStore();
const countryStorage = countryStore();

const { collection, errors, loading, res } = storeToRefs(customers_store);

async function initialize() {
  await customers_store.fetchAssociated(id.value);
  await users_store.fetchUsers();
  await countryStorage.fetchItems();
}

const managers = computed(() => {
  return users_store.collection.filter(
    (item) => item.id != id.value && item.role === "sm"
  );
});


const editedItem = ref({
  name: "",
  email: "",
  phone: "",
  country_id: "",
  billing_address: "",
  shipping_address: "",
  is_credit: false,
});

const defaultItem = ref({
  name: "",
  email: "",
  phone: "",
  country_id: "",
  billing_address: "",
  shipping_address: "",
  is_credit: false,
});

const search = ref("");
const dialog = ref(false);
const selectedCustomer = ref();
const selectedManager = ref();
const editedIndex = ref(-1);
const dialogEdit = ref(false);
const form = ref();

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

const transfer = (customer) => {
  dialog.value = true;
  selectedCustomer.value = customer.id;
};

const handleTransfer = async () => {
  await customers_store.transferCustomer(
    selectedCustomer.value,
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

const update = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    await customers_store.updateItemHandler(
      editedItem.value.id,
      editedItem.value
    );
    if (res.value) {
      Object.assign(collection.value[editedIndex.value], editedItem.value);
      closeEdit();
      snackbarShow(res.value.message, res.value.status);
    } else {
      snackbarShow(errors.value, "error");
    }
    res.value = null;
  }
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
  selectedCustomer.value = "";
}

const snackbar = ref({
  show: false,
  text: "",
  color: "",
});

const snackbarShow = (message, type) => {
  snackbar.value = {
    show: true,
    text: message,
    color: type,
  };
};

const goBack = () => {
  router.go(-1);
};

initialize();

watch(dialog, (val) => {
  val || closeEdit();
});
</script>
