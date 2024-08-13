import { ref } from "vue";
import { defineStore } from "pinia";
import {
    getShipments, getShipment, createShipment, deleteShipments, updateShipment,
    linkOrderToShipment, deleteOrderToShipment,
    getOrdersOfShipment, getAvailableOrders
} from "../http/shipment";

export const useShipmentStore = defineStore("shipmentStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const loading = ref(false)
    const orders = ref([])
    const availableOrders = ref([])
    const shipment = ref({})

    const fetchShipments = async () => {
        try {
            loading.value = true
            const { data } = await getShipments()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }

    const fetchShipment = async (id) => {
        try {
            loading.value = true
            const { data } = await getShipment(id)
            shipment.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }


    const handleAddedShipment = async (newItem, orderIds) => {
        try {
            loading.value = true
            const { data } = await createShipment(newItem)

            const shipment_id = data.data.id;
            const value = orderIds.map(id => ({
                ...id,
                shipment_id
            }));
            await handleLinkOrdertToShipment(value)
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

    const handleDeletedShipments = async (ids) => {
        try {
            loading.value = true
            const { data } = await deleteShipments(ids)
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


    const handleUpdatedShipment = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateShipment(id, item)
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

    const handleLinkOrdertToShipment = async (ids) => {

        try {
            loading.value = true
            const { data } = await linkOrderToShipment(ids)
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

    const handleDeleteOrdertToShipment = async (ids) => {

        try {
            loading.value = true
            const { data } = await deleteOrderToShipment(ids)
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

    const fetchOrders = async (id) => {

        try {
            loading.value = true
            const { data } = await getOrdersOfShipment(id)
            orders.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }

    const fetchAvailableOrders = async () => {

        try {
            loading.value = true
            const { data } = await getAvailableOrders()
            availableOrders.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }





    // const handleExport = async (userRole) => {
    //     const { ...data } = await exportUsers({role: userRole})
    //     csvData.value = data.data
    // }

    return {
        collection, errors, res, loading, csvData, orders, availableOrders, shipment, fetchShipments, fetchShipment, handleAddedShipment, handleDeletedShipments,
        handleUpdatedShipment, handleLinkOrdertToShipment, handleDeleteOrdertToShipment, fetchOrders,
        fetchAvailableOrders
    }
})
