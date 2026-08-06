import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Spinner from '../components/Spinner';

export function RequireAuth({ children }) {
    const { user, loading } = useAuth();
    const location = useLocation();

    if (loading) {
        return (
            <div className="flex min-h-screen items-center justify-center">
                <Spinner />
            </div>
        );
    }

    if (!user) {
        return <Navigate to="/login" state={{ from: location }} replace />;
    }

    return children;
}

export function RequireGuest({ children }) {
    const { user, loading } = useAuth();

    if (loading) {
        return (
            <div className="flex min-h-screen items-center justify-center">
                <Spinner />
            </div>
        );
    }

    if (user) {
        return <Navigate to={homeFor(user.role)} replace />;
    }

    return children;
}

export function RequireRole({ roles, children }) {
    const { user } = useAuth();

    if (!user || !roles.includes(user.role)) {
        return <Navigate to={user ? homeFor(user.role) : '/login'} replace />;
    }

    return children;
}

export function homeFor(role) {
    switch (role) {
        case 'admin':
            return '/admin';
        case 'syndic':
            return '/syndic';
        case 'resident':
            return '/resident';
        default:
            return '/login';
    }
}