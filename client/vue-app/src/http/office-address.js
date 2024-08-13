import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/office-address"

export const getAddresses = () => api.get(resource)

export const createAddress = (address) => api.post(resource, address)

export const deleteAddresses = (ids) => api.post(`${resource}-delete`, ids)

export const updateAddress = (id, address) => api.put(`${resource}/${id}`, address)

// export const exportUsers= (role) => api.post(resource, role)
