import { useCallback, useEffect, useState } from 'react';
import { api, getErrorMessage } from '../api/client';

export default function usePaginatedList(key, fetcher, deps = []) {
    const [items, setItems] = useState([]);
    const [pagination, setPagination] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const load = useCallback(
        async (page = 1) => {
            setLoading(true);
            setError(null);
            try {
                const payload = fetcher(page);
                const res = await (typeof payload === 'string'
                    ? api.get(payload)
                    : payload);
                const data = res.data?.data;
                const list = data?.[key] ?? [];
                setItems(list.data ?? list);
                setPagination(list.meta ?? null);
            } catch (e) {
                setError(getErrorMessage(e));
            } finally {
                setLoading(false);
            }
        },
        [key, ...deps]
    );

    useEffect(() => {
        load(1);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [load]);

    return { items, pagination, loading, error, load, setItems };
}

export function Pagination({ pagination, onPage, loading }) {
    if (!pagination || pagination.last_page <= 1) {
        return null;
    }

    const pages = [];
    const current = pagination.current_page;
    for (let i = 1; i <= pagination.last_page; i += 1) {
        if (i === 1 || i === pagination.last_page || Math.abs(i - current) <= 2) {
            pages.push(i);
        } else if (pages[pages.length - 1] !== '…') {
            pages.push('…');
        }
    }

    return (
        <div className="flex items-center justify-between border-t border-gray-200 px-4 py-3">
            <p className="text-sm text-gray-600">
                Page {pagination.current_page} sur {pagination.last_page}
            </p>
            <div className="flex items-center gap-1">
                <button
                    type="button"
                    disabled={current <= 1 || loading}
                    onClick={() => onPage(current - 1)}
                    className="rounded-md border border-gray-300 px-3 py-1 text-sm text-gray-700 transition hover:bg-gray-50 disabled:opacity-40"
                >
                    Précédent
                </button>
                <div className="mx-1 flex items-center">
                    {pages.map((p, i) =>
                        p === '…' ? (
                            <span key={`e-${i}`} className="px-1 text-sm text-gray-400">
                                …
                            </span>
                        ) : (
                            <button
                                key={p}
                                type="button"
                                onClick={() => onPage(p)}
                                disabled={loading}
                                className={`rounded-md px-2 py-1 text-sm transition disabled:opacity-40 ${
                                    p === current ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'
                                }`}
                            >
                                {p}
                            </button>
                        )
                    )}
                </div>
                <button
                    type="button"
                    disabled={current >= pagination.last_page || loading}
                    onClick={() => onPage(current + 1)}
                    className="rounded-md border border-gray-300 px-3 py-1 text-sm text-gray-700 transition hover:bg-gray-50 disabled:opacity-40"
                >
                    Suivant
                </button>
            </div>
        </div>
    );
}