import { ref } from "vue";
import { defineStore } from "pinia";
import { getServices, createService, deleteServices, updateService } from "../http/shipment-services";

export const useShipmentServiceStore = defineStore("shipmentServiceStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const loading = ref(false)

    const fetchServices = async () => {
        try {
            loading.value = true
            const { data } = await getServices()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }


    const handleAddedService = async (newItem) => {
        try {
            loading.value = true
            const { data } = await createService(newItem)
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

    const handleDeletedServices = async (ids) => {
        try {
            loading.value = true
            const { data } = await deleteServices(ids)
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


    const handleUpdatedService = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateService(id, item)
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
        collection, errors, res, loading, csvData, fetchServices, handleAddedService, handleDeletedServices, handleUpdatedService
    }
})
