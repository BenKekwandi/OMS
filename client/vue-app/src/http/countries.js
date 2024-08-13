import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH
const entity = "country"

// CRUD
export const create = (brand) => api.post(`${base}/${entity}-create`, brand)
export const getAll = () => api.get(`${base}/countries`)
export const update = (id, brand) => api.put(`${base}/${entity}/${id}`, brand)
export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)

export const getAssociated = (id) => api.get(`${base}/${entity}/${id}`)