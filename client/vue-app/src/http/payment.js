import api from "./axios-setup.js";

// Payment api
const base = import.meta.env.VITE_API_PATH
const entity = "payment"

// CRUD
export const create = (payment) => api.post(`${base}/${entity}`, payment)
export const getById = (id) => api.get(`${base}/${entity}/${id}`)
export const update = (id, payment) => api.put(`${base}/${entity}/${id}`, payment)
export const remove = (id) => api.delete(`${base}/${entity}/${id}`)