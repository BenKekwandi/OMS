import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH


export const getAll = () => api.get(`${base}/d-users`)
export const reactivate = (ids) => api.post(`${base}/user-active`, ids)