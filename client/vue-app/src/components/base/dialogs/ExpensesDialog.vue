<template>
  <v-dialog v-model="proxyValue" max-width="600px">
    <v-card>
      <v-container>
        <v-card-title><span class="text-h5"> EXPENSES}</span></v-card-title>
        <v-divider class="mb-4 mx-4"></v-divider>
        <v-card-text>
          <v-table class="table">
            <thead>
              <tr>
                <th class="text-center">Type</th>
                <th class="text-center">Expense Date</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in filteredExpenses" :key="index">
                <td>{{ item.name }}</td>
                <template v-if="item.paid_at">
                  <td>{{ item.paid_at }}</td>
                  <td>{{ item.amount }}</td>
                  <td class="d-flex">
                    <v-btn
                      variant="plain"
                      color="#193a63"
                      size="small"
                      icon="mdi-pencil"
                      @click="editExpense(item)"
                    ></v-btn>
                    <v-btn
                      variant="plain"
                      color="#193a63"
                      size="small"
                      icon="mdi-delete"
                      @click="deleteExpense(item)"
                    ></v-btn>
                  </td>
                </template>
                <template v-else>
                  <td class="text-center" colspan="4">No expenses</td>
                </template>
              </tr>
            </tbody>
          </v-table>

          <v-row class="mt-4">
            <v-col cols="6">
              <v-text-field
                readonly
                variant="underlined"
                v-model="profit"
                label="Profit"
                density="compact"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                readonly
                variant="underlined"
                label="Total"
                v-model="computeTotal"
                density="compact"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mt-1">
            <v-col cols="6">
              <v-checkbox
                class="ml-2"
                @change="filterEntered"
                v-model="allEntered"
                label="All expenses entered"
                color="#00ADB5"
                hide-details
              />
            </v-col>
            <v-col cols="6">
              <v-checkbox
                class="ml-2"
                v-model="finalizeOrder"
                label="Finalize Order"
                color="#00ADB5"
                hide-details
              />
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="mx-2">
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="outlined" @click="close">Close</v-btn>
          <v-btn color="orange" variant="outlined" @click="save">Save</v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>

  <v-dialog v-model="dialogEditExpense" max-width="600px">
    <v-card>
      <v-card-title>Edit expense - {{ expense.name }}</v-card-title>

      <v-card-text>
        <v-row>
          <v-col>
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-text-field
                  readonly
                  label="Expense Date"
                  v-model="expense.paid_at"
                  v-bind="props"
                ></v-text-field>
              </template>
              <v-date-picker
                hide-header
                max="2030-12-31"
                v-model="expenseDate"
                min="2023-01-01"
              ></v-date-picker>
            </v-menu>
          </v-col>
          <v-col>
            <v-text-field
              label="Amount"
              v-model="expense.amount"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions class="mx-2 my-4">
        <v-spacer></v-spacer>
        <v-btn @click="closeEditExpense" color="primary">Cancel</v-btn>
        <v-btn color="primary" :loading="loading" @click="updateExpense"
          >Update</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog v-model="dialogDeleteExpense" max-width="400px">
    <v-card class="pa-3">
      <v-card-title class="text-center"
        >Are you sure you want to <br />delete selected expense?</v-card-title
      >
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" variant="text" @click="closeDeleteExpense">
          Cancel
        </v-btn>
        <v-btn
          color="primary"
          :loading="loading"
          variant="text"
          @click="deleteExpenseConfirm"
        >
          Delete
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { defineProps, toRefs, ref, watch, nextTick, computed } from "vue";
import format from "date-fns/format";
import { storeToRefs } from "pinia";
import { useOrderExpenseStore } from "@/stores/order-expense";
import { orderStore } from "@/stores/order";

const props = defineProps({ expenses: Object, order: Object });
const { expenses } = toRefs(props);

const orderExpenseStore = useOrderExpenseStore();
const order_store = orderStore();

const { errors, res, loading } = storeToRefs(orderExpenseStore);

const { res: orderStoreRes, errors: orderStoreErrors } =
  storeToRefs(order_store);

const proxyValue = defineModel();
const emit = defineEmits(["save", "close"]);

const expense = ref({
  paid_at: "",
  amount: "",
});
const expenseDate = ref(null);
const finalizeOrder = ref(false);
const allEntered = ref(false);

const editIndex = ref();
const dialogEditExpense = ref(false);
const dialogDeleteExpense = ref(false);
const filteredExpenses = ref({});

const filterEntered = () => {
  if (allEntered.value)
    filteredExpenses.value = Object.values(expenses.value).filter(
      (expense) => expense.paid_at
    );
  else filteredExpenses.value = Object.assign({}, expenses.value);
};

watch(
  () => expenses.value,
  (val) => {
    filteredExpenses.value = Object.assign({}, val);
  }
);

const editExpense = async (item) => {
  expense.value = Object.assign({}, item);
  editIndex.value = expenses.value.indexOf(item);
  expenseDate.value = new Date(item.paid_at);
  dialogEditExpense.value = true;
};

const deleteExpense = (item) => {
  editIndex.value = expenses.value.indexOf(item);
  expense.value = Object.assign({}, item);
  dialogDeleteExpense.value = true;
};

const updateExpense = async () => {
  await orderExpenseStore.updateItemHandler(expense.value.expense_id, {
    paid_at: expense.value.paid_at,
    amount: expense.value.amount,
  });
  if (res.value) {
    emit("save", res.value.message, "success");
    Object.assign(expenses.value[editIndex.value], res.value.data);
    closeEditExpense();
  } else {
    emit("save", errors.value, "error");
  }
  res.value = null;
};

const deleteExpenseConfirm = async () => {
  await orderExpenseStore.deleteItemHandler(expense.value.expense_id);
  if (res.value) {
    emit("save", res.value.message, "success");
    expenses.value[editIndex.value].paid_at = "";
    closeDeleteExpense();
  } else {
    emit("save", errors.value, "error");
  }
  res.value = null;
};

const save = async () => {
  if (finalizeOrder.value) {
    await order_store.handlesetFinalizeOrder(props.order.id);
    if (orderStoreRes.value) {
      emit("initialize");
      emit("alert", orderStoreRes.value.message, orderStoreRes.value.status);
      close();
    } else {
      emit("alert", orderStoreErrors.value, "error");
    }
    orderStoreRes.value = null;
  }
  proxyValue.value = false;
  allEntered.value = false;
};

function close() {
  proxyValue.value = false;
  allEntered.value = false;
}

function closeDeleteExpense() {
  dialogDeleteExpense.value = false;
  nextTick(() => {
    expense.value = {};
    errors.value = {};
  });
}

function closeEditExpense() {
  dialogEditExpense.value = false;
  nextTick(() => {
    expense.value = {};
    errors.value = {};
  });
}

const profit = computed(() => {
  return props.order.profit - computeTotal.value;
});

const computeTotal = computed(() => {
  return expenses.value.reduce(
    (acc, curr) => acc + (curr.amount ? parseFloat(curr.amount) : 0),
    0
  );
});

watch(
  () => expenseDate.value,
  (val) => {
    expense.value.paid_at = format(val, "do MMM yyyy");
  }
);

watch(proxyValue, (val) => {
  val || close();
});
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
</style>
