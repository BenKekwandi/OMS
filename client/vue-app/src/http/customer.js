import api from "./axios-setup.js";

// Customer api
const base = import.meta.env.VITE_API_PATH
const entity = "customer"

// CRUD
export const create = (customer) => api.post(`${base}/${entity}-create`, customer)
export const getAll = () => api.get(`${base}/${entity}s`)
export const update = (id, customer) => api.put(`${base}/${entity}/${id}`, customer)
export const remove = (ids) => api.post(`${base}/${entity}-delete`, ids)

export const getAssociated = (id) => api.get(`${base}/sm-${entity}/${id}`)

export const exportAll = () => api.get(`${base}/${entity}s/export`)

export const transfer = (customerID, managerID) => api.put(`${base}/${entity}-transfer/${customerID}`, managerID)