import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost/api', // Adjust as needed
  headers: {
    'Content-Type': 'application/json',
  },
});

export default api;
