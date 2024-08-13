import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove, exportAll, reset, filter, getAccOrders, getLogOrders, exportAccOrders, importOrder, setFinalizeOrder } from "../http/order.js";

export const orderStore = defineStore("orderStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const name = 'Order'
    const loading = ref(false)



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
        loading.value = true
        const { data } = await getAll()
        collection.value = data['orders']
        loading.value = false
    }

    const updateItemHandler = async (id, item) => {
        try {
            loading.value = true;
            const { data } = await update(id, item);
            res.value = data;
            loading.value = false;
        } catch (error) {

            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const uploadFileHandler = async (file) => {
        try {
            loading.value = true;
            const { data } = await importOrder(file);
            res.value = data;
            loading.value = false;
        } catch (error) {
            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const deleteItemHandler = async (ids) => {
        loading.value = true;
        const { data } = await remove(ids);
        res.value = data;
        console.log(ids);
        loading.value = false;
    }

    const handleExport = async () => {
        const { ...data } = await exportAll()
        csvData.value = data.data
    }

    const exportItemsAccOrder = async (ids) => {
        const { ...data } = await exportAccOrders(ids)
        csvData.value = data.data
    }

    const resetHandler = async (resetInfo) => {
        loading.value = true
        const { data } = await reset(resetInfo)
        res.value = data;
        loading.value = false
    }

    const filterHandler = async (datas) => {

        loading.value = true
        const { data } = await filter(datas)
        collection.value = data
        loading.value = false
    }

    const accountingOrdersHandler = async () => {
        loading.value = true
        const { data } = await getAccOrders()
        collection.value = data.orders

        loading.value = false
    }

    const logisticsOrdersHandler = async () => {
        loading.value = true
        const { data } = await getLogOrders()
        collection.value = data.orders
        loading.value = false
    }

    const handlesetFinalizeOrder = async (id) => {
        try {
            loading.value = true
            const { data } = await setFinalizeOrder(id)
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
        filterHandler,
        addItemHandler, fetchItems, updateItemHandler,
        deleteItemHandler, handleExport,
        resetHandler, accountingOrdersHandler, logisticsOrdersHandler, exportItemsAccOrder, uploadFileHandler, handlesetFinalizeOrder
    }
})