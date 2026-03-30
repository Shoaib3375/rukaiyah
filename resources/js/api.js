import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Add token to requests
api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Handle token refresh
api.interceptors.response.use(
    response => response,
    async error => {
        const originalRequest = error.config;
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            try {
                const { data } = await axios.post(`${API_URL}/auth/refresh`, {}, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });
                localStorage.setItem('token', data.data.access_token);
                api.defaults.headers.common['Authorization'] = `Bearer ${data.data.access_token}`;
                return api(originalRequest);
            } catch (refreshError) {
                localStorage.removeItem('token');
                window.location.href = '/login';
                return Promise.reject(refreshError);
            }
        }
        return Promise.reject(error);
    }
);

// Auth endpoints
export const authAPI = {
    register: (data) => api.post('/auth/register', data),
    login: (email, password) => api.post('/auth/login', { email, password }),
    logout: () => api.post('/auth/logout'),
    refresh: () => api.post('/auth/refresh'),
    me: () => api.get('/auth/me')
};

// Patient endpoints
export const patientAPI = {
    profile: {
        get: () => api.get('/patient/profile'),
        update: (data) => api.put('/patient/profile', data)
    },
    appointments: {
        list: () => api.get('/patient/appointments'),
        create: (data) => api.post('/patient/appointments', data),
        get: (id) => api.get(`/patient/appointments/${id}`),
        cancel: (id) => api.delete(`/patient/appointments/${id}`)
    },
    raqis: {
        list: () => api.get('/patient/raqis'),
        get: (id) => api.get(`/patient/raqis/${id}`),
        getAvailableSlots: (id, params) => api.get(`/patient/raqis/${id}/available-slots`, { params })
    },
    reviews: {
        create: (data) => api.post('/patient/reviews', data)
    },
    notifications: {
        list: () => api.get('/patient/notifications'),
        markRead: (id) => api.put(`/patient/notifications/${id}/read`)
    }
};

// Raqi endpoints
export const raqiAPI = {
    profile: {
        get: () => api.get('/raqi/profile'),
        update: (data) => api.put('/raqi/profile', data)
    },
    availability: {
        list: () => api.get('/raqi/availability'),
        create: (data) => api.post('/raqi/availability', data),
        delete: (id) => api.delete(`/raqi/availability/${id}`)
    },
    appointments: {
        list: () => api.get('/raqi/appointments'),
        accept: (id) => api.put(`/raqi/appointments/${id}/accept`),
        decline: (id) => api.put(`/raqi/appointments/${id}/decline`),
        complete: (id) => api.put(`/raqi/appointments/${id}/complete`)
    },
    participants: {
        list: (appointmentId) => api.get(`/raqi/appointments/${appointmentId}/participants`),
        invite: (appointmentId, data) => api.post(`/raqi/appointments/${appointmentId}/invite`, data)
    },
    notes: {
        list: (appointmentId) => api.get(`/raqi/appointments/${appointmentId}/notes`),
        create: (appointmentId, data) => api.post(`/raqi/appointments/${appointmentId}/notes`, data)
    },
    followup: {
        create: (appointmentId, data) => api.post(`/raqi/appointments/${appointmentId}/followup`, data)
    },
    notifications: {
        list: () => api.get('/raqi/notifications'),
        markRead: (id) => api.put(`/raqi/notifications/${id}/read`)
    }
};

// Admin endpoints
export const adminAPI = {
    users: {
        list: (params) => api.get('/admin/users', { params }),
        get: (id) => api.get(`/admin/users/${id}`),
        activate: (id, data) => api.put(`/admin/users/${id}/activate`, data)
    },
    raqis: {
        pending: () => api.get('/admin/raqis/pending'),
        approved: () => api.get('/admin/raqis/approved'),
        approve: (id) => api.put(`/admin/raqis/${id}/approve`),
        suspend: (id) => api.put(`/admin/raqis/${id}/suspend`)
    },
    appointments: {
        list: (params) => api.get('/admin/appointments', { params }),
        update: (id, data) => api.put(`/admin/appointments/${id}`, data)
    },
    stats: {
        index: () => api.get('/admin/stats')
    }
};

// Invites endpoints
export const inviteAPI = {
    accept: (token) => api.post(`/invites/${token}/accept`),
    decline: (token) => api.post(`/invites/${token}/decline`)
};

export default api;
