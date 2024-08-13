import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove, exportAll, reset, filter, importOffer } from "../http/offer.js";

export const offerStore = defineStore("offerStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Offer'
    const csvData = ref()
    const loading = ref(true)


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

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data['offers']
        loading.value = false

    }

    const updateItemHandler = async (id, item) => {
        try {
            loading.value = true;
            const { data } = await update(id, item);
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

    const uploadFileHandler = async (file) => {
        try {
            loading.value = true;
            const { data } = await importOffer(file);
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
        loading.value = true
        const { data } = await remove(ids)
        res.value = data;
        loading.value = false
    }

    const handleExport = async () => {
        const { ...data } = await exportAll()
        csvData.value = data.data
    }

    const resetHandler = async (ids) => {
        loading.value = true
        const { data } = await reset(ids)
        res.value = data;
        loading.value = false
    }

    const filterHandler = async (datas) => {
        loading.value = true
        const { data } = await filter(datas)
        collection.value = data
        loading.value = false
    }

    return {
        collection, errors, res, loading, name, csvData, resetHandler, filterHandler,
        addItemHandler, fetchItems, updateItemHandler, deleteItemHandler, handleExport, uploadFileHandler
    }
})