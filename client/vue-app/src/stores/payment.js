import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getById, update, remove } from "@/http/payment.js";

export const paymentStore = defineStore("paymentStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Payment'
    const loading = ref(false)


    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const { data } = await create(newItem);
            res.value = data;
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const getItemById = async (id) => {
        loading.value = true
        const { data } = await getById(id)
        res.value = data['payment']
        loading.value = false
    }

    const updateItemHandler = async (id, payment) => {
        try {
            loading.value = true;
            const { data } = await update(id, payment);
            res.value = data;
            console.log(item);
            loading.value = false;
        } catch (error) {
            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const deleteItemHandler = async (id) => {
        loading.value = true
        const { data } = await remove(id)
        res.value = data;
        loading.value = false
    }

    return {
        collection, errors, res, loading, name,
        addItemHandler, getItemById, updateItemHandler, deleteItemHandler 
    }
});