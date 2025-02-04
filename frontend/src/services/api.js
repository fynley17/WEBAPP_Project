import axios from 'axios';

const api = axios.create({
  baseURL: 'https://ws381211-wad-api.remote.ac/api', // Adjust as needed
  headers: {
    'Content-Type': 'application/json',
  },
});

export default api;
