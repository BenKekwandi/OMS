import { ref } from "vue";
import { defineStore } from "pinia";
import { getLabels, createLabel, deleteLabels, updateLabel, createLabelInvoice, setCollected, setDelivered, stepBack } from "../http/label";

export const useLabelStore = defineStore("labelStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const loading = ref(false)
    const loading_collected_at = ref(false)
    const loading_delivered_at = ref(false)
    const loading_step_back = ref(false)

    const fetchLabels = async () => {
        try {
            loading.value = true
            const { data } = await getLabels()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            loading.value = false
            errors.value = error.response.data.errors
        }
    }


    const handleAddedLabel = async (isInvoiceAttached, invoice, newItem) => {
        try {
            loading.value = true
            const { data } = await createLabel(newItem)
            if (isInvoiceAttached) {
                await handleAddedLabelInvoice({ ...invoice, label_id: data.data.id })
            }
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

    const handleDeletedLabels = async (id) => {
        try {
            loading.value = true
            const { data } = await deleteLabels(id)
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


    const handleUpdatedLabel = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateLabel(id, item)
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

    const handleStepBack = async (id) => {
        try {
            loading_step_back.value = true
            const { data } = await stepBack(id)
            res.value = data
            loading_step_back.value = false
        } catch (error) {
            loadiloading_step_backng.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }
    const handleSetCollectedAt = async (id, item) => {
        try {
            loading_collected_at.value = true
            const { data } = await setCollected(id, item)
            res.value = data
            loading_collected_at.value = false
        } catch (error) {
            loading_collected_at.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    const handleSetDeliveredAt = async (id, item) => {
        try {
            loading_delivered_at.value = true
            const { data } = await setDelivered(id, item)
            res.value = data
            loading_delivered_at.value = false
        } catch (error) {
            loading_delivered_at.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    const handleAddedLabelInvoice = async (invoice) => {
        try {

            loading.value = true
            await createLabelInvoice(invoice)
            loading.value = false
        }

        catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    return {
        collection, errors, res, loading, loading_collected_at, loading_delivered_at, loading_step_back, csvData,
         fetchLabels, handleAddedLabel, handleDeletedLabels, handleUpdatedLabel, handleStepBack, handleSetCollectedAt, handleSetDeliveredAt, handleAddedLabelInvoice
    }
})
