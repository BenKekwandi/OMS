import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/invoice-company"

export const getAll = () => api.get(apiPath + '/invoice-companies')

export const create = (company) => api.post(`${resource}-create`, company)

export const remove = (ids) => api.post(`${resource}-delete`, ids)

export const update = (id, company) => api.put(`${resource}/${id}`, company)

export const getAssociated = (id) => api.get(`${resource}/${id}`)
