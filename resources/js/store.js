import { ref, computed } from 'vue';
import { authAPI } from './api';
import { initializeReverb } from './reverb';

const user = ref(null);
const token = ref(localStorage.getItem('token'));
const isAuthenticated = computed(() => !!token.value);
const userRole = computed(() => user.value?.role);
const authReady = ref(false);

// Initialize user on app load
export const initAuth = async () => {
    if (token.value) {
        try {
            const { data } = await authAPI.me();
            user.value = data.data;
            initializeReverb();
        } catch (error) {
            logout();
        }
    }
    authReady.value = true;
};

export const login = async (email, password) => {
    try {
        const { data } = await authAPI.login(email, password);
        token.value = data.data.access_token;
        user.value = data.data.user;
        localStorage.setItem('token', token.value);
        initializeReverb();
        return { success: true, data };
    } catch (error) {
        return { success: false, error: error.response?.data?.message || 'Login failed' };
    }
};

export const register = async (formData) => {
    try {
        const { data } = await authAPI.register(formData);
        token.value = data.data.access_token;
        user.value = data.data.user;
        localStorage.setItem('token', token.value);
        initializeReverb();
        return { success: true, data };
    } catch (error) {
        return { success: false, error: error.response?.data?.message || 'Registration failed' };
    }
};

export const logout = async () => {
    try {
        await authAPI.logout();
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        token.value = null;
        user.value = null;
        localStorage.removeItem('token');
    }
};

export const refreshToken = async () => {
    try {
        const { data } = await authAPI.refresh();
        token.value = data.data.access_token;
        localStorage.setItem('token', token.value);
        return { success: true };
    } catch (error) {
        logout();
        return { success: false, error };
    }
};

export {
    user,
    token,
    isAuthenticated,
    userRole,
    authReady
};

