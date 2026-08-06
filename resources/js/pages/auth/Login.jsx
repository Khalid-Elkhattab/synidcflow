import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { getErrorMessage, getFieldErrors } from '../../api/client';
import { Input, Button } from '../../components/ui';
import { Alert } from '../../components/Alert';

export default function Login() {
    const { login } = useAuth();
    const navigate = useNavigate();
    const [form, setForm] = useState({ email: '', password: '' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [globalError, setGlobalError] = useState(null);
    const [loading, setLoading] = useState(false);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setGlobalError(null);
        setFieldErrors({});
        try {
            const user = await login(form.email, form.password);
            navigate(user.role === 'admin' ? '/admin' : user.role === 'syndic' ? '/syndic' : '/resident');
        } catch (err) {
            setGlobalError(getErrorMessage(err));
            setFieldErrors(getFieldErrors(err));
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4">
            <div className="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
                <div className="mb-6 text-center">
                    <span className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white">S</span>
                    <h1 className="text-2xl font-semibold text-gray-900">Se connecter</h1>
                    <p className="mt-1 text-sm text-gray-500">Bienvenue sur SyndicFlow</p>
                </div>

                {globalError && <div className="mb-4"><Alert type="error">{globalError}</Alert></div>}

                <form onSubmit={handleSubmit} className="space-y-4">
                    <Input
                        label="Email"
                        type="email"
                        name="email"
                        autoComplete="email"
                        required
                        placeholder="vous@exemple.fr"
                        value={form.email}
                        onChange={handleChange}
                        error={fieldErrors.email?.[0]}
                    />
                    <Input
                        label="Mot de passe"
                        type="password"
                        name="password"
                        autoComplete="current-password"
                        required
                        placeholder="••••••••"
                        value={form.password}
                        onChange={handleChange}
                        error={fieldErrors.password?.[0]}
                    />
                    <Button type="submit" loading={loading} className="w-full">
                        Connexion
                    </Button>
                </form>

                <p className="mt-6 text-center text-sm text-gray-600">
                    Pas encore de compte ?{' '}
                    <Link to="/register" className="font-medium text-blue-600 hover:underline">
                        S'inscrire
                    </Link>
                </p>
            </div>
        </div>
    );
}