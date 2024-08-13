<template>
  <v-dialog v-model="dialog" max-width="750px">
    <v-card>

      <v-card-title class="d-flex justify-space-between align-center  mb-0 pb-0">
        <span class="text-h6">INVOICE FOR ORDER <span class="text-primary font-weight-bold">#{{ order.id }}</span>
        </span>
        <v-btn icon="mdi-close" variant="text" @click="close"></v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-container>
        <v-card-text>
          <div class="d-flex justify-space-between align-center">
            <span class="text-h6">Invoice Info</span>

          </div>
          <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100"></v-divider>
          <v-row class="mt-6">
            <v-col class="py-0">
              <v-text-field label="Created At" v-model="invoiceItem.created_at" variant="underlined"
                readonly></v-text-field>
            </v-col>
            <v-col class="py-0">
              <v-text-field label="Invoicing Date" v-model="invoiceItem.invoicing_date" variant="underlined"
                readonly></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col class="py-0">
              <v-text-field v-if="type === 'customer'" label="Sell Price" v-model="invoiceItem.sell_price"
                variant="underlined" readonly></v-text-field>

              <v-text-field v-if="type === 'supplier'" label="Net Price" v-model="invoiceItem.net_price"
                variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col class="py-0">
              <v-text-field label="Paid" v-model="paidAmount" variant="underlined" readonly></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col class="py-0">
              <v-text-field label="Left to pay" v-model="leftToPay" variant="underlined" readonly></v-text-field>
            </v-col>
            <v-col class="py-0">
              <v-text-field label="File uploaded at" v-model="invoiceItem.created_at" variant="underlined"
                readonly></v-text-field>
            </v-col>
          </v-row>


          <div class="d-flex justify-space-between align-center">
            <span class="text-h6">Payments</span>
            <v-btn icon="mdi-plus" variant="text" color="green" @click="addPayment"></v-btn>
          </div>
          <v-divider :thickness="2" color="#00ADB5" class="border-opacity-100">
          </v-divider>
          <v-row class="mt-2">
            <v-col cols="12">
              <v-table class="table">
                <thead>
                  <tr>
                    <th class="text-center column">Amount</th>
                    <th class="text-center column">Paid at</th>
                    <th class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in payments" :key="item.id">
                    <td class="text-center column">{{ item.amount }}</td>
                    <td class="text-center column">{{ item.paid_at }}</td>
                    <td class="text-center">
                      <v-btn :disabled="orderDetails.status === 'Ready for Shipment'" icon="mdi-trash-can-outline"
                        color="#193a63" @click="showDeletePayment(item)" variant="plain" class="mx-2"></v-btn>
                    </td>
                  </tr>
                  <tr v-for="(item, index) in newPayments" :key="index">
                    <td>
                      <v-text-field min="0" variant="underlined" v-model.number="item.amount"
                        :rules="[rules.left_to_pay(leftToPay, newPayments)]"></v-text-field>
                    </td>
                    <td>
                      <DatePicker v-model="item.paid_at" />
                    </td>
                    <td></td>
                  </tr>
                </tbody>
              </v-table>
            </v-col>
          </v-row>


        </v-card-text>
        <v-card-actions class="mx-2 my-4">
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-btn class="px-4" color="primary" variant="outlined" @click="close">Cancel</v-btn>
          <v-btn class="px-4" color="primary" variant="elevated" flat :loading="loading"
            @click="createPayment">Save</v-btn>
        </v-card-actions>
      </v-container>

    </v-card>

  </v-dialog>

  <v-dialog v-model="dialogDeletePayment" max-width="400px">
    <v-card class="pa-3">
      <v-card-title class="text-center">Are you sure you want to <br />delete selected payment?</v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" variant="text" @click="closeDeletePayment">
          Cancel
        </v-btn>
        <v-btn color="primary" :loading="loading" variant="text" @click="deletePayment">
          Delete
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { defineProps, toRefs, ref, watch, computed, nextTick } from "vue";
import { paymentStore } from "@/stores/payment";
import { rules } from "@/includes/customValidationRules";
import DatePicker from "../form-elements/DatePicker.vue";


const props = defineProps({ order: Object, dialog: Boolean, type: String });
const { order, dialog } = toRefs(props);

const paymentStorage = paymentStore();
const { res, loading, errors } = storeToRefs(paymentStorage);

const emit = defineEmits(["close", "save"]);

const dialogDeletePayment = ref(false);


const newPayments = ref([]);
const payments = ref([]);
const payment = ref({});
const orderDetails = ref({});

const defaultPayment = ref({
  amount: "",
  paid_at: null,
});


const invoiceItem = ref({
  created_at: "",
  invoicing_date: "",
  net_price: "",
  sell_price: "",
  paid: "",
  left_to_pay: "",
  file_uploaded_at: "",
});

const defaultInvoiceItem = ref({
  created_at: "",
  invoicing_date: "",
  net_price: "",
  sell_price: "",
  paid: "",
  left_to_pay: "",
  file_uploaded_at: "",
});

function showDeletePayment(item) {
  dialogDeletePayment.value = true;
  payment.value = item;
}


function addPayment() {
  if (invoiceItem.value.amount !== paidAmount.value) {
    if (props.type === 'supplier') {
      newPayments.value.push({
        amount: "",
        paid_at: null,
        paymentDate: null,
        invoice_id: order.value.supplier_invoice.id,
      });
    }
    else if (props.type === 'customer') {
      newPayments.value.push({
        amount: "",
        paid_at: null,
        paymentDate: null,
        invoice_id: order.value.customer_invoice.id,
      });
    }
  }
}

async function createPayment() {
  if (newPayments.value.length) {
    await paymentStorage.addItemHandler(newPayments.value);
    if (res.value) {
      emit("save", res.value.message, "success");
      close();
    } else {
      emit("save", errors.value, "error");
    }
    res.value = null;
  }
}

const close = () => {
  emit("close");
  nextTick(() => {
    newPayments.value.length = 0;
  });
};

async function deletePayment() {
  await paymentStorage.deleteItemHandler(payment.value.id);
  if (res.value) {
    emit("save", res.value.message, "success");
    closeDeletePayment();
    close();
  } else {
    emit("save", errors.value, "error");
  }
  res.value = null;
}

function closeDeletePayment() {
  dialogDeletePayment.value = false;
  nextTick(() => {
    payment.value = defaultPayment.value;
  });
}

const paidAmount = computed(() => {
  return payments.value.reduce((acc, curr) => acc + parseFloat(curr.amount), 0);
});

const leftToPay = computed(() => {
  if (props.type === 'customer')
    return invoiceItem.value.sell_price - paidAmount.value;
  if (props.type === 'supplier')
    return invoiceItem.value.net_price - paidAmount.value;
});

watch(
  () => order.value,
  (val) => {
    if (Object.keys(val).length) {
      invoiceItem.value.created_at = val.created_at;
      if (props.type === 'supplier' && val.supplier_invoice != null) {
        invoiceItem.value.invoicing_date = val.supplier_invoice.invoicing_date;
        invoiceItem.value.net_price = val.offer.net_price;
        payments.value = Object.values(val.supplier_invoice.payments);

      }
      else if (props.type === 'customer' && val.customer_invoice != null) {
        invoiceItem.value.invoicing_date = val.customer_invoice.invoicing_date;
        invoiceItem.value.sell_price = val.proposal.sell_price;
        payments.value = Object.values(val.customer_invoice.payments);

      }

      orderDetails.value = Object.assign({}, val);

    } else {
      invoiceItem.value = defaultInvoiceItem.value;
    }
  }
);
</script>

<style scoped>
.table {
  border: 1px solid #e0e0e0;
}

.table th,
.table td {
  border-right: 1px solid #e0e0e0;
  border-bottom: 1px solid #e0e0e0;
}

.column {
  width: 500px;
}
</style>
