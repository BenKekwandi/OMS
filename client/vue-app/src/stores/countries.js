import { ref } from "vue";
import { defineStore } from "pinia";
import { create, getAll, update, remove, getAssociated } from "../http/countries.js";

export const countryStore = defineStore("countryStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Country'
    let loading = ref(true)
    const countryName = ref('')

    const fetchItems = async () => {
        const { data } = await getAll()
        collection.value = data['countries']
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

    const deleteItemHandler = async (ids) => {
        try {
            loading.value = true
            const { data } = await remove(ids)
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

    const fetchAssociated = async (id) => {
        const { data } = await getAssociated(id)
        countryName.value = data.country.name
        loading.value = false
    }


    return {
        collection, errors, res, loading, name, countryName,
        addItemHandler,
        fetchItems,
        updateItemHandler, deleteItemHandler, fetchAssociated
    }
})
