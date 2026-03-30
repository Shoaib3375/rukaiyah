import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set API base URL
const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1';
window.axios.defaults.baseURL = apiUrl;

