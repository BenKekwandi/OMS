import api from "./axios-setup.js";

// Invoice api
const base = import.meta.env.VITE_API_PATH

const entity = 'invoice-file';

export const getItemWithId = (id) => api.get(`${base}/${entity}/${id}`, {
    responseType: 'blob'
});

export const updateInvoice = (id, invoice) => api.post(`${base}/invoice-update/${id}`, invoice, {
    params: {
        _method: 'PUT'
    }
})
export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)

export const uplaodInvoiceCustomer = (id, invoice) => api.post(`${base}/customer-invoice/${id}`, invoice, {
    params: {
        _method: 'PUT'
    }
})

export const uplaodInvoiceSupplier = (id, invoice) => api.post(`${base}/order-confirm/${id}`, invoice, {
    params: {
        _method: 'PUT'
    }
})


