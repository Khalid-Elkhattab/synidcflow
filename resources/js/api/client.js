import axios from 'axios';

const TOKEN_KEY = 'syndicflow_token';
const USER_KEY = 'syndicflow_user';

export const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        Accept: 'application/json',
    },
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem(TOKEN_KEY);
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem(TOKEN_KEY);
            localStorage.removeItem(USER_KEY);
            if (window.location.pathname !== '/login') {
                window.location.assign('/login');
            }
        }
        return Promise.reject(error);
    }
);

export function getErrorMessage(error) {
    if (!error.response) {
        return 'Erreur réseau. Vérifiez votre connexion.';
    }
    const data = error.response?.data;
    if (typeof data?.message === 'string' && data.message) {
        return data.message;
    }
    if (data?.errors) {
        const first = Object.values(data.errors)[0];
        if (Array.isArray(first)) {
            return first[0];
        }
        return first;
    }
    return 'Une erreur est survenue.';
}

export function getFieldErrors(error) {
    return error.response?.data?.errors ?? {};
}

export const tokenStore = {
    set(token) {
        localStorage.setItem(TOKEN_KEY, token);
    },
    get() {
        return localStorage.getItem(TOKEN_KEY);
    },
    clear() {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
    },
    setUser(user) {
        localStorage.setItem(USER_KEY, JSON.stringify(user));
    },
    getUser() {
        try {
            return JSON.parse(localStorage.getItem(USER_KEY));
        } catch {
            return null;
        }
    },
};