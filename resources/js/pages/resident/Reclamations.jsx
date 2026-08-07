import { useEffect, useState } from 'react';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card } from '../../components/Page';
import { Button, Select, Input } from '../../components/ui';
import { Alert } from '../../components/Alert';
import Reclamations from '../reclamations/ReclamationsPage';

export default function ResidentReclamations() {
    const [appartements, setAppartements] = useState([]);
    const [loadingList, setLoadingList] = useState(true);
    const [form, setForm] = useState({ appartement_id: '', titre: '', description: '', priorite: 'medium' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        const load = async () => {
            try {
                const res = await api.get('/residences');
                const residences = res.data?.data?.residences ?? [];
                const all = [];
                for (const r of residences) {
                    const imRes = await api.get(`/residences/${r.id}/immeubles`);
                    const immeubles = imRes.data?.data?.immeubles ?? [];
                    for (const im of immeubles) {
                        const apRes = await api.get(`/immeubles/${im.id}/appartements`);
                        const aps = apRes.data?.data?.appartements ?? [];
                        all.push(...aps.map((a) => ({ ...a, label: `${r.name} / ${im.name} / N°${a.numero}` })));
                    }
                }
                setAppartements(all);
            } catch {
                setAppartements([]);
            } finally {
                setLoadingList(false);
            }
        };
        load();
    }, []);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setFormError(null);
        setFieldErrors({});
        try {
            await api.post('/reclamations', form);
            setForm({ appartement_id: '', titre: '', description: '', priorite: 'medium' });
            window.location.reload();
        } catch (err) {
            if (err.response?.status === 422) {
                setFieldErrors(getFieldErrors(err));
            } else {
                setFormError(getErrorMessage(err));
            }
        } finally {
            setSaving(false);
        }
    };

    return (
        <div>
            <PageHeader title="Mes réclamations" subtitle="Déposez et suivez vos réclamations" />

            <Card className="mb-6">
                <h2 className="px-4 pt-4 text-lg font-semibold text-gray-900">Déposer une réclamation</h2>
                <form onSubmit={handleSubmit} className="space-y-4 p-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    {loadingList ? (
                        <p className="text-sm text-gray-500">Chargement de vos appartements…</p>
                    ) : appartements.length === 0 ? (
                        <p className="text-sm text-gray-500">Aucun appartement affecté : vous ne pouvez pas déposer de réclamation.</p>
                    ) : (
                        <>
                            <Select label="Appartement concerné" name="appartement_id" required value={form.appartement_id} onChange={handleChange} error={fieldErrors.appartement_id?.[0]}>
                                <option value="">Choisir un appartement…</option>
                                {appartements.map((a) => (
                                    <option key={a.id} value={a.id}>{a.label}</option>
                                ))}
                            </Select>
                            <Input label="Titre" name="titre" required value={form.titre} onChange={handleChange} error={fieldErrors.titre?.[0]} />
                            <label className="block">
                                <span className="mb-1 block text-sm font-medium text-gray-700">Description</span>
                                <textarea
                                    name="description"
                                    rows={4}
                                    required
                                    value={form.description}
                                    onChange={handleChange}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                />
                                {fieldErrors.description && <p className="mt-1 text-xs text-red-600">{fieldErrors.description[0]}</p>}
                            </label>
                            <Select label="Priorité" name="priorite" value={form.priorite} onChange={handleChange} error={fieldErrors.priorite?.[0]}>
                                <option value="low">Basse</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Haute</option>
                                <option value="urgent">Urgente</option>
                            </Select>
                            <Button type="submit" loading={saving}>Déposer</Button>
                        </>
                    )}
                </form>
            </Card>

            <Reclamations />
        </div>
    );
}