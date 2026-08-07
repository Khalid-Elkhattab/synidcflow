import { useState } from 'react';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Select } from '../../components/ui';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';
import { formatDate, RECLAMATION_STATUT, PRIORITE, AUDIT_STATUT, AUDIT_DECISION } from '../../lib/labels';

export default function Reclamations({ canManage = false, canAnalyse = false }) {
    const { items, pagination, loading, error, load, setItems } = usePaginatedList('reclamations', (page) =>
        api.get('/reclamations', { params: { page } })
    );

    const [selected, setSelected] = useState(null);
    const [audits, setAudits] = useState([]);
    const [auditsLoading, setAuditsLoading] = useState(false);
    const [notice, setNotice] = useState(null);
    const [analyseError, setAnalyseError] = useState(null);
    const [analyseLoading, setAnalyseLoading] = useState(false);

    const openDetails = async (rec) => {
        setAudits([]);
        setNotice(null);
        setAnalyseError(null);
        const res = await api.get(`/reclamations/${rec.id}`);
        setSelected(res.data?.data?.reclamation ?? rec);
        if (canAnalyse) {
            try {
                setAuditsLoading(true);
                const auditsRes = await api.get(`/reclamations/${rec.id}/audits`);
                setAudits(auditsRes.data?.data?.audits ?? []);
            } catch {
                setNotice('Impossible de charger les audits.');
            } finally {
                setAuditsLoading(false);
            }
        }
    };

    const changeStatus = async (rec, statut) => {
        try {
            const res = await api.put(`/reclamations/${rec.id}`, { statut });
            const updated = res.data?.data?.reclamation;
            setItems((prev) => prev.map((r) => (r.id === updated.id ? updated : r)));
            setSelected(updated);
            setNotice('Statut mis à jour.');
        } catch (err) {
            setNotice(getErrorMessage(err));
        }
    };

    const triggerAnalyse = async () => {
        if (!selected) {
            return;
        }
        setAnalyseLoading(true);
        setAnalyseError(null);
        try {
            await api.post(`/reclamations/${selected.id}/analyser`);
            setAudits([]);
            setNotice('Analyse lancée. Rechargez les audits dans quelques instants.');
        } catch (err) {
            setAnalyseError(getErrorMessage(err));
        } finally {
            setAnalyseLoading(false);
        }
    };

    return (
        <div>
            <PageHeader title="Réclamations" subtitle={canManage ? 'Gestion et traitement des réclamations' : 'Vos réclamations'} />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucune réclamation" /></div>
                ) : (
                    <>
                        <Table headers={['Titre', 'Priorité', 'Statut', 'Déposée le', 'Actions']}>
                            {items.map((r) => (
                                <tr key={r.id} className="hover:bg-gray-50">
                                    <Td className="font-medium text-gray-900">{r.titre}</Td>
                                    <Td><Badge color={PRIORITE[r.priorite]?.color}>{PRIORITE[r.priorite]?.label ?? r.priorite}</Badge></Td>
                                    <Td><Badge color={RECLAMATION_STATUT[r.statut]?.color}>{RECLAMATION_STATUT[r.statut]?.label ?? r.statut}</Badge></Td>
                                    <Td>{formatDate(r.created_at)}</Td>
                                    <Td>
                                        <button className="text-sm font-medium text-blue-600 hover:underline" onClick={() => openDetails(r)}>
                                            Détails
                                        </button>
                                    </Td>
                                </tr>
                            ))}
                        </Table>
                        <Pagination pagination={pagination} onPage={load} loading={loading} />
                    </>
                )}
            </Card>

            <Modal open={!!selected} onClose={() => setSelected(null)} title="Détail de la réclamation" wide>
                {selected && (
                    <div className="space-y-4">
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900">{selected.titre}</h3>
                            <div className="mt-1 flex flex-wrap items-center gap-2">
                                <Badge color={PRIORITE[selected.priorite]?.color}>{PRIORITE[selected.priorite]?.label ?? selected.priorite}</Badge>
                                <Badge color={RECLAMATION_STATUT[selected.statut]?.color}>{RECLAMATION_STATUT[selected.statut]?.label ?? selected.statut}</Badge>
                            </div>
                        </div>
                        <p className="whitespace-pre-wrap text-sm text-gray-600">{selected.description}</p>
                        <p className="text-xs text-gray-400">Déposée le {formatDate(selected.created_at)}</p>

                        {notice && <Alert type="info">{notice}</Alert>}

                        {canManage && (
                            <div className="flex flex-wrap items-end gap-3 border-t border-gray-100 pt-4">
                                <Select
                                    label="Nouveau statut"
                                    value={selected.statut}
                                    onChange={(e) => changeStatus(selected, e.target.value)}
                                >
                                    {Object.entries(RECLAMATION_STATUT).map(([key, v]) => (
                                        <option key={key} value={key}>{v.label}</option>
                                    ))}
                                </Select>
                            </div>
                        )}

                        {canAnalyse && (
                            <div className="space-y-3 border-t border-gray-100 pt-4">
                                <div className="flex items-center justify-between">
                                    <h4 className="text-sm font-semibold text-gray-900">Audits IA ({audits.length})</h4>
                                    <Button variant="success" loading={analyseLoading} onClick={triggerAnalyse}>
                                        {analyseLoading ? 'Analyse…' : 'Lancer une analyse IA'}
                                    </Button>
                                </div>
                                {analyseError && <Alert type="error">{analyseError}</Alert>}
                                {auditsLoading && <Spinner label="Chargement des audits…" />}
                                {!auditsLoading && audits.length === 0 && <p className="text-sm text-gray-500">Aucun audit pour l'instant.</p>}
                                {audits.map((a) => (
                                    <div key={a.id} className="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                        <div className="flex flex-wrap items-center gap-2">
                                            <Badge color={AUDIT_STATUT[a.statut]?.color}>{AUDIT_STATUT[a.statut]?.label ?? a.statut}</Badge>
                                            {a.decision && (
                                                <Badge color={AUDIT_DECISION[a.decision]?.color}>{AUDIT_DECISION[a.decision]?.label ?? a.decision}</Badge>
                                            )}
                                            <span className="text-xs text-gray-400">{a.modele_ia}</span>
                                            <span className="text-xs text-gray-400">{formatDate(a.created_at)}</span>
                                        </div>
                                        {a.resultat && (
                                            typeof a.resultat === 'string' ? (
                                                <p className="mt-2 whitespace-pre-wrap text-sm text-gray-700">{a.resultat}</p>
                                            ) : (
                                                <div className="mt-2 space-y-2 text-sm text-gray-700">
                                                    {a.resultat.justification && <p>{a.resultat.justification}</p>}
                                                    {Array.isArray(a.resultat.points) && a.resultat.points.length > 0 && (
                                                        <ul className="list-inside list-disc space-y-1">
                                                            {a.resultat.points.map((p, idx) => (
                                                                <li key={idx}>{p}</li>
                                                            ))}
                                                        </ul>
                                                    )}
                                                </div>
                                            )
                                        )}
                                        {a.charges_snapshot && (
                                            <details className="mt-2">
                                                <summary className="cursor-pointer text-xs font-medium text-blue-600">Charges analysées ({Object.keys(a.charges_snapshot).length})</summary>
                                                <pre className="mt-1 overflow-x-auto rounded bg-white p-2 text-xs text-gray-600">
                                                    {JSON.stringify(a.charges_snapshot, null, 2)}
                                                </pre>
                                            </details>
                                        )}
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                )}
            </Modal>
        </div>
    );
}