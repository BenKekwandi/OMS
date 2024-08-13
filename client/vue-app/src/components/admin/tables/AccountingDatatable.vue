<template>
  <v-data-table
    v-model="selected"
    :search="search"
    show-select
    :headers="headers"
    :loading="loading"
    :items="collection"
    :sort-by="[{ key: 'id', order: 'desc' }]"
  >
    <template v-slot:item.id="{ item }">
      <span
        @click="openOrderInfo(item)"
        class="text-decoration-underline text-blue-darken-4 cursor-pointer"
      >
        {{ item.id }}
      </span>
    </template>

    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <v-btn
          prepend-icon="mdi-file-download"
          @click="exportItems"
          color="#66BB6A"
          variant="flat"
          class="mx-2"
        >
          Export
        </v-btn>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

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
    <template v-slot:item.customer.name="{ item }">
      <span
        v-if="item.customer"
        class="text-decoration-underline text-blue-darken-4 cursor-pointer"
        @click="showCustomer(item.customer)"
      >
        {{ item.customer.name }}
      </span>
    </template>

    <template v-slot:item.supplier.name="{ item }">
      <span
        v-if="item.supplier"
        class="text-decoration-underline text-blue-darken-4 cursor-pointer"
        @click="showSupplier(item.supplier)"
      >
        {{ item.supplier.name }}
      </span>
    </template>

    <template v-slot:item.status="{ item }">
      <v-chip
        variant="outlined"
        size="small"
        v-if="item.status === 'PM Confirmed'"
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

    <!-- supplier -->
    <template v-slot:item.actions_supplier="{ item }">
      <div class="d-flex align-center">
        <v-tooltip
          v-if="!item.supplier_invoice"
          open-delay="500"
          text="Upload Invoice"
          location="bottom"
        >
          <template v-slot:activator="{ props }">
            <v-btn
              @click="newInvoice(item, 'supplier')"
              v-bind="props"
              color="#193a63"
              icon="mdi-file-upload"
              size="small"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip
          v-else
          open-delay="500"
          text="Edit Invoice"
          location="bottom"
        >
          <template v-slot:activator="{ props }">
            <v-btn
              @click="editInvoice(item, 'supplier')"
              v-bind="props"
              color="#193a63"
              icon="mdi-pencil"
              size="small"
              variant="text"
              :disabled="item.supplier_invoice?.is_paid ? true : false"
            >
              <v-badge
                :color="
                  item.supplier_invoice?.is_real ? 'success' : 'orange-accent-3'
                "
                floating
                dot
                inline
              >
                <v-icon color="#193a63">mdi-pencil</v-icon>
              </v-badge></v-btn
            >
          </template>
        </v-tooltip>

        <v-tooltip open-delay="500" text="View Invoice" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="
                viewInvoice(
                  item.supplier_invoice && item.supplier_invoice.file,
                  'supplier'
                )
              "
              color="#193a63"
              v-bind="props"
              icon="mdi-file-eye"
              size="small"
              :disabled="!item.supplier_invoice"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip open-delay="500" text="Download Invoice" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="downloadInvoice(item.supplier_invoice)"
              color="#193a63"
              v-bind="props"
              icon="mdi-file-download"
              size="small"
              variant="text"
              :disabled="!item.supplier_invoice"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip open-delay="500" text="Payment" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              :disabled="!item.supplier_invoice"
              @click="showPayments(item, 'supplier')"
              v-bind="props"
              size="small"
              icon=""
              variant="text"
            >
              <v-badge
                :color="
                  item.supplier_invoice?.is_paid ? 'success' : 'orange-accent-3'
                "
                floating
                dot
                inline
              >
                <v-icon color="#193a63">mdi-credit-card</v-icon>
              </v-badge>
            </v-btn>
          </template>
        </v-tooltip>
      </div>
    </template>

    <!-- customer -->
    <template v-slot:item.actions_customer="{ item }">
      <div class="d-flex align-center">
        <v-tooltip
          v-if="!item.customer_invoice"
          open-delay="500"
          text="Upload Invoice"
          location="bottom"
        >
          <template v-slot:activator="{ props }">
            <v-btn
              @click="newInvoice(item, 'customer')"
              v-bind="props"
              color="#193a63"
              icon="mdi-file-upload"
              size="small"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip
          v-else
          open-delay="500"
          text="Edit Invoice"
          location="bottom"
        >
          <template v-slot:activator="{ props }">
            <v-btn
              @click="editInvoice(item, 'customer')"
              v-bind="props"
              color="#193a63"
              icon="mdi-pencil"
              size="small"
              variant="text"
              :disabled="item.customer_invoice?.is_paid ? true : false"
            >
              <v-badge
                :color="
                  item.customer_invoice?.is_real ? 'success' : 'orange-accent-3'
                "
                floating
                inline
                dot
              >
                <v-icon color="#193a63">mdi-pencil</v-icon>
              </v-badge></v-btn
            >
          </template>
        </v-tooltip>

        <v-tooltip open-delay="500" text="View Invoice" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="
                viewInvoice(
                  item.customer_invoice && item.customer_invoice.file,
                  'customer'
                )
              "
              color="#193a63"
              v-bind="props"
              icon="mdi-file-eye"
              size="small"
              :disabled="!item.customer_invoice"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip open-delay="500" text="Download Invoice" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="downloadInvoice(item.customer_invoice)"
              color="#193a63"
              v-bind="props"
              icon="mdi-file-download"
              size="small"
              variant="text"
              :disabled="!item.customer_invoice"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip open-delay="500" text="Payment" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              :disabled="!item.customer_invoice"
              @click="showPayments(item, 'customer')"
              v-bind="props"
              size="small"
              icon=""
              variant="text"
            >
              <v-badge
                :color="
                  item.customer_invoice?.is_paid ? 'success' : 'orange-accent-3'
                "
                floating
                inline
                dot
              >
                <v-icon color="#193a63">mdi-credit-card</v-icon>
              </v-badge>
            </v-btn>
          </template>
        </v-tooltip>
      </div>
    </template>

    <template v-slot:item.expense="{ item }">
      <div class="d-flex align-center">
        <v-tooltip open-delay="500" text="Add Expense" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="openNewExpense(item)"
              color="#193a63"
              v-bind="props"
              icon="mdi-file-upload"
              size="small"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
        <v-tooltip open-delay="500" text="View Expenses" location="bottom">
          <template v-slot:activator="{ props }">
            <v-btn
              @click="allExpense(item)"
              color="#193a63"
              v-bind="props"
              icon="mdi-eye"
              size="small"
              variant="text"
            ></v-btn>
          </template>
        </v-tooltip>
      </div>
    </template>
  </v-data-table>

  <!-- invoice modal -->
  <v-dialog v-model="dialogInvoice" max-width="560px">
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <span class="text-h6 text-uppercase"
          >{{ invoiceFormTitle }} invoice</span
        >
        <v-btn
          icon="mdi-close"
          variant="text"
          @click="dialogInvoice = false"
        ></v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-container>
        <v-card-text>
          <v-row dense>
            <v-col cols="12" sm="6" md="6">
              <DatePicker
                v-model="invoice.invoicing_date"
                label="Invoice Date"
                variant="default"
                :rules="[$rules.required]"
              />
            </v-col>
            <v-col cols="12" sm="6" md="6">
              <v-text-field
                label="Invoice number"
                v-model="invoice.invoice_number"
                :rules="[$rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col cols="12" sm="6" md="6">
              <v-select
                label="Invoice company"
                v-model="invoice.invoice_company_id"
                item-title="company"
                item-value="id"
                :items="invoiceCompanyStore.collection"
                clearable
                :rules="[$rules.required]"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="6" md="6">
              <DatePicker
                v-model="invoice.payment_deadline"
                label="Payment Deadline"
                variant="default"
                :rules="[$rules.required]"
              />
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                label="Amount"
                :rules="[
                  rules.required,
                  rules.invoice_amount(invoice.amount, proposal_sell_price),
                ]"
                v-model.number="invoice.amount"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-file-input
                label="Invoice file"
                @change="handleFileData"
                counter
                v-model="invoice.file"
              ></v-file-input>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-checkbox
                label="Real Invoice"
                v-model="invoice.is_real"
              ></v-checkbox>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions class="mx-2 my-4">
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="text" @click="dialogInvoice = false">
            Cancel
          </v-btn>
          <v-btn
            color="primary"
            variant="flat"
            :loading="loading"
            @click="uploadInvoice"
          >
            Upload
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>

  <ExpensesDialog
    v-model="dialogExpenses"
    @save="saveExpense"
    :expenses="expenses"
    :order="order"
  />

  <AddExpenseDialog
    :dialog="dialogExpense"
    :expenseStore="expenseTypeStorage"
    :order="order"
    @close="closeExpense"
    @uploaded="expenseUploaded"
  />

  <OrderDialog v-model="dialogOrder" :order="order" />

  <PaymentsDialog
    :dialog="dialogPayments"
    :order="order"
    :type="type"
    @close="closePayments"
    @save="savePayment"
  />

  <CustomerDialog
    :dialog="dialogCustomer"
    :customer="customer"
    @close="closeCustomer"
  />

  <SupplierDialog :supplier="supplier" v-model="dialogSupplier" />
</template>

<script setup>
//vue and pinia
import { ref, defineProps, watch, nextTick, onMounted, computed } from "vue";
import { storeToRefs } from "pinia";
//validation rule
import { rules } from "@/includes/customValidationRules";
//stores
import { useExpensesTypesStore } from "@/stores/expenses-types.js";
import { useInvoiceStore } from "@/stores/invoices.js";

//components
import OrderDialog from "@/components/base/dialogs/OrderDialog.vue";
import AddExpenseDialog from "@/components/base/dialogs/AddExpenseDialog.vue";
import ExpensesDialog from "@/components/base/dialogs/ExpensesDialog.vue";
import PaymentsDialog from "@/components/base/dialogs/PaymentsDialog.vue";
import SupplierDialog from "@/components/accounting/dialogs/SupplierDialog.vue";
import CustomerDialog from "@/components/accounting/dialogs/CustomerDialog.vue";
import DatePicker from "../../base/form-elements/DatePicker.vue";

const props = defineProps({ store: Object, invoiceCompanyStore: Object });
const { collection, errors, res, loading } = storeToRefs(props.store);

const emit = defineEmits([
  "alert",
  "initialize",
  "uploadInvoice",
  "updateInvoice",
]);

const dialogInvoice = defineModel({ type: Boolean });

const invoiceStorage = useInvoiceStore();
const expenseTypeStorage = useExpensesTypesStore();
const proposal_sell_price = ref();
const selected = ref([]);
const search = ref("");

const dialogOrder = ref(false);
const dialogCustomer = ref(false);
const dialogSupplier = ref(false);
const dialogExpense = ref(false);
const dialogExpenses = ref(false);
const dialogPayments = ref(false);
const order = ref({});
const type = ref(""); // to check if customer or supplier invoice

const headers = ref([
  { title: "OMS Order ID", key: "id" },
  { title: "Order Confirmation Date", key: "confirmed_at" },
  { title: "Status", key: "status", align: "center" },
  { title: "Supplier", key: "supplier.name" },
  { title: "Brand", key: "brand.name" },
  { title: "Reference Number", key: "reference_number" },
  { title: "Net Purchasing Price", key: "offer.net_price" },
  {
    title: "Proforma From Supplier",
    key: "actions_supplier",
    align: "center",
    sortable: false,
  },
  {
    title: "Payment Date to Supplier",
    key: "supplier_invoice.payment_deadline",
  },
  { title: "Customer", key: "customer.name" },
  { title: "Sales Price", key: "proposal.sell_price" },
  {
    title: "Proforma From Customer",
    key: "actions_customer",
    align: "center",
    sortable: false,
  },
  {
    title: "Payment Date of the Customer",
    key: "customer_invoice.payment_deadline",
  },
  { title: "Total Expenses", key: "expense" },
  { title: "Profit", key: "proposal.profit" },
  { title: "Supplier Shipment Date", key: "shipment.delivered_at" },
  { title: "Tracking #", key: "shipment.label.tracking_number" },
  { title: "Delivery Date", key: "proposal.delivery_days" },
  { title: "Invoice for Shipment", key: "shipment.label.label_invoice.file" },
  { title: "Order Finalization Date", key: "finalized_at" },
  //{ title: "Actions", key: "action", sortable: false },
]);

const invoice = ref({
  invoicing_date: null,
  invoice_number: "",
  invoice_company_id: null,
  payment_deadline: null,
  amount: 0,
  file: null,
  is_real: false,
});

const defaultItem = ref({
  invoicing_date: null,
  invoice_number: "",
  invoice_company_id: null,
  payment_deadline: null,
  amount: 0,
  is_real: false,
});

const invoiceFormTitle = ref("");

watch(
  () => invoice.value,
  (val) => {
    if (typeof val.file === "string") {
      invoice.value.file = new File([""], invoice.value.file.split("/").pop(), {
        type: "text/plain",
      });
    }
  }
);

const expenses = ref([]);
const customer = ref({});
const supplier = ref({});

onMounted(async () => {
  await expenseTypeStorage.fetchItems();
});

const openOrderInfo = (item) => {
  dialogOrder.value = true;
  order.value = Object.assign({}, item);
};

const newInvoice = (item, types) => {
  invoiceFormTitle.value = "Create";
  order.value = Object.assign({}, item);
  dialogInvoice.value = true;
  if (types === "supplier") {
    proposal_sell_price.value = item.offer.net_price;
  } else {
    proposal_sell_price.value = item.proposal.sell_price;
  }
  type.value = types; //differentiate customer or supplier invoice
};

const openNewExpense = (item) => {
  order.value = Object.assign({}, item);
  dialogExpense.value = true;
};

const allExpense = (item) => {
  let allExpenses = [];

  // Iterate through each expense type in the collection
  expenseTypeStorage.collection.forEach((type) => {
    // Find all expenses of the current type
    let matchingExpenses = item.expenses.filter(
      (expense) => expense.expenses_type_id === type.id
    );

    // If there are matching expenses, add them to the allExpenses array
    if (matchingExpenses.length > 0) {
      matchingExpenses.forEach((expense) => {
        allExpenses.push({
          ...type,
          amount: expense.amount,
          paid_at: expense.paid_at,
          expense_id: expense.id,
        });
      });
    } else {
      // If no matching expenses, add the type with undefined amount, paid_at, and expense_id
      allExpenses.push({
        ...type,
        amount: undefined,
        paid_at: undefined,
        expense_id: undefined,
      });
    }
  });

  // Update the expenses and order
  expenses.value = allExpenses;
  order.value = Object.assign({}, item);

  // Open the dialog
  dialogExpenses.value = true;
};

function showPayments(item, types) {
  order.value = Object.assign({}, item);
  type.value = types;
  dialogPayments.value = true;
}

const editInvoice = async (item, types) => {
  invoiceFormTitle.value = "Edit";
  order.value = Object.assign({}, item);
  let invoiceType = `${types}_invoice`;
  invoice.value = Object.assign({}, item[invoiceType]);
  if (types === "supplier") {
    proposal_sell_price.value = item.offer.net_price;
  } else {
    proposal_sell_price.value = item.proposal.sell_price;
  }
  dialogInvoice.value = true;
  type.value = types;
};

async function downloadInvoice(invoice) {
  try {
    const response = await invoiceStorage.retrieveItem(invoice.id);
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const extension = invoice.file.match(/\.([0-9a-z]+)(?:[\?#]|$)/i)[1];
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "invoice" + invoice.id + "." + extension);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error("Error downloading invoice:", error);
  }
}
function viewInvoice(url) {
  window.open(url, "_blank");
}

const handleFileData = (event) => {
  invoice.value.file = event.target.files[0];
};

function invoiceFormType() {
  const formData = new FormData();
  for (const key in invoice.value) {
    formData.append(key, invoice.value[key]);
  }
  return formData;
}

const exportItems = async () => {
  if (selected.value.length) {
    const ids = selected.value.map((id) => {
      return {
        id,
      };
    });

    await props.store.exportItemsAccOrder(ids);

    const headers = [
      "Order id",
      "Order Confirmation Date",
      "Order Status",
      "Brand",
      "Reference Number",
      "Net Purchasing Prices",
      "Supplier",
      "Suplier Invoice Status",
      "Customer",
      "Customer Invoice Status",
    ];

    const csvContent =
      "data:text/csv;charset=utf-8," +
      encodeURIComponent(headers.join(",") + "\n" + props.store.csvData);

    const downloadLink = document.createElement("a");
    downloadLink.setAttribute("href", csvContent);
    downloadLink.setAttribute("download", props.store.name + ".csv");
    document.body.appendChild(downloadLink);

    downloadLink.click();

    document.body.removeChild(downloadLink);
  } else {
    emit("alert", "Select data(s) to export first.", "error");
  }
};

const uploadInvoice = async () => {
  const data = invoiceFormType();
  if (invoice.value.hasOwnProperty("id")) {
    emit("updateInvoice", type.value, invoice.value.id, data);
  } else {
    emit("uploadInvoice", type.value, order.value.id, data);
  }
};

//AddExpenseDialog component
const expenseUploaded = (message, status) => {
  if (status === "success") {
    emit("initialize");
    emit("alert", message, status);
    closeExpense();
  } else {
    emit("alert", message, status);
  }
};

//ExpensesDialog component
const saveExpense = (message, status) => {
  if (status === "success") {
    emit("initialize");
    emit("alert", message, status);
  } else {
    emit("alert", message, status);
  }
};

// CUSTOMER DIALOG MANIPULATIONS
function showCustomer(item) {
  customer.value = Object.assign({}, item);
  dialogCustomer.value = true;
}

function closeCustomer() {
  dialogCustomer.value = false;
  nextTick(() => {
    errors.value = {};
  });
}

// SUPPLIER DIALOG MANIPULATIONS
function showSupplier(item) {
  supplier.value = Object.assign({}, item);
  dialogSupplier.value = true;
}

//PaymentDialog component
const savePayment = (message, status) => {
  if (status === "success") {
    emit("initialize");
    emit("alert", message, status);
  } else {
    emit("alert", message, status);
  }
};

// Add expense component
function closeExpense() {
  dialogExpense.value = false;
  nextTick(() => {
    order.value = {};
    errors.value = {};
  });
}

function closePayments() {
  dialogPayments.value = false;
  order.value = {};
}

watch(dialogInvoice, (val) => {
  if (!val) {
    invoice.value = Object.assign({}, defaultItem.value);
    errors.value = {};
  }
});
</script>
<style scoped>
.v-card {
  box-shadow: none !important;
}
</style>
