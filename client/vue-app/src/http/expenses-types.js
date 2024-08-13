import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/expenses-type"

export const getAll = () => api.get(`${resource}s`)

export const create = (expense) => api.post(`${resource}-create`, expense)

export const remove = (ids) => api.post(`${resource}-delete`, ids)

export const update = (id, expense) => api.put(`${resource}/${id}`, expense)

