import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { api, getErrorMessage } from '../../api/client';
import { PageHeader, Card, StatCard, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';
import { formatDate, RECLAMATION_STATUT, PRIORITE, AUDIT_STATUT } from '../../lib/labels';

export default function AdminDashboard() {
    const navigate = useNavigate();
    const [stats, setStats] = useState({});
    const [reclamations, setReclamations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        Promise.allSettled([
            api.get('/residences'),
            api.get('/users'),
            api.get('/reclamations'),
            api.get('/reclamations', { params: { page: 1, per_page: 5 } }),
            api.get('/audits'),
        ]).then(([r, u, rec, recent, audits]) => {
            setStats({
                residences: r.value?.data?.data?.residences?.length ?? '—',
                users: u.value?.data?.data?.users?.length ?? '—',
                reclamations: rec.value?.data?.data?.reclamations?.length ?? '—',
                audits: audits.value?.data?.data?.audits?.length ?? '—',
            });
            setReclamations(recent.value?.data?.data?.reclamations ?? []);
            if (r.reason || u.reason || rec.reason) {
                setError(getErrorMessage(recent.reason ?? r.reason ?? u.reason ?? rec.reason));
            }
            setLoading(false);
        });
    }, []);

    if (loading) {
        return <Spinner />;
    }

    return (
        <div>
            <PageHeader title="Tableau de bord" subtitle="Vue d'ensemble de la plateforme" />
            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <StatCard label="Résidences" value={stats.residences} icon="🏢" />
                <StatCard label="Utilisateurs" value={stats.users} icon="👤" />
                <StatCard label="Réclamations" value={stats.reclamations} icon="📝" />
                <StatCard label="Audits IA" value={stats.audits} icon="🤖" />
            </div>

            <div className="mt-6">
                <Card>
                    <div className="flex items-center justify-between px-4 pt-4">
                        <h2 className="text-lg font-semibold text-gray-900">Dernières réclamations</h2>
                        <button className="text-sm font-medium text-blue-600 hover:underline" onClick={() => navigate('/admin/reclamations')}>
                            Voir tout
                        </button>
                    </div>
                    {reclamations.length === 0 ? (
                        <div className="p-6"><EmptyState title="Aucune réclamation" /></div>
                    ) : (
                        <Table headers={['Titre', 'Priorité', 'Statut', 'Déposée le']}>
                            {reclamations.map((r) => (
                                <tr key={r.id} className="cursor-pointer" onClick={() => navigate('/admin/reclamations')}>
                                    <Td className="font-medium text-gray-900">{r.titre}</Td>
                                    <Td><Badge color={PRIORITE[r.priorite]?.color}>{PRIORITE[r.priorite]?.label ?? r.priorite}</Badge></Td>
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