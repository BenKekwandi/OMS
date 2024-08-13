import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/shipment"

export const getShipments = () => api.get(resource)

export const getShipment = (id) => api.get(`${resource}/${id}`)

export const createShipment = (shipment) => api.post(resource, shipment)

export const deleteShipments = (ids) => api.post(`${resource}-delete`, ids)

export const updateShipment = (id, shipment) => api.put(`${resource}/${id}`, shipment)

export const linkOrderToShipment = (ids) => api.post(`${apiPath + '/order-shipment'}`, ids)

export const deleteOrderToShipment = (ids) => api.post(`${apiPath + '/order-shipment-delete'}`, ids)

export const getOrdersOfShipment = (id) => api.get(`${apiPath}` + `/order-shipment-list/${id}`)


export const getAvailableOrders = () => api.get(`${apiPath}` + '/logistic-orders')

// export const exportUsers= (role) => api.post(resource, role)
