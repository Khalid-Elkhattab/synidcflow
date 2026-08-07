import { Link } from 'react-router-dom';

export default function NotFound() {
    return (
        <div className="flex min-h-screen flex-col items-center justify-center gap-4 bg-gray-100 px-4">
            <p className="text-6xl font-bold text-blue-600">404</p>
            <h1 className="text-xl font-semibold text-gray-900">Page introuvable</h1>
            <p className="text-sm text-gray-500">La page que vous cherchez n'existe pas.</p>
            <Link to="/" className="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Retour à l'accueil
            </Link>
        </div>
    );
}