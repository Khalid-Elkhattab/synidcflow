import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';
import { formatDate, AUDIT_STATUT, AUDIT_DECISION } from '../../lib/labels';

export default function Audits() {
    const { items, pagination, loading, error, load } = usePaginatedList('audits', (page) =>
        api.get('/audits', { params: { page } })
    );

    return (
        <div>
            <PageHeader title="Audits IA" subtitle="Historique des analyses de réclamations" />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucun audit" message="Lancez une analyse IA sur une réclamation pour générer un audit." /></div>
                ) : (
                    <>
                        <Table headers={['Réclamation', 'Décision', 'Statut', 'Modèle', 'Créé le']}>
                            {items.map((a) => (
                                <tr key={a.id} className="hover:bg-gray-50">
                                    <Td className="font-medium text-gray-900">#{a.reclamation_id}</Td>
                                    <Td>
                                        {a.decision ? (
                                            <Badge color={AUDIT_DECISION[a.decision]?.color}>{AUDIT_DECISION[a.decision]?.label ?? a.decision}</Badge>
                                        ) : (
                                            <span className="text-sm text-gray-400">—</span>
                                        )}
                                    </Td>
                                    <Td><Badge color={AUDIT_STATUT[a.statut]?.color}>{AUDIT_STATUT[a.statut]?.label ?? a.statut}</Badge></Td>
                                    <Td>{a.modele_ia}</Td>
                                    <Td>{formatDate(a.created_at)}</Td>
                                </tr>
                            ))}
                        </Table>
                        <Pagination pagination={pagination} onPage={load} loading={loading} />
                    </>
                )}
            </Card>
        </div>
    );
}