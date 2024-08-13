import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/expense"

export const getAll = () => api.get(`${resource}`)

export const create = (expense) => api.post(`${resource}`, expense)

export const remove = (id) => api.delete(`${resource}/${id}`)

export const update = (id, expense) => api.put(`${resource}/${id}`, expense)

