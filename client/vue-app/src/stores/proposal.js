import { ref } from "vue";
import { defineStore } from "pinia";
import { confirm, create, getAll, cancel, update } from "../http/proposal.js";

export const proposalStore = defineStore("proposalStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Proposal'
    const loading = ref(false)

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data.data
        loading.value = false
    }

    const addItemHandler = async (newItem) => {
        try {
            loading.value = true
            const { data } = await create(newItem)
            res.value = data;
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

    const confirmItemHandler = async (id, supplier) => {
        try {
            loading.value = true
            const { data } = await confirm(id, { supplier_id: supplier })
            res.value = data;
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

    const cancelItemHandler = async (id, note) => {
        try {
            loading.value = true
            const { data } = await cancel(id, { notes: note })
            res.value = data;
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

    const updateOrderOffer = async (id, item) => {
        try {
            loading.value = true
            const { data } = await update(id, item)
            res.value = data;
            loading.value = false
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } 
        }
    }


    return {
        collection, errors, res, loading, name, fetchItems, confirmItemHandler, cancelItemHandler,
        addItemHandler, updateOrderOffer
    }
})