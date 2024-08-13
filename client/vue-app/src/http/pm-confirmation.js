import api from "./axios-setup.js";

// Confirmation api
const base = import.meta.env.VITE_API_PATH
const entity = "proposal"

// CRUD
export const getAll = () => api.get(`${base}/${entity}s`)

export const update = (id, item) => api.put(`${base}/proposal-pconfirm/${id}`, item)
export const cancel = (id, item) => api.put(`${base}/proposal-pcancel/${id}`, item)