import { useState } from 'react';
import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { ROLE_LABELS } from '../context/AuthContext';
import Spinner from '../components/Spinner';

const navByRole = {
    admin: [
        { to: '/admin', label: 'Tableau de bord', end: true },
        { to: '/admin/users', label: 'Utilisateurs' },
        { to: '/admin/residences', label: 'Résidences' },
        { to: '/admin/reclamations', label: 'Réclamations' },
        { to: '/admin/audits', label: 'Audits IA' },
    ],
    syndic: [
        { to: '/syndic', label: 'Tableau de bord', end: true },
        { to: '/syndic/residences', label: 'Mes résidences' },
        { to: '/syndic/reclamations', label: 'Réclamations' },
        { to: '/syndic/audits', label: 'Audits IA' },
    ],
    resident: [
        { to: '/resident', label: 'Tableau de bord', end: true },
        { to: '/resident/appartements', label: 'Mes appartements' },
        { to: '/resident/charges', label: 'Mes charges' },
        { to: '/resident/reclamations', label: 'Mes réclamations' },
    ],
};

export default function Layout() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [loggingOut, setLoggingOut] = useState(false);

    if (!user) {
        return (
            <div className="flex min-h-screen items-center justify-center">
                <Spinner />
            </div>
        );
    }

    const nav = navByRole[user.role] ?? navByRole.resident;

    const handleLogout = async () => {
        setLoggingOut(true);
        try {
            await logout();
            navigate('/login', { replace: true });
        } finally {
            setLoggingOut(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-100">
            <aside className="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-gray-200 bg-white">
                <div className="flex h-16 items-center gap-2 border-b border-gray-200 px-6">
                    <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white">S</span>
                    <span className="text-lg font-semibold text-gray-900">SyndicFlow</span>
                </div>
                <nav className="flex-1 space-y-1 px-3 py-4">
                    {nav.map((item) => (
                        <NavLink
                            key={item.to}
                            to={item.to}
                            end={item.end}
                            className={({ isActive }) =>
                                `block rounded-md px-3 py-2 text-sm font-medium transition ${
                                    isActive
                                        ? 'bg-blue-50 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                                }`
                            }
                        >
                            {item.label}
                        </NavLink>
                    ))}
                </nav>
                <div className="border-t border-gray-200 p-4">
                    <p className="truncate text-sm font-medium text-gray-900">{user.name}</p>
                    <p className="text-xs text-gray-500">{ROLE_LABELS[user.role] ?? user.role}</p>
                </div>
            </aside>

            <div className="pl-64">
                <header className="sticky top-0 z-20 flex h-16 items-center justify-end border-b border-gray-200 bg-white px-8">
                    <button
                        type="button"
                        onClick={handleLogout}
                        disabled={loggingOut}
                        className="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 disabled:opacity-50"
                    >
                        {loggingOut ? <Spinner label="" /> : null}
                        Déconnexion
                    </button>
                </header>

                <main className="p-8">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}