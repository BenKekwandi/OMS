import api from "./axios-setup.js";

// Offer api
const base = import.meta.env.VITE_API_PATH

export const getAll = (orderId) => api.get(`${base}/matching-offers/${orderId}`);
