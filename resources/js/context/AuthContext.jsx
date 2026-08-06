import { createContext, useCallback, useContext, useEffect, useState } from 'react';
import { api, tokenStore as store } from '../api/client';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(() => store.getUser());
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const token = store.get();
        if (!token) {
            setLoading(false);
            return;
        }
        api.get('/me')
            .then((res) => {
                const me = res.data?.data?.user;
                store.setUser(me);
                setUser(me);
            })
            .catch(() => {
                store.clear();
                setUser(null);
            })
            .finally(() => setLoading(false));
    }, []);

    const login = useCallback(async (email, password) => {
        const res = await api.post('/login', { email, password });
        const data = res.data?.data;
        store.set(data.token);
        store.setUser(data.user);
        setUser(data.user);
        return data.user;
    }, []);

    const register = useCallback(async (payload) => {
        const res = await api.post('/register', payload);
        const data = res.data.data;
        store.set(data.token);
        store.setUser(data.user);
        setUser(data.user);
        return data.user;
    }, []);

    const refreshUser = useCallback(async () => {
        const res = await api.get('/me');
        const me = res.data.data.user;
        store.setUser(me);
        setUser(me);
        return me;
    }, []);

    const logout = useCallback(async () => {
        try {
            await api.post('/logout');
        } finally {
            store.clear();
            setUser(null);
        }
    }, []);

    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                login,
                register,
                logout,
                refreshUser,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth doit être utilisé dans AuthProvider');
    }
    return context;
}

export const ROLES = {
    admin: 'admin',
    syndic: 'syndic',
    resident: 'resident',
};

export const ROLE_LABELS = {
    admin: 'Administrateur',
    syndic: 'Syndic',
    resident: 'Résident',
};