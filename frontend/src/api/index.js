// src/api/index.js
import axios from 'axios';

// Gunakan environment variable, fallback ke localhost untuk development
const Api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api' 
});

// Interceptor untuk menyisipkan token
Api.interceptors.request.use((config) => {
    const token = sessionStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

export default Api;