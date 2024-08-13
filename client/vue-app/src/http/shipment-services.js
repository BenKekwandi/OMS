import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/shipment-service"

export const getServices = () => api.get(resource)

export const createService = (service) => api.post(resource, service)

export const deleteServices = (ids) => api.post(`${resource}-delete`, ids)

export const updateService = (id, service) => api.put(`${resource}/${id}`, service)

// export const exportUsers= (role) => api.post(resource, role)
