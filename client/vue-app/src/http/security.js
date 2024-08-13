import api from "./axios-setup.js";

// Sales manager api
const apiPath = import.meta.env.VITE_API_PATH


export const getIPs = () => api.get(apiPath + '/users-auth')

export const blockIP = (id, ip) => api.post(apiPath + `/block/${id}`, ip)

export const unblockIP = (id, ip) => api.post(apiPath + `/unblock/${id}`, ip)