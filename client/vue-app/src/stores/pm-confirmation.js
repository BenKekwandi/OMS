import { ref } from "vue";
import { defineStore } from "pinia";
import { getAll, update, cancel } from "../http/pm-confirmation.js";

export const pmConfirmationStore = defineStore("pmConfirmationStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Confirmation'
    const loading = ref(false)

    const fetchItems = async (role) => {
        loading.value = true
        const { data } = await getAll(role)
        collection.value = data['data']
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

    const cancelItemHandler = async (id, item) => {
        try {
            loading.value = true
            const { data } = await cancel(id, item)
            res.value = data
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            } else {
                errors.value = "Something went wrong"
            }
        }
    }


    return {
        collection, errors, res, loading, name,
        fetchItems,
        updateItemHandler,
        cancelItemHandler,
    }
})