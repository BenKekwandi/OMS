import api from "./axios-setup.js";

const apiPath = import.meta.env.VITE_API_PATH
const resource = apiPath + "/users"

export const getUsers = () => api.get(resource)

export const createUser = (user) => api.post(`${resource}-create`, user)

export const deactivateUsers = (ids) => api.post(apiPath + '/user-deactivate', ids)

export const updateUser = (id, user) => api.put(`${resource}-update/${id}`, user)

export const exportUsers= (role) => api.post(`${resource}-export`, role)
