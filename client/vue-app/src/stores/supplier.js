import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove, getAssociated, exportAll, transfer } from "../http/supplier.js";

export const supplierStore = defineStore("supplierStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Supplier'
    const csvData = ref()
    let loading = ref(true)

    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const { data } = await create(newItem);
            res.value = data;
        } catch (error) {
            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data['suppliers']
        loading.value = false
    }

    const updateItemHandler = async (id, item) => {
        try {
            loading.value = true
            const { data } = await update(id, item)
            res.value = data
            loading.value = false
        } catch (error) {
            loading.value = false

            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const deleteItemHandler = async (ids) => {
        loading.value = true
        const { data } = await remove(ids)
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

    const handleTransferedSupplier = async (supplierID, managerID) => {
        try {
            loading.value = true
            const { data } = await transfer(supplierID, { user_id: managerID })
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
        addItemHandler, fetchItems, updateItemHandler, deleteItemHandler, fetchAssociated, exportItemsHandler, handleTransferedSupplier
    }
})