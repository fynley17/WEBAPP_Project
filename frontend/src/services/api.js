import axios from 'axios';

const api = axios.create({
  baseURL: 'https://ws381211-wad-api.remote.ac/api', // Adjust as needed
  headers: {
    'Content-Type': 'application/json',
  },
});

api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
