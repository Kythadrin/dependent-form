import axios from 'axios';
window.axios = axios;

window.axios.defaults.baseURL = 'http://localhost:8080/api';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
