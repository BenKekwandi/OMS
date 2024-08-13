import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove, getAssociated, exportAll, transfer } from "../http/customer.js";

export const customerStore = defineStore("customerStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Customer'
    const csvData = ref()
    let loading = ref(true)

    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const { data } = await create(newItem);
            res.value = data;
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            }
        }
    }

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data['customers']
        loading.value = false
    }

    const updateItemHandler = async (id, item) => {
        try {
            loading.value = true
            const { data } = await update(id, item);
            res.value = data;
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            }
        }
    }

    const deleteItemHandler = async (ids) => {
        loading.value = true
        const { data } = await remove(ids);
        res.value = data;
        loading.value = false
    }

    const fetchAssociated = async (id) => {
        loading.value = true
        const { data } = await getAssociated(id)
        collection.value = data.record
        loading.value = false
    }

    const exportItemsHandler = async () => {
        const { ...data } = await exportAll();
        csvData.value = data.data
    }

    const transferCustomer = async (customerID, managerID) => {
        try {
            loading.value = true
            const { data } = await transfer(customerID, { user_id: managerID })
            res.value = data
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    return {
        collection, errors, res, loading, name, csvData,
        addItemHandler, fetchItems, updateItemHandler, deleteItemHandler, fetchAssociated, exportItemsHandler, transferCustomer
    }
})