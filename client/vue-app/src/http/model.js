import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH
const entity = "model"

// CRUD
export const create = (model) => api.post(`${base}/${entity}-create`, model)
export const getAll = (id) => api.get(`${base}/${entity}-brand/${id}`)
export const update = (id, model) => api.post(`${base}/${entity}/${id}`, model, {
    params: {
        _method: 'PUT'
    }
})
export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)