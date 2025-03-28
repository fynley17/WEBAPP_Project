import axios from 'axios';

// Create an instance of axios with a base URL and default headers
const api = axios.create({
  baseURL: 'https://WS381211-wad-api.remote.ac/api', // Base URL for the API (adjust as needed)
  headers: {
    'Content-Type': 'application/json', // Set the content type to JSON
  },
});

// Add an interceptor to include the authorisation token in requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token'); // Retrieve the token from local storage
  if (token) {
    config.headers.Authorization = `Bearer ${token}`; // Add the token to the request headers
  }
  return config; // Return the updated config
});

// Export the configured axios instance for use in the application
export default api;
