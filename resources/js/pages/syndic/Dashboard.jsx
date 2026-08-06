import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../../api/client';
import { PageHeader, Card, StatCard, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';
import { formatDate, RECLAMATION_STATUT } from '../../lib/labels';

export default function SyndicDashboard() {
    const [residences, setResidences] = useState([]);
    const [reclamations, setReclamations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        Promise.allSettled([
            api.get('/residences'),
            api.get('/reclamations', { params: { page: 1, per_page: 5 } }),
        ]).then(([res, rec]) => {
            setResidences(res.value?.data?.data?.residences ?? []);
            setReclamations(rec.value?.data?.data?.reclamations ?? []);
            setError(rec.reason ? 'Impossible de charger certaines données.' : null);
            setLoading(false);
        });
    }, []);

    if (loading) {
        return <Spinner />;
    }

    return (
        <div>
            <PageHeader title="Tableau de bord" subtitle="Vos résidences et réclamations" />
            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <div className="grid gap-4 sm:grid-cols-2">
                <StatCard label="Résidences" value={residences.length} icon="🏢" />
                <StatCard label="Réclamations récentes" value={reclamations.length} icon="📝" />
            </div>

            <div className="mt-6 grid gap-6 lg:grid-cols-2">
                <Card>
                    <div className="flex items-center justify-between px-4 pt-4">
                        <h2 className="text-lg font-semibold text-gray-900">Mes résidences</h2>
                        <Link to="/syndic/residences" className="text-sm font-medium text-blue-600 hover:underline">Gérer</Link>
                    </div>
                    {residences.length === 0 ? (
                        <div className="p-6"><EmptyState title="Aucune résidence" /></div>
                    ) : (
                        <Table headers={['Nom', 'Ville']}>
                            {residences.map((r) => (
                                <tr key={r.id}>
                                    <Td className="font-medium text-gray-900">{r.name}</Td>
                                    <Td>{r.city}</Td>
                                </tr>
                            ))}
                        </Table>
                    )}
                </Card>

                <Card>
                    <div className="flex items-center justify-between px-4 pt-4">
                        <h2 className="text-lg font-semibold text-gray-900">Dernières réclamations</h2>
                        <Link to="/syndic/reclamations" className="text-sm font-medium text-blue-600 hover:underline">Voir tout</Link>
                    </div>
                    {reclamations.length === 0 ? (
                        <div className="p-6"><EmptyState title="Aucune réclamation" /></div>
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