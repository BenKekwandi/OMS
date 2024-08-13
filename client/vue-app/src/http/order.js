import api from "./axios-setup.js";

// Order api
const base = import.meta.env.VITE_API_PATH
const entity = "order"

// CRUD
export const create = (order) => api.post(`${base}/${entity}-create`, order)
export const getAll = () => api.get(`${base}/${entity}s`)
export const update = (id, order) => api.post(`${base}/${entity}/${id}`, order, {
    params: {
        _method: 'PUT'
    }
})

export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)


export const getAccOrders = () => api.get(`${base}/account-${entity}s`)

export const getLogOrders = () => api.get(`${base}/logistic-${entity}s`)

export const exportAll = () => api.get(`${base}/${entity}s/export`)

export const importOrder = (file) => api.post(`${base}/${entity}s/import`,file)

export const exportAccOrders = (ids) => api.post(`${base}/acc-orders/export`, ids)

export const reset = (resetInfo) => api.post(`${base}/${entity}-reset`, resetInfo);

export const filter = (data) => api.post(`${base}/${entity}-query`, data)

export const setFinalizeOrder = (id) => api.put(`${base}/set-finalized/${id}`, id)