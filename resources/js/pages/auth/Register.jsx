import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { getErrorMessage, getFieldErrors } from '../../api/client';
import { Input, Button } from '../../components/ui';
import { Alert } from '../../components/Alert';

export default function Register() {
    const { register } = useAuth();
    const navigate = useNavigate();
    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });
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
            const user = await register(form);
            navigate('/resident');
        } catch (err) {
            setGlobalError(getErrorMessage(err));
            setFieldErrors(getFieldErrors(err));
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 py-8">
            <div className="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
                <div className="mb-6 text-center">
                    <span className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white">S</span>
                    <h1 className="text-2xl font-semibold text-gray-900">Créer un compte</h1>
                    <p className="mt-1 text-sm text-gray-500">Inscription résident</p>
                </div>

                {globalError && <div className="mb-4"><Alert type="error">{globalError}</Alert></div>}

                <form onSubmit={handleSubmit} className="space-y-4">
                    <Input
                        label="Nom complet"
                        type="text"
                        name="name"
                        autoComplete="name"
                        required
                        placeholder="Jean Dupont"
                        value={form.name}
                        onChange={handleChange}
                        error={fieldErrors.name?.[0]}
                    />
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
                        autoComplete="new-password"
                        required
                        placeholder="8 caractères min, lettres, chiffres et symboles"
                        value={form.password}
                        onChange={handleChange}
                        error={fieldErrors.password?.[0]}
                    />
                    <Input
                        label="Confirmation du mot de passe"
                        type="password"
                        name="password_confirmation"
                        autoComplete="new-password"
                        required
                        placeholder="••••••••"
                        value={form.password_confirmation}
                        onChange={handleChange}
                        error={fieldErrors.password_confirmation?.[0]}
                    />
                    <Button type="submit" loading={loading} className="w-full">
                        S'inscrire
                    </Button>
                </form>

                <p className="mt-6 text-center text-sm text-gray-600">
                    Déjà inscrit ?{' '}
                    <Link to="/login" className="font-medium text-blue-600 hover:underline">
                        Se connecter
                    </Link>
                </p>
            </div>
        </div>
    );
}