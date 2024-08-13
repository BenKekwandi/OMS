<template>
  <v-text-field
    v-show="xs || sm"
    class="mb-2 mx-2"
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
    :mobile="sm || xs"
  >
    <template v-slot:top>
      <v-toolbar color="#071d35" flat class="px-2">
        <ExportButton
          :store="store"
          :headers="headers.map((header) => header.title)"
          variant="flat"
          class="mr-2"
        />
        <Button
          v-if="permission"
          color="#5C6BC0"
          variant="flat"
          label="Import"
          class="mr-2"
          icon="mdi-file-upload"
          @click="showImport"
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

        <Button
          v-if="
            (permission && status === 'All') || (permission && status === 'New')
          "
          color="#EF5350"
          variant="flat"
          label="Delete"
          class="mr-2"
          icon="mdi-trash-can-outline"
          @click="deleteItem"
        />

        <v-dialog v-if="permission" v-model="dialog" max-width="1000px">
          <template v-slot:activator="{ props }">
            <Button
              v-if="
                (permission && status === 'All') ||
                (permission && status === 'New')
              "
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
                          <v-autocomplete
                            label="Brand"
                            prepend-inner-icon="mdi-tag-plus"
                            v-model="editedItem.brand_id"
                            item-title="name"
                            item-value="id"
                            @update:model-value="
                              (selectedBrand) => modifyModels(selectedBrand)
                            "
                            :items="brand_store.collection"
                            :rules="[rules.required]"
                          ></v-autocomplete>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-combobox
                            v-model="editedItem.reference_number"
                            label="Model"
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
                        <v-col cols="12" sm="6" md="6">
                          <DatePicker
                            v-model="editedItem.deadline"
                            label="Deadline"
                            :rules="[
                              rules.required,
                              rules.order_deadline(deadlineDate),
                            ]"
                            variant="default"
                            @dateForRule="getDeadlineDate"
                          />
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-autocomplete
                            label="Customer"
                            prepend-inner-icon="mdi-account-circle"
                            v-model="editedItem.customer_id"
                            item-title="name"
                            item-value="id"
                            :items="customerStorage.collection"
                            :rules="[rules.required]"
                          ></v-autocomplete>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="12" md="12">
                          <v-textarea
                            label="Other Features"
                            rows="3"
                            prepend-inner-icon="mdi-star-circle-outline"
                            v-model="editedItem.other_features"
                          ></v-textarea>
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
                <v-btn color="blue-darken-1" variant="outlined" @click="close"
                  >Cancel</v-btn
                >
                <v-btn
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

        <DeleteDialog
          v-model="dialogDelete"
          item="order"
          type="delete"
          :loading="loading"
          @close="closeDelete"
          @confirm="deleteItemConfirm"
        />

        <v-dialog v-model="dialogRenew" max-width="500px">
          <v-card class="pa-3">
            <v-card-title class="text-h5 text-center"
              >Are you sure you want to renew <br />selected
              {{ store.name }}(s)?</v-card-title
            >
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <DatePicker
                    v-model="extendedDeadline"
                    label="Deadline"
                    variant="default"
                    @dateForRule="extendedDeadlineDate"
                  />
                </v-col>
              </v-row>
            </v-card-text>
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
                @click="renewOrdersConfirm"
                >Renew</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
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
    <template v-slot:item.matches="{ item }">
      <div v-show="permission">
        <v-btn
          icon=""
          v-if="
            item.matches > 0 &&
            (item.status === 'New' || item.status === 'Proposed')
          "
          size="small"
          class="me2"
          :color="!item.is_read ? 'secondary' : 'transparent'"
          @click="showMatches(item)"
        >
          {{ item.matches }}
        </v-btn>
        <div v-else-if="item.matches === 0">-</div>
      </div>
      <div v-show="!permission">
        <div v-if="item.matches > 0">
          {{ item.matches }}
        </div>
        <div v-else>-</div>
      </div>
    </template>

    <template v-slot:item.status="{ item }">
      <v-chip
        variant="outlined"
        size="small"
        v-if="item.status === 'New'"
        color="blue-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Proposed'"
        color="blue-darken-3"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'SM Confirmed'"
        color="green-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'PM Confirmed'"
        color="green-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Invoice Received'"
        color="deep-purple-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        label
        v-else-if="item.status === 'invoice to Supplier Paid'"
        color="blue-darken-3"
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Cancelled'"
        color="red-darken-1"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        label
        v-else-if="item.status === 'Invoice issued'"
        color="red-darken-1"
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        label
        v-else-if="item.status === 'invoice from Customer Paid'"
        color="yellow-light-3"
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Expired'"
        color="amber-darken-1"
        label
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
          item.status === 'Delivered to the Customer' ||
          item.status === 'Delivered'
        "
        color="teal-darken-3"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Shipment booked'"
        color="purple-darken-3"
        label
      >
        {{ item.status }}
      </v-chip>
      <v-chip
        variant="outlined"
        size="small"
        v-else-if="item.status === 'Finalized'"
        color="red-darken-3"
        label
      >
        {{ item.status }}
      </v-chip>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon
        v-if="permission && item.status === 'New'"
        size="small"
        class="me-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-btn
        icon="mdi-file-document-plus"
        v-if="
          (item.status === 'PM Confirmed' && !permission) ||
          (item.status === 'Ready for Shipment' && !permission)
        "
        color="#193a63"
        size="small"
        variant="text"
        @click="showInvoice(item)"
      >
      </v-btn>
    </template>

    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
    <template v-slot:item.image="{ item }">
      <v-img :src="item.image" width="80" height="80"></v-img>
    </template>
  </v-data-table>

  <ImportDialog
    v-if="permission"
    v-model="dialogImport"
    name="order"
    @upload="uploadImportFile"
    :importHeaders="importHeaders"
  />

  <MatchesDialog
    v-if="permission"
    v-model="dialogMatches"
    :order="editedItem"
    @createProposal="createNewProposal"
    :proposalStore="proposalStorage"
  />

  <InvoiceDialog
    v-model="dialogInvoice"
    :order="editedItem"
    @upload="uploadInvoice"
    :loading="invoiceLoading"
  />
</template>

<script setup>
import ImageUpload from "@/components/base/form-elements/ImageUpload.vue";
import { storeToRefs } from "pinia";
import format from "date-fns/format";
import { computed, nextTick, ref, watch, defineProps } from "vue";
import { rules } from "/src/includes/customValidationRules.js";
import { customerStore } from "@/stores/customer.js";
import { modelStore } from "@/stores/model.js";
import { warehouseStore } from "@/stores/warehouses.js";
import { proposalStore } from "@/stores/proposal.js";
import { useInvoiceStore } from "@/stores/invoices";
import { useDisplay } from "vuetify";
import Button from "../form-elements/Button.vue";
import DatePicker from "../form-elements/DatePicker.vue";
import MatchesDialog from "../dialogs/MatchesDialog.vue";
import InvoiceDialog from "../dialogs/InvoiceDialog.vue";
import ImportDialog from "../dialogs/ImportDialog.vue";
import DeleteDialog from "../dialogs/DeleteDialog.vue";
import ExportButton from "../form-elements/ExportButton.vue";

const { xs, sm } = useDisplay();

const proposalStorage = proposalStore();
const invoiceStore = useInvoiceStore();

const props = defineProps([
  "store",
  "brand_store",
  "status",
  "permission",
  "excludeColumns",
]);
const { store, brand_store, status, permission, excludeColumns } = props;

const { collection, errors, res, loading } = storeToRefs(store);
const { errors: proposalErrors, res: proposalRes } =
  storeToRefs(proposalStorage);
const {
  errors: invoiceErrors,
  res: invoiceRes,
  loading: invoiceLoading,
} = storeToRefs(invoiceStore);

const emit = defineEmits(["alert", "initialize"]);

const items = computed(() => {
  if (status !== "All") {
    if (status === "Confirmed") {
      return collection.value.filter(
        (item) =>
          item.status === "PM Confirmed" || item.status === "SM Confirmed"
      );
    } else return collection.value.filter((item) => item.status == status);
  } else return collection.value;
});

const dialog = ref(false);
const dialogDelete = ref(false);
const dialogMatches = ref(false);
const dialogInvoice = ref(false);
const dialogRenew = ref(false);
const dialogImport = ref(false);
const form = ref();
const editedIndex = ref(-1);
const search = ref("");
const selected = ref([]);
const extendedDeadline = ref(null);

const chosenSupplier = ref({
  name: "",
});

const headers = ref(
  [
    { title: "OMS Order ID", key: "id", align: "center" },
    { title: "Order Creation Date", key: "created_at" },
    { title: "Order Confirmation Date", key: "confirmed_at" },
    { title: "Status", key: "status", align: "center" },
    { title: "Customer", key: "customer.name" },
    { title: "Country", key: "customer.country.name" },
    { title: "Supplier", key: "supplier.name", sortable: true },
    { title: "Brand", key: "brand.name" },
    { title: "Reference Number", key: "reference_number" },
    { title: "Image", align: "center", value: "image" },
    { title: "Offer ID", key: "offer_id" },
    { title: "Matches", key: "matches", align: "center" },
    { title: "Serial Number", key: "offer.serial_number" },
    { title: "Additional Info", key: "other_features" },
    { title: "Deadline", key: "deadline" },
    { title: "Expected Arrival", key: "shipment.collected_at" },
    { title: "Expected Delivery", key: "shipment.label.expected_delivery_at" },
    { title: "RRP", key: "offer.rrp_price" },
    { title: "Net Purchasing Price", key: "offer.net_price" },
    { title: "Sales Price", key: "proposal.sell_price" },
    { title: "Shipping Cost", key: "shipping_cost" },
    { title: "Total Expenses", key: "total_expenses" },
    !permission ? { title: "Profit", key: "proposal.profit" } : null,
    !permission ? { title: "Proforma Invoice from Supplier", key: "customer_invoice.file" } : null,
    !permission ? { title: "Payment Date to Supplier", key: "supplier_invoice.invoicing_date" } : null,
    { title: "Invoice to the Customer", key: "customer_invoice.file" },
    {
      title: "Payment Date of the Customer",
      key: "customer_invoice.invoicing_date",
    },
    { title: "Supplier Shipment Date", key: "shipment.collected_at" },
    { title: "Tracking #", key: "shipment.label.tracking_number" },
    { title: "Delivery Date", key: "shipment.delivered_at" },
    { title: "Invoice for Shipment", key: "shipment.label.file" },
    { title: "Order Finalization Date", key: "finalized_at" },
    { title: "Actions", key: "actions", sortable: false, align: "end" },
  ].filter((header) => header !== null)
);

const editedItem = ref({
  customer: {},
  brand: {},
  reference_number: "",
  deadline: null,
  other_features: "",
});

const defaultItem = ref({
  customer: {},
  brand: {},
  reference_number: "",
  deadline: null,
  other_features: "",
});

//format dates
const deadlineDate = ref(null);
const extendedDeadlineDate = ref(null);

function getDeadlineDate(val) {
  deadlineDate.value = val;
}

watch(
  () => deadlineDate.value,
  (val) => {
    editedItem.value.deadline = format(val, "do MMM yyyy");
  }
);

watch(
  () => extendedDeadlineDate.value,
  (val) => {
    extendedDeadline.value = format(val, "do MMM yyyy");
  }
);

const image = ref("");
const invoiceFile = ref("");

const importHeaders = [
  "customer",
  "brand",
  "reference number",
  "other features",
  "deadline",
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
      // if (key === "deadline") {
      //   formData.append(key, format(editedItem.value[key], "do MMM yyyy"));
      // } else {
      formData.append(key, editedItem.value[key]);
      // }
    }
    return formData;
  } else {
    return editedItem.value;
  }
}

function invoiceFormType(invoice) {
  const formData = new FormData();
  for (const key in invoice) {
    formData.append(key, invoice[key]);
  }
  return formData;
}

const modelStorage = modelStore();
const customerStorage = customerStore();
const warehouseStorage = warehouseStore();

const models = computed(() => {
  return modelStorage.collection;
});

const modifyModels = async (selectedBrand) => {
  await modelStorage.fetchItems(selectedBrand);
};

async function initialize() {
  await warehouseStorage.fetchItems();
  if (permission) {
    await customerStorage.fetchItems();
  }
}

const formTitle = computed(() => {
  return `${editedIndex.value === -1 ? "New" : "Edit a"} ${store.name}`;
});

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

function editItem(managedItem) {
  editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  image.value = managedItem.image;
  dialog.value = true;
}

function deleteItem() {
  if (selected.value.length) {
    dialogDelete.value = true;
  } else {
    emit("alert", "Select Order(s) to delete first.", "error");
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
      const data = formType();
      await store.updateItemHandler(editedItem.value.id, data);
      if (res.value) {
        emit("initialize");
        emit("alert", res.value.message, res.value.status);
        close();
      } else {
        emit("alert", errors.value, "error");
      }
    } else {
      const data = formType();
      await store.addItemHandler(data);
      if (res.value) {
        emit("initialize");
        emit("alert", res.value.message, res.value.status);
        close();
      } else {
        emit("alert", errors.value, "error");
      }
    }
  }
  res.value = null;
};

// Matches functionality
async function showMatches(managedItem) {
  editedItem.value = Object.assign({}, managedItem);
  dialogMatches.value = true;
  managedItem.is_read = true;
}

function closeMatches() {
  dialogMatches.value = false;
  nextTick(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
  });
}

async function createNewProposal(newProposal) {
  await proposalStorage.addItemHandler(newProposal);
  if (proposalRes.value) {
    emit("initialize");
    emit("alert", proposalRes.value.message, proposalRes.value.status);
    closeMatches();
  } else {
    emit("alert", proposalErrors.value, "error");
  }

  proposalRes.value = null;
}

// Invoice functionality
function showInvoice(managedItem) {
  // editedIndex.value = collection.value.indexOf(managedItem);
  editedItem.value = Object.assign({}, managedItem);
  dialogInvoice.value = true;
}

function closeInvoice() {
  dialogInvoice.value = false;
  nextTick(() => {
    editedIndex.value = -1;
  });
}

const uploadInvoice = async (invoice) => {
  const data = invoiceFormType(invoice);
  await invoiceStore.uplaodInvoiceSupplierHandler(editedItem.value.id, data);
  if (invoiceRes.value) {
    emit("initialize");
    emit("alert", invoiceRes.value.message, "success");
    closeInvoice();
  } else {
    emit("alert", invoiceErrors.value, "error");
  }
  invoiceRes.value = null;
};

const uploadImportFile = async (file) => {
  const data = invoiceFormType(file);
  await store.uploadFileHandler(data);
  if (res.value) {
    emit("initialize");
    emit("alert", res.value.message, "success");
    closeImport();
  } else {
    emit("alert", errors.value, "error");
  }
  res.value = null;
};

function showRenew() {
  if (selected.value.length) {
    dialogRenew.value = true;
  } else {
    emit("alert", "Select Offer(s) to renew first.", "error");
  }
}

function showImport() {
  dialogImport.value = true;
}

function closeImport() {
  dialogImport.value = false;
  nextTick(() => {
    editedIndex.value = -1;
  });
}

const renewOrdersConfirm = async () => {
  await store.resetHandler({
    items: selected.value,
    deadline: extendedDeadline.value,
  });
  if (res.value) {
    collection.value = collection.value.filter(
      (value) => !selected.value.includes(value.id)
    );
    emit("initialize");
    emit("alert", res.value.message, "success");
    selected.value = [];
  } else {
    emit("alert", errors.value, "error");
  }
  res.value = null;
  closeRenew();
};

function closeRenew() {
  dialogRenew.value = false;
  nextTick(() => {
    extendedDeadline.value = null;
    editedIndex.value = -1;
  });
}

watch(dialog, (val) => {
  val || close();
});

watch(dialogDelete, (val) => {
  val || closeDelete();
});

watch(dialogMatches, (val) => {
  val || closeMatches();
});

watch(dialogInvoice, (val) => {
  val || closeInvoice();
});

watch(dialogRenew, (val) => {
  val || closeRenew();
});

watch(dialogImport, (val) => {
  val || closeImport();
});

initialize();
</script>
