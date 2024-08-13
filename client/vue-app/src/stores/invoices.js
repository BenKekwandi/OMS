import { ref } from "vue";
import { defineStore } from "pinia";
import { getItemWithId, uplaodInvoiceSupplier, uplaodInvoiceCustomer, updateInvoice } from "../http/invoices.js";

export const useInvoiceStore = defineStore("invoiceStore", () => {
    const collection = ref([])
    const errors = ref({})
    const res = ref(null)
    let loading = ref(false)

    const retrieveItem = async (itemId) => {
        loading.value = true
        const response = await getItemWithId(itemId);
        res.value = response;
        loading.value = false;
        return response;
    }

    const updateInvoiceHandler = async (id, item) => {
        try {
            loading.value = true;
            const { data } = await updateInvoice(id, item);
            res.value = data;
            loading.value = false;
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.errors
            }
        }
    }

    const uplaodInvoiceCustomerHandler = async (id, invoice) => {
        try {

            loading.value = true;
            const { data } = await uplaodInvoiceCustomer(id, invoice);
            res.value = data;
            loading.value = false;
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    const uplaodInvoiceSupplierHandler = async (id, invoice) => {
        try {
            loading.value = true;
            const { data } = await uplaodInvoiceSupplier(id, invoice);
            res.value = data;
            loading.value = false;
        } catch (error) {
            loading.value = false
            if (error.response) {
                errors.value = error.response.data.message
            } else {
                errors.value = "Something went wrong"
            }
        }
    }

    return {
        collection, errors, res, loading, retrieveItem, updateInvoiceHandler, uplaodInvoiceCustomerHandler, uplaodInvoiceSupplierHandler
    }
})