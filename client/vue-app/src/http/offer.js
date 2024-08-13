import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH
const entity = "offer"

// CRUD
export const create = (offer) => api.post(`${base}/${entity}-create`, offer)
export const getAll = () => api.get(`${base}/${entity}s`)
export const update = (id, offer) => api.post(`${base}/${entity}/${id}`, offer, {
    params: {
        _method: 'PUT'
    }
})
export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)

export const exportAll = () => api.get(`${base}/${entity}s/export`)

export const importOffer = (file) => api.post(`${base}/${entity}s/import`, file)

export const reset = (ids) => api.post(`${base}/${entity}-reset`, ids)

export const filter = (data) => api.post(`${base}/${entity}-query`, data)

