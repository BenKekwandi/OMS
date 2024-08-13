import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/label"

export const getLabels = () => api.get(resource)

export const createLabel = (label) => api.post(resource, label)

export const deleteLabels = (id) => api.delete(`${resource}/${id}`)

export const updateLabel = (id, label) => api.put(`${resource}/${id}`, label)

export const createLabelInvoice = (invoice) => api.post(`${resource}-invoice`, invoice)

export const setCollected = (id, collectedAt) => api.put(`${apiPath}/set-collected/${id}`, collectedAt)

export const setDelivered = (id, DeliveredAt) => api.put(`${apiPath}/set-delivered/${id}`, DeliveredAt)

export const stepBack = (id) => api.put(`${resource}-stepback/${id}`)

