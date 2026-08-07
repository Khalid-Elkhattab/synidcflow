import { useEffect, useState } from 'react';
import { api } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';
import { formatDate, CHARGE_STATUT } from '../../lib/labels';

export default function ResidentCharges() {
    const [charges, setCharges] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const downloadRecu = async (recu) => {
        try {
            const res = await api.get(`/recus/${recu.id}/download`, { responseType: 'blob' });
            const url = URL.createObjectURL(new Blob([res.data]));
            const link = document.createElement('a');
            link.href = url;
            link.download = recu.nom_original ?? `recu-${recu.id}`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        } catch (err) {
            setError('Impossible de télécharger le reçu.');
        }
    };

    useEffect(() => {
        const load = async () => {
            try {
                setLoading(true);
                const res = await api.get('/residences');
                const residences = res.data?.data?.residences ?? [];
                const all = [];
                for (const r of residences) {
                    const imRes = await api.get(`/residences/${r.id}/immeubles`);
                    const immeubles = imRes.data?.data?.immeubles ?? [];
                    for (const im of immeubles) {
                        const apRes = await api.get(`/immeubles/${im.id}/appartements`);
                        const aps = apRes.data?.data?.appartements ?? [];
                        for (const ap of aps) {
                            const chRes = await api.get(`/appartements/${ap.id}/charges`);
                            const items = chRes.data?.data?.charges ?? [];
                            all.push(...items.map((c) => ({ ...c, appartement: `${r.name} / ${im.name} / ${ap.numero}` })));
                        }
                    }
                }
                setCharges(all);
            } catch (err) {
                setError('Impossible de charger vos charges.');
            } finally {
                setLoading(false);
            }
        };
        load();
    }, []);

    return (
        <div>
            <PageHeader title="Mes charges" subtitle="Charges de vos appartements et reçus de paiement" />
            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : charges.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucune charge" /></div>
                ) : (
                    <Table headers={['Appartement', 'Libellé', 'Montant', 'Échéance', 'Statut', 'Paiement', 'Reçu']}>
                        {charges.map((c) => (
                            <tr key={c.id}>
                                <Td className="font-medium text-gray-900">{c.appartement}</Td>
                                <Td>{c.libelle}</Td>
                                <Td>{Number(c.montant).toLocaleString('fr-FR')} €</Td>
                                <Td>{formatDate(c.date_echeance)}</Td>
                                <Td><Badge color={CHARGE_STATUT[c.statut]?.color}>{CHARGE_STATUT[c.statut]?.label ?? c.statut}</Badge></Td>
                                <Td>{c.date_paiement ? formatDate(c.date_paiement) : '—'}</Td>
                                <Td>
                                    {c.recu ? (
                                        <button
                                            className="text-sm font-medium text-blue-600 hover:underline"
                                            onClick={() => downloadRecu(c.recu)}
                                        >
                                            Télécharger
                                        </button>
                                    ) : (
                                        <span className="text-sm text-gray-400">—</span>
                                    )}
                                </Td>
                            </tr>
                        ))}
                    </Table>
                )}
            </Card>
        </div>
    );
}