export function PageHeader({ title, subtitle, actions }) {
    return (
        <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                {subtitle && <p className="mt-1 text-sm text-gray-500">{subtitle}</p>}
            </div>
            {actions && <div className="flex items-center gap-2">{actions}</div>}
        </div>
    );
}

export function Card({ children, className = '' }) {
    return <div className={`overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm ${className}`}>{children}</div>;
}

export function Table({ headers, children }) {
    return (
        <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                    <tr>
                        {headers.map((h) => (
                            <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                {h}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody className="divide-y divide-gray-100">{children}</tbody>
            </table>
        </div>
    );
}

export function Td({ children, className = '' }) {
    return <td className={`px-4 py-3 text-sm text-gray-700 ${className}`}>{children}</td>;
}

export function StatCard({ label, value, icon }) {
    return (
        <div className="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            {icon && (
                <span className="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-xl text-blue-600">
                    {icon}
                </span>
            )}
            <div>
                <p className="text-3xl font-semibold text-gray-900">{value}</p>
                <p className="text-sm text-gray-500">{label}</p>
            </div>
        </div>
    );
}