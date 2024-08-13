<template>
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
    :show-select="permission"
    :loading="loading"
    :headers="
      excludeColumns
        ? headers.filter((header) => !excludeColumns.includes(header.title))
        : headers
    "
    :items="items"
    :sort-by="[{ key: 'id', order: 'desc' }]"
  >
    <template v-slot:top>
      <v-toolbar color="#071d35" flat class="px-2">
        <Button
          v-if="permission"
          color="#66BB6A"
          @click="exportItems"
          variant="flat"
          class="mr-2"
          label="Export"
          icon="mdi-file-download"
        />
        <Button
          v-if="permission"
          color="#5C6BC0"
          variant="flat"
          label="Import"
          class="mr-2"
          icon="mdi-file-upload"
        />

        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <Button
          v-if="permission && status === 'Expired'"
          color="#039BE5"
          variant="flat"
          label="Renew"
          class="mr-2"
          icon="mdi-autorenew"
          @click="showRenew"
        />

        <v-dialog v-if="permission" v-model="dialog" max-width="1000px">
          <template v-slot:activator="{ props }">
            <Button
              v-if="status === 'All' || status === 'New'"
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
                    <v-col>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-select
                            label="Brand"
                            prepend-inner-icon="mdi-tag-plus"
                            v-model="editedItem.brand_id"
                            item-title="name"
                            item-value="id"
                            @update:model-value="
                              (selectedBrand) => modifyModels(selectedBrand)
                            "
                            :items="brandStorage.collection"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-combobox
                            v-model="editedItem.reference_number"
                            label="Reference"
                            prepend-inner-icon="mdi-cube-outline"
                            @update:model-value="(model) => handleInput(model)"
                            :items="modelStorage.collection"
                            item-value="reference"
                            item-title="reference"
                            :rules="[rules.required]"
                          ></v-combobox>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="12" md="12">
                          <v-textarea
                            label="Other Features"
                            rows="2"
                            prepend-inner-icon="mdi-star-circle-outline"
                            v-model="editedItem.other_features"
                          ></v-textarea>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-select
                            label="Supplier"
                            prepend-inner-icon="mdi-account-circle"
                            @update:modelValue="
                              (newValue) => modifySupplierId(newValue.id)
                            "
                            v-model="chosenSupplier"
                            item-title="name"
                            item-value="id"
                            return-object
                            :items="supplierStorage.collection"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            label="Discount"
                            v-model.number="editedItem.discount"
                            prepend-inner-icon="mdi-cash"
                            @update:modelValue="netValue"
                            :rules="[rules.required]"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            label="RRP"
                            prepend-inner-icon="mdi-cash"
                            v-model.number="editedItem.rrp_price"
                            @update:modelValue="netValue"
                            :rules="[rules.required]"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            label="Net"
                            prepend-inner-icon="mdi-cash"
                            :model-value="editedItem.net_price"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-select
                            label="Availability"
                            prepend-inner-icon="mdi-store"
                            v-model="editedItem.availability"
                            @update:modelValue="
                              (value) => availabilityModifier(value)
                            "
                            item-title="name"
                            item-value="val"
                            :items="availabilityItems"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            v-if="
                              !editedItem.availability ||
                              editedItem.availability === 1 ||
                              editedItem.availability === 'In shop'
                            "
                            label="Order days"
                            prepend-inner-icon="mdi-timer-outline"
                            v-model="editedItem.order_days"
                            readonly
                          ></v-text-field>
                          <v-text-field
                            v-else-if="
                              editedItem.availability === 2 ||
                              editedItem.availability === 'To order'
                            "
                            label="Order days"
                            prepend-inner-icon="mdi-timer-outline"
                            v-model="editedItem.order_days"
                          ></v-text-field>
                          <v-select
                            v-else
                            label="Location"
                            prepend-inner-icon="mdi-map-marker"
                            v-model="editedItem.warehouse_id"
                            item-title="country"
                            item-value="id"
                            :items="warehouseStorage.collection"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="12" md="12">
                          <v-text-field
                            v-if="
                              editedItem.availability === 3 ||
                              editedItem.availability === 'In stock'
                            "
                            label="Serial number"
                            append-inner-icon="mdi-pound"
                            v-model="editedItem.serial_number"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-col>
                    <v-col
                      cols="12"
                      sm="4"
                      md="4"
                      class="d-flex justify-center"
                    >
                      <ImageUpload
                        @imageData="handleImageData"
                        :image="image"
                      />
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

        <v-dialog v-model="dialogDelete" max-width="400px">
          <v-card class="pa-3">
            <v-card-title class="text-center"
              >Are you sure you want to <br />delete selected
              {{ store.name }}(s)?</v-card-title
            >
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">
                Cancel
              </v-btn>
              <v-btn
                color="blue-darken-1"
                :loading="loading"
                variant="text"
                @click="deleteItemConfirm"
              >
                OK
              </v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="dialogRenew" max-width="500px">
          <v-card class="pa-3">
            <v-card-title class="text-h5 text-center"
              >Are you sure you want to renew <br />selected
              {{ store.name }}(s)?</v-card-title
            >
            <v-card-actions class="mx-4 my-4">
              <v-spacer></v-spacer>
              <v-btn
                class="px-4"
                color="blue-darken-1"
                variant="outlined"
                @click="closeRenew"
                >Cancel</v-btn
              >
              <v-btn
                class="px-6"
                color="blue-darken-1"
                variant="elevated"
                :loading="loading"
                @click="renewOfferConfirm"
                >Renew</v-btn
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

    <template v-slot:item.status="{ item }">
      <v-chip variant="outlined" v-if="item.status === 'New'" color="blue-darken-1" label>
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" v-else-if="item.status === 'Proposed'" color="blue-darken-3" label>
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" v-else-if="item.status === 'Confirmed'" color="green-darken-1" label>
        {{ item.status }}
      </v-chip>
       <v-chip variant="outlined" v-else-if="item.status === 'Cancelled'" color="red-darken-1" label>
        {{ item.status }}
      </v-chip>
      <v-chip variant="outlined" v-else-if="item.status === 'Expired'" color="grey-darken-1" label>
        {{ item.status }}
      </v-chip>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon
        v-if="permission"
        size="small"
        class="me-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
    </template>

    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
    <template v-slot:item.image="{ item }">
      <v-img :src="item.image" width="80" height="80"></v-img>
    </template>
  </v-data-table>
</template>

<script setup>
import ImageUpload from "@/components/base/form-elements/ImageUpload.vue";
import { storeToRefs } from "pinia";
import { computed, nextTick, ref, watch, defineProps } from "vue";
import { rules } from "/src/includes/customValidationRules.js";
import { brandStore } from "@/stores/brand.js";
import { modelStore } from "@/stores/model.js";
import { supplierStore } from "@/stores/supplier.js";
import { warehouseStore } from "@/stores/warehouses.js";
import Button from "@/components/base/form-elements/Button.vue";
import { useDisplay } from "vuetify";

const { mobile } = useDisplay();
const props = defineProps(["store", "status", "permission", "excludeColumns"]);
const { store, status, permission, excludeColumns } = props;
const { collection, errors, res, loading } = storeToRefs(store);

const emit = defineEmits(["alert"]);

const dialog = ref(false);
const dialogDelete = ref(false);
const dialogRenew = ref(false);
const search = ref("");
const selected = ref([]);

// Status filtering
const items = computed(() => {
  return status === "All"
    ? collection.value
    : collection.value.filter((item) => item.status === status);
  // if (status !== "All")
  //   return collection.value.filter((item) => item.status === status);
  // else return collection.value;
});

const headers = ref(
  [
    { title: "Offer ID", key: "id" },
    { title: "Offer Creation Date", key: "created_at" },
    { title: "Status", key: "status", align: "center" },
    { title: "Supplier", key: "supplier.name" },
    permission ? null : { title: "PM", key: "pm" },
    { title: "Brand", key: "brand.name" },
    { title: "Matches", key: "matches" },
    { title: "Reference Number", key: "reference_number" },
    { title: "Image", align: "center", value: "image" },
    { title: "Serial Number", key: "serial_number" },
    { title: "Other Features", key: "other_features" },
    { title: "Additional Info", key: "additional_info" },
    { title: "RRP", key: "rrp_price" },
    { title: "Discount %", key: "discount" },
    { title: "Net Purchasing Price", key: "net_price" },
    { title: "Payment Terms", key: "payment_terms" },
    { title: "Availability", key: "availability" },
    { title: "Location", key: "location" },
    permission ? { title: "Actions", key: "actions", sortable: false } : null,
  ].filter((header) => header !== null)
);

const editedIndex = ref(-1);
const editedItem = ref({
  supplier_id: "",
  brand_id: "",
  reference_number: "",
  discount: "",
  image: "",
  location: "",
  rrp_price: "",
  serial_number: "",
  net_price: "",
  other_features: "",
  order_days: "",
});
const defaultItem = ref({
  supplier_id: "",
  brand_id: "",
  reference_number: "",
  discount: "",
  image: "",
  location: "",
  rrp_price: "",
  serial_number: "",
  net_price: "",
  other_features: "",
  order_days: "",
});

// Advanced elements:
const form = ref();
const image = ref("");

const availabilityItems = [
  { name: "In shop", val: 1 },
  { name: "To order", val: 2 },
  { name: "In stock", val: 3 },
];

function handleInput(model) {
  if (typeof model == "object" && model != null) {
    image.value = model.image;
    editedItem.value.image = model.image;
    editedItem.value.reference_number = model.reference;
  } else {
    editedItem.value.reference_number = model;
    image.value = ""; // when the input field is cleared
  }
}

const handleImageData = (data) => {
  editedItem.value.image = data;
};

function formType() {
  if (typeof editedItem.value.image !== "string") {
    const formData = new FormData();
    for (const key in editedItem.value) {
      formData.append(key, editedItem.value[key]);
    }
    return formData;
  } else {
    return editedItem.value;
  }
}

const brandStorage = brandStore();
const modelStorage = modelStore();
const supplierStorage = supplierStore();
const warehouseStorage = warehouseStore();

const models = computed(() => {
  return modelStorage.collection;
});

const chosenSupplier = ref({
  name: "",
});

const modifySupplierId = (providedValue) => {
  editedItem.value.supplier_id = providedValue;
  console.log(editedItem);
};

const modifyModels = async (selectedBrand) => {
  console.log(`Selected brand: ${selectedBrand}`);
  await modelStorage.fetchItems(selectedBrand);
  console.log(modelStorage);
};

const netValue = () => {
  editedItem.value.net_price =
    editedItem.value.discount &&
    editedItem.value.rrp_price &&
    chosenSupplier.value.country.vat
      ? editedItem.value.rrp_price -
        (editedItem.value.rrp_price * chosenSupplier.value.country.vat +
          editedItem.value.rrp_price * editedItem.value.discount * 0.01)
      : "";
};

async function initialize() {
  await store.fetchItems();
  await brandStorage.fetchItems();
  await warehouseStorage.fetchItems();
  if (permission) {
    await supplierStorage.fetchItems();
  }

  console.log("offers collection");
  console.log(collection);
}

const formTitle = computed(() => {
  return `${editedIndex.value === -1 ? "New" : "Edit an"} ${store.name}`;
});

function availabilityModifier(managedItem) {
  switch (managedItem) {
    case 1:
      editedItem.value.order_days = 0;
      delete editedItem.value.location;
      delete editedItem.value.serial_number;
      break;
    case 2:
      editedItem.value.order_days = 0;
      delete editedItem.value.location;
      delete editedItem.value.serial_number;
      break;
    case 3:
      delete editedItem.value.order_days;
      break;
  }
}

function editItem(managedItem) {
  console.log(managedItem);
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  chosenSupplier.value = managedItem.supplier;
  image.value = managedItem.image;
  dialog.value = true;
}

function deleteItem() {
  if (selected.value.length) {
    dialogDelete.value = true;
  } else {
    emit("alert", "Select Offer(s) to delete first.", "error");
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
    emit("alert", res.value.message, "success");
    selected.value = [];
  } else {
    emit("alert", errors.value, "error");
  }
  res.value = null;
  closeDelete();
};

function close() {
  chosenSupplier.value = { name: "" };
  image.value = "";
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
  console.log("edited offer");
  console.log(editedItem.value);
  const { valid } = await form.value.validate();

  if (valid) {
    if (editedIndex.value > -1) {
      const data = formType();
      await store.updateItemHandler(editedItem.value.id, data);
      if (res.value) {
        await store.fetchItems();
        close();
        emit("alert", res.value.message, res.value.status);
      } else {
        emit("alert", errors.value, "error");
      }
    } else {
      const data = formType();
      await store.addItemHandler(data);
      if (res.value) {
        await store.fetchItems();
        close();
        emit("alert", res.value.message, res.value.status);
      } else {
        emit("alert", errors.value, "error");
      }
    }
    res.value = null;
  }
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

function showRenew() {
  if (selected.value.length) {
    dialogRenew.value = true;
  } else {
    emit("alert", "Select Offer(s) to renew first.", "error");
  }
}

const renewOfferConfirm = async () => {
  await store.resetHandler({ items: selected.value });
  if (res.value) {
    collection.value = collection.value.filter(
      (value) => !selected.value.includes(value.id)
    );
    emit("alert", res.value.message, "success");
    selected.value = [];
  } else {
    emit("alert", errors.value, "error");
    console.log(errors.value);
  }

  res.value = null;
  closeRenew();
};

function closeRenew() {
  dialogRenew.value = false;
  selected.value = [];
}

watch(dialog, (val) => {
  val || close();
});

watch(dialogDelete, (val) => {
  val || closeDelete();
});

watch(dialogRenew, (val) => {
  val || closeRenew();
});

initialize();
</script>
