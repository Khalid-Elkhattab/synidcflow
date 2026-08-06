export default function Spinner({ label = 'Chargement…' }) {
    return (
        <div className="flex flex-col items-center justify-center gap-3 py-12">
            <div className="h-8 w-8 animate-spin rounded-full border-2 border-blue-600 border-t-transparent" />
            <p className="text-sm text-gray-500">{label}</p>
        </div>
    );
}