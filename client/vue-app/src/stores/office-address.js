import { ref } from "vue";
import { defineStore } from "pinia";
import { getAddresses, createAddress, deleteAddresses, updateAddress } from "../http/office-address";

export const useOfficeAddressStore = defineStore("OfficeAddressStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const loading = ref(false)

    const fetchOfficeAddresses = async () => {
        try {
            loading.value = true
            const { data } = await getAddresses()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }


    const handleAddedOfficeAddress = async (newItem) => {
        try {
            loading.value = true
            const { data } = await createAddress(newItem)
            await fetchOfficeAddresses()
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

    const handleDeletedOfficeAddresses = async (ids) => {
        try {
            loading.value = true
            const { data } = await deleteAddresses(ids)
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


    const handleUpdatedOfficeAddress = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateAddress(id, item)
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
        collection, errors, res, loading, csvData, fetchOfficeAddresses, handleAddedOfficeAddress, handleDeletedOfficeAddresses, handleUpdatedOfficeAddress
    }
})
