import { ref } from "vue";
import { defineStore } from "pinia";
import { blockIP, getIPs, unblockIP } from "../http/security";

export const useSecurityStore = defineStore("securityStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Security'
    let loading = ref(true)


    const fetchItems = async () => {
        try {
            const { data } = await getIPs()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            errors.value = error.response.data.errors
        }
    }

    const handleBlocked = async (id, ip) => {
        try {
            loading.value = true
            const { data } = await blockIP(id, { ip: ip })
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

    const handleUnblocked = async (id, ip) => {
        try {
            loading.value = true
            const { data } = await unblockIP(id, { ip: ip })
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
        collection, errors, res, loading, name, fetchItems, handleBlocked, handleUnblocked
    }
})