import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useSnackbarStore = defineStore('snackbarStore', () => {

    const show = ref(false)
    const text = ref('')
    const color = ref('')


    const showSnackbar = (message, type) => {
        show.value = true
        text.value = message
        color.value = type
    }

    return { show, text, color, showSnackbar }
})