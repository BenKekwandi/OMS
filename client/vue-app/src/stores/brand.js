import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove } from "../http/brand.js";

export const brandStore = defineStore("brandStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Brand'
    let loading = ref(true)

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data.brands
        loading.value = false
    }

    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const {data} = await create(newItem)
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


    const updateItemHandler = async (id, item) => {
        try {
            loading.value = true
            const { data } = await update(id, item)
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

    const deleteItemHandler = async (ids) => {
        try {
            loading.value = true
            const { data } = await remove(ids)
            res.value = data
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            }
            else {
                errors.value = "Something went wrong"
            }
        }

    }

    return {
        collection, errors, res, loading, name,
        addItemHandler,
        fetchItems,
        updateItemHandler, deleteItemHandler
    }
})
