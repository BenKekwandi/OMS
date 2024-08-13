import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { getAll, reactivate } from '@/http/deactive-users'

export const deactiveUserStore = defineStore("deactiveUserStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Brand'
    let loading = ref(true)

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data.users
        loading.value = false
    }

    const handleReactivatedUsers = async (ids) => {
        try {
            loading.value = true
            const { data } = await reactivate(ids)
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


    return { collection, errors, res, loading, fetchItems, handleReactivatedUsers }
})