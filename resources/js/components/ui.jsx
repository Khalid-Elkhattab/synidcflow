import { useState } from 'react';

const inputClass =
    'w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500';

export function Input({ label, error, ...props }) {
    return (
        <label className="block">
            {label && <span className="mb-1 block text-sm font-medium text-gray-700">{label}</span>}
            <input className={inputClass} {...props} />
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </label>
    );
}

export function Select({ label, error, children, ...props }) {
    return (
        <label className="block">
            {label && <span className="mb-1 block text-sm font-medium text-gray-700">{label}</span>}
            <select className={inputClass} {...props}>
                {children}
            </select>
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </label>
    );
}

export function Button({ variant = 'primary', loading = false, children, type = 'button', ...props }) {
    const variants = {
        primary: 'bg-blue-600 text-white hover:bg-blue-700',
        secondary: 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        danger: 'bg-red-600 text-white hover:bg-red-700',
        success: 'bg-green-600 text-white hover:bg-green-700',
    };

    return (
        <button
            type={type}
            disabled={loading || props.disabled}
            className={`inline-flex items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-60 ${variants[variant]} ${props.className ?? ''}`}
            {...props}
        >
            {loading && (
                <span className="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" />
            )}
            {children}
        </button>
    );
}

export function ConfirmButton({ onConfirm, children, className = '' }) {
    const [open, setOpen] = useState(false);

    if (!open) {
        return (
            <button
                type="button"
                onClick={() => setOpen(true)}
                className={`rounded-md px-3 py-1.5 text-sm font-medium transition ${className}`}
            >
                {children}
            </button>
        );
    }

    return (
        <span className="inline-flex items-center gap-2">
            <button
                type="button"
                onClick={async () => {
                    await onConfirm();
                    setOpen(false);
                }}
                className="rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700"
            >
                Confirmer
            </button>
            <button
                type="button"
                onClick={() => setOpen(false)}
                className="rounded-md bg-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-300"
            >
                Annuler
            </button>
        </span>
    );
}