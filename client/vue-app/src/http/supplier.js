import api from "./axios-setup.js";

// Supplier api
const base = import.meta.env.VITE_API_PATH
const entity = "supplier"

// CRUD
export const create = (supplier) => api.post(`${base}/${entity}-create`, supplier)

export const getAll = () => api.get(`${base}/${entity}s`)

export const update = (id, supplier) => api.put(`${base}/${entity}/${id}`, supplier)

export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)

export const getAssociated = (id) => api.get(`${base}/pm-${entity}/${id}`)

export const exportAll = () => api.get(`${base}/${entity}s/export`)


export const transfer = (supplierID, managerID) => api.put(`${base}/${entity}-transfer/${supplierID}`, managerID)