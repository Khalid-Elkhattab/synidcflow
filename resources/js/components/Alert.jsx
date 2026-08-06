export function Alert({ type = 'error', children }) {
    const styles = {
        error: 'border-red-300 bg-red-50 text-red-700',
        success: 'border-green-300 bg-green-50 text-green-700',
        info: 'border-blue-300 bg-blue-50 text-blue-700',
    };

    return (
        <div className={`rounded-md border px-4 py-3 text-sm ${styles[type]}`} role="alert">
            {children}
        </div>
    );
}

export function FieldError({ errors }) {
    if (!errors || errors.length === 0) {
        return null;
    }
    return (
        <p className="mt-1 text-xs text-red-600">{errors[0]}</p>
    );
}