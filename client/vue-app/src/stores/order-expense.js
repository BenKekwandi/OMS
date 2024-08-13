import { ref } from 'vue'
import { defineStore } from 'pinia'
import { getAll, create, remove, update } from '@/http/order-expense'

export const useOrderExpenseStore = defineStore("orderExpenseStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    let loading = ref(false)

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data.expenses
        loading.value = false
    }

    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const { data } = await create(newItem)
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


    const deleteItemHandler = async (id) => {
        try {
            loading.value = true
            const { data } = await remove(id)
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


    return { collection, errors, res, loading, fetchItems, addItemHandler, deleteItemHandler, updateItemHandler }
})