import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/shipment-account"

export const getAccounts = (id) => api.get(`${resource}-service/${id}`)

export const createAccount = (account) => api.post(resource, account)

export const deleteAccounts = (ids) => api.post(`${resource}-delete`, ids)

export const updateAccount = (id, account) => api.put(`${resource}/${id}`, account)

// export const exportUsers= (role) => api.post(resource, role)
