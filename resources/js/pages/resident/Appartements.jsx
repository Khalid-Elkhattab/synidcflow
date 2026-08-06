import { useEffect, useState } from 'react';
import { api } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Alert } from '../../components/Alert';
import { EmptyState } from '../../components/Badge';
import Spinner from '../../components/Spinner';

export default function ResidentAppartements() {
    const [appartements, setAppartements] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const load = async () => {
            try {
                setLoading(true);
                const res = await api.get('/residences');
                const residences = res.data?.data?.residences ?? [];
                const results = [];
                for (const r of residences) {
                    const imRes = await api.get(`/residences/${r.id}/immeubles`);
                    const immeubles = imRes.data?.data?.immeubles ?? [];
                    for (const im of immeubles) {
                        const apRes = await api.get(`/immeubles/${im.id}/appartements`);
                        const aps = apRes.data?.data?.appartements ?? [];
                        results.push(...aps.map((a) => ({ ...a, immeuble: im.name, residence: r.name })));
                    }
                }
                setAppartements(results);
            } catch (err) {
                setError('Impossible de charger vos appartements.');
            } finally {
                setLoading(false);
            }
        };
        load();
    }, []);

    return (
        <div>
            <PageHeader title="Mes appartements" subtitle="Les appartements qui vous sont affectés" />
            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : appartements.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucun appartement" message="Aucun appartement ne vous est affecté pour le moment." /></div>
                ) : (
                    <Table headers={['Résidence', 'Immeuble', 'N°', 'Étage', 'Superficie', 'Statut']}>
                        {appartements.map((a) => (
                            <tr key={a.id}>
                                <Td className="font-medium text-gray-900">{a.residence}</Td>
                                <Td>{a.immeuble}</Td>
                                <Td>{a.numero}</Td>
                                <Td>{a.etage}</Td>
                                <Td>{a.superficie ? `${a.superficie} m²` : '—'}</Td>
                                <Td>{a.statut === 'occupied' ? 'Occupé' : a.statut === 'vacant' ? 'Vacant' : a.statut}</Td>
                            </tr>
                        ))}
                    </Table>
                )}
            </Card>
        </div>
    );
}