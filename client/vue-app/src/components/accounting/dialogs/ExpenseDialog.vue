<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-container>
                <v-card-title>
                    <span class="text-h5">Add Expense</span>
                </v-card-title>
                <v-divider class="my-4 mx-4"></v-divider>
                <v-card-text>
                    <v-row> </v-row>
                    <v-row>
                        <v-col>
                            <v-menu>
                                <template v-slot:activator="{ props }">
                                    <v-text-field v-model="Expense.paid_at"
                                                  :rules="[rules.required]"
                                                  readonly label="Date"
                                                  v-bind="props"></v-text-field>
                                </template>
                                <v-date-picker hide-header v-model="expenseDate" max="2030-12-31"
                                    min="2023-01-01"></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field label="Amount" 
                                          @update:modelValue="console.log(Expense)"
                                          :rules="[rules.required]"
                                          v-model.number="Expense.amount"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select label="Expenses" v-model.number="Expense.expenses_type_id" item-title="name"
                                item-value="id" :items="expenseStore.collection"></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions class="mx-2 my-4">
                    <v-spacer></v-spacer>
                    <v-btn class="px-4" color="blue-darken-1" variant="outlined" @click="close">
                        Cancel
                    </v-btn>
                    <v-btn class="px-4" color="blue-darken-1" variant="elevated" :loading="expenseStore.loading"
                        @click="upload">
                        Add
                    </v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { defineProps, toRefs, ref, watch } from "vue";
import { rules } from "@/includes/customValidationRules";
import format from "date-fns/format";


const props = defineProps({ dialog: Boolean, expenseStore: Object, givenInvoice: Object });
const { dialog, expenseStore, givenInvoice } = toRefs(props)

const emit = defineEmits(['close', 'upload'])

const expenseDate = ref(null);

const Expense = ref({
    amount: "",
    paid_at: null,
    expenses_type_id: null,
});

const defaultExpenseItem = ref({
    amount: "",
    paid_at: null,
    invoice_id: "",
});

watch(
    () => expenseDate.value,
    (val) => {
        Expense.value.paid_at = format(val, "do MMM yyyy");
    }
);


watch(() => givenInvoice.value,
      (val) => {
        Expense.value.order_id = val.order_id;
        Expense.value.invoice_id = val.id;
      }      
);

const close = () => {
    emit('close')
    Expense.value = Object.assign({}, defaultExpenseItem.value);
}

const upload = () => {
    emit('upload', Expense.value)
    Expense.value = Object.assign({}, defaultExpenseItem.value);
}

</script>