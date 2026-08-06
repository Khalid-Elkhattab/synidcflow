import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../../api/client';
import { PageHeader, Card, StatCard, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';
import { formatDate, RECLAMATION_STATUT } from '../../lib/labels';

export default function ResidentDashboard() {
    const [reclamations, setReclamations] = useState([]);
    const [residences, setResidences] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        Promise.allSettled([
            api.get('/reclamations', { params: { page: 1, per_page: 5 } }),
            api.get('/residences'),
        ]).then(([rec, res]) => {
            setReclamations(rec.value?.data?.data?.reclamations ?? []);
            setResidences(res.value?.data?.data?.residences ?? []);
            setError(null);
            setLoading(false);
        });
    }, []);

    if (loading) {
        return <Spinner />;
    }

    return (
        <div>
            <PageHeader title="Tableau de bord" subtitle="Vue d'ensemble de votre copropriété" />
            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <div className="grid gap-4 sm:grid-cols-2">
                <StatCard label="Mes réclamations" value={reclamations.length} icon="📝" />
                <StatCard label="Mes résidences" value={residences.length} icon="🏢" />
            </div>

            <div className="mt-6">
                <Card>
                    <div className="flex items-center justify-between px-4 pt-4">
                        <h2 className="text-lg font-semibold text-gray-900">Mes réclamations</h2>
                        <Link to="/resident/reclamations" className="text-sm font-medium text-blue-600 hover:underline">Gérer</Link>
                    </div>
                    {reclamations.length === 0 ? (
                        <div className="p-6"><EmptyState title="Aucune réclamation" message="Déposez votre première réclamation." /></div>
                    ) : (
                        <Table headers={['Titre', 'Statut', 'Date']}>
                            {reclamations.map((r) => (
                                <tr key={r.id}>
                                    <Td className="font-medium text-gray-900">{r.titre}</Td>
                                    <Td><Badge color={RECLAMATION_STATUT[r.statut]?.color}>{RECLAMATION_STATUT[r.statut]?.label ?? r.statut}</Badge></Td>
                                    <Td>{formatDate(r.created_at)}</Td>
                                </tr>
                            ))}
                        </Table>
                    )}
                </Card>
            </div>
        </div>
    );
}