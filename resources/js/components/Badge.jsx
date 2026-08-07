const palette = {
    default: 'bg-gray-100 text-gray-700',
    blue: 'bg-blue-100 text-blue-700',
    green: 'bg-green-100 text-green-700',
    red: 'bg-red-100 text-red-700',
    amber: 'bg-amber-100 text-amber-700',
    violet: 'bg-violet-100 text-violet-700',
};

export default function Badge({ color = 'default', children }) {
    return (
        <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${palette[color] ?? palette.default}`}>
            {children}
        </span>
    );
}

export function EmptyState({ title = 'Aucun élément', message = 'Aucune donnée à afficher pour le moment.' }) {
    return (
        <div className="flex flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-gray-300 bg-gray-50 py-12">
            <p className="text-sm font-medium text-gray-700">{title}</p>
            <p className="text-sm text-gray-500">{message}</p>
        </div>
    );
}