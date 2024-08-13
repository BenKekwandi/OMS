import { ref } from "vue";
import { defineStore } from "pinia";
import { getAll } from "../http/matching-offers.js";

export const matchesStore = defineStore("matchesStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const name = 'Matching Offers'
    const brand = ref("")
    let loading = ref(true)

    const fetchItems = async (itemId) => {
        const { data } = await getAll(itemId)
        collection.value = data['matching_offers']
        brand.value = data['brand']
        loading.value = false
    }

    return {
        collection, errors, res, loading, name, brand, fetchItems
    }
})