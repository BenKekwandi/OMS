import { ref } from "vue";
import { defineStore } from "pinia";
import { getUsers, createUser, deactivateUsers, updateUser, exportUsers } from "../http/users";

export const useUserStore = defineStore("userStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    const csvData = ref()
    const name = 'users'

    let loading = ref(true)

    const fetchUsers = async () => {
        try {
            const { data } = await getUsers()
            collection.value = data.data
            loading.value = false
        } catch (error) {
            errors.value = error.response.data.errors
        }
    }


    const handleAddedUser = async (newItem) => {
        try {
            loading.value = true
            const { data } = await createUser(newItem)
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

    const handleDeactivatedUsers = async (ids) => {
        try {
            loading.value = true
            const { data } = await deactivateUsers(ids)
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


    const handleUpdatedUser = async (id, item) => {
        try {
            loading.value = true
            const { data } = await updateUser(id, item)
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

    const handleExport = async (userRole) => {
        const { ...data } = await exportUsers({role: userRole})
        csvData.value = data.data
    }

    return {
        collection, errors, res, loading, csvData, name, fetchUsers, handleAddedUser, handleDeactivatedUsers, handleUpdatedUser, handleExport
    }
})
