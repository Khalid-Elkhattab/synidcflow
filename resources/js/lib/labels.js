export const RECLAMATION_STATUT = {
    submitted: { label: 'Soumise', color: 'default' },
    under_review: { label: 'En cours d’examen', color: 'blue' },
    accepted: { label: 'Acceptée', color: 'green' },
    rejected: { label: 'Rejetée', color: 'red' },
    resolved: { label: 'Résolue', color: 'violet' },
    closed: { label: 'Clôturée', color: 'amber' },
};

export const PRIORITE = {
    low: { label: 'Basse', color: 'default' },
    medium: { label: 'Moyenne', color: 'blue' },
    high: { label: 'Haute', color: 'amber' },
    urgent: { label: 'Urgente', color: 'red' },
};

export const CHARGE_STATUT = {
    pending: { label: 'En attente', color: 'amber' },
    paid: { label: 'Payée', color: 'green' },
    overdue: { label: 'En retard', color: 'red' },
};

export const AUDIT_STATUT = {
    pending: { label: 'En attente', color: 'default' },
    processing: { label: 'En traitement', color: 'blue' },
    completed: { label: 'Terminé', color: 'green' },
    failed: { label: 'Échec', color: 'red' },
};

export const AUDIT_DECISION = {
    accepted: { label: 'Acceptée', color: 'green' },
    rejected: { label: 'Rejetée', color: 'red' },
    review: { label: 'À examiner', color: 'amber' },
};

export function formatDate(value) {
    if (!value) {
        return '—';
    }
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) {
        return value;
    }
    return d.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
}