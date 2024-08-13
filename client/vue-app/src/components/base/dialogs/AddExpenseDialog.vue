<template>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-container>
                <v-card-title>
                    <span class="text-h5">ADD EXPENSE</span>
                </v-card-title>
                <v-divider class="mb-4 mx-4"></v-divider>
                <v-card-text>
                    
                    <v-row>
                        <v-col>
                            <DatePicker v-model="expense.date" label="Date" variant="default"/>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field  label="Amount" v-model.number="expense.amount"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select  label="Expenses" v-model="expense.id" item-title="name" item-value="id"
                                :items="expenseStore.collection"></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions class="mx-2 my-4">
                    <v-spacer></v-spacer>
                    <v-btn color="primary" variant="text" @click="close">
                        Cancel
                    </v-btn>
                    <v-btn color="primary" variant="text" :loading="loading" @click="uploadExpense">
                        Add
                    </v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { defineProps, toRefs, ref } from "vue";
import { useOrderExpenseStore } from "@/stores/order-expense";
import DatePicker from "../form-elements/DatePicker.vue";

// Define props for the component
const props = defineProps({ dialog: Boolean, expenseStore: Object, order: Object });
const { dialog, order } = toRefs(props)
// Access the order-expense store using Pinia
const orderExpenseStore = useOrderExpenseStore()

// Destructure the reactive properties from the orderExpenseStore
const { collection, errors, res, loading } = storeToRefs(orderExpenseStore);

// Define emit function for emitting events
const emit = defineEmits(['close', 'uploaded'])

// Define reactive variables for the component
const expenseDate = ref(null);

const expense = ref({
    date: null,
    amount: "",
    id: "",
});

// Define a default item for resetting the expense object
const defaultItem = ref({
    date: null,
    amount: "",
    id: "",
});

// Function to upload expense data
const uploadExpense = async () => {
    const data = {
        paid_at: expense.value.date,
        amount: expense.value.amount,
        expenses_type_id: expense.value.id,
        order_id: order.value.id,
    };
    await orderExpenseStore.addItemHandler(data);
    if (res.value) {
        emit("uploaded", res.value.message, 'success')
        expense.value = Object.assign({}, defaultItem.value)
    } else {
        emit("uploaded", errors.value, "error");
    }
    res.value = null
}


// Function to close the dialog
const close = () => {
    emit('close')
    expense.value = Object.assign({}, defaultItem)
}


</script>