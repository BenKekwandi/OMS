import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH
const entity = "proposal"

// CRUD
export const create = (proposal) => api.post(`${base}/${entity}-create`, proposal)
export const getAll = () => api.get(`${base}/${entity}s`)
export const confirm = (id, supplier) => api.put(`${base}/${entity}-confirm/${id}`, supplier)
export const cancel = (id, note) => api.put(`${base}/${entity}-cancel/${id}`, note)

export const update = (id, data) => api.put(`${base}/${entity}-update/${id}`, data)

