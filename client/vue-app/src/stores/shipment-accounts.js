import { ref } from "vue";
import { defineStore } from "pinia";
import { getAccounts, createAccount, deleteAccounts, updateAccount } from "../http/shipment-account";

export const useShipmentAccountStore = defineStore("shipmentAccountStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const loading = ref(false)

    const fetchAccounts = async (id) => {
        try {
            loading.value = true
            const { data } = await getAccounts(id)
            collection.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }


    const handleAddedAccount = async (newItem) => {
        try {
            loading.value = true
            const { data } = await createAccount(newItem)
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

    const handleDeletedAccounts = async (ids) => {
        try {
            loading.value = true
            const { data } = await deleteAccounts(ids)
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


    const handleUpdatedAccount = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateAccount(id, item)
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

    // const handleExport = async (userRole) => {
    //     const { ...data } = await exportUsers({role: userRole})
    //     csvData.value = data.data
    // }

    return {
        collection, errors, res, loading, csvData, fetchAccounts, handleAddedAccount, handleDeletedAccounts, handleUpdatedAccount
    }
})
