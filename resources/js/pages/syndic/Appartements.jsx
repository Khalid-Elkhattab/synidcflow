import { useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Input, Select, ConfirmButton } from '../../components/ui';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';
import { useAuth } from '../../context/AuthContext';

const statutConfig = {
    vacant: { label: 'Vacant', color: 'default' },
    occupied: { label: 'Occupé', color: 'green' },
    disponible: { label: 'Disponible', color: 'green' },
    occupe: { label: 'Occupé', color: 'green' },
};

export default function SyndicAppartements() {
    const { immeubleId } = useParams();
    const navigate = useNavigate();
    const { user } = useAuth();
    const { items, pagination, loading, error, load, setItems } = usePaginatedList('appartements', (page) =>
        api.get(`/immeubles/${immeubleId}/appartements`, { params: { page } })
    );

    const [modalOpen, setModalOpen] = useState(false);
    const [editing, setEditing] = useState(null);
    const [saving, setSaving] = useState(false);
    const [form, setForm] = useState({ numero: '', etage: 0, superficie: '', statut: 'disponible' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);
    const [actionError, setActionError] = useState(null);

    const [assignOpen, setAssignOpen] = useState(false);
    const [assignTarget, setAssignTarget] = useState(null);
    const [residents, setResidents] = useState([]);
    const [residentId, setResidentId] = useState('');
    const [assignSaving, setAssignSaving] = useState(false);
    const [assignError, setAssignError] = useState(null);

    const openCreate = () => {
        setEditing(null);
        setForm({ numero: '', etage: 0, superficie: '', statut: 'disponible' });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
    };

    const openEdit = (ap) => {
        setEditing(ap);
        setForm({ numero: ap.numero, etage: ap.etage, superficie: ap.superficie ?? '', statut: ap.statut });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
    };

    const openAssign = async (ap) => {
        setAssignTarget(ap);
        setResidentId('');
        setAssignError(null);
        setAssignOpen(true);
        try {
            const res = await api.get('/users', { params: { role: 'resident', per_page: 100 } });
            setResidents(res.data?.data?.users ?? []);
        } catch (err) {
            setAssignError(getErrorMessage(err));
            setResidents([]);
        }
    };

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setFormError(null);
        setFieldErrors({});
        try {
            if (editing) {
                await api.put(`/immeubles/${immeubleId}/appartements/${editing.id}`, form);
            } else {
                await api.post(`/immeubles/${immeubleId}/appartements`, form);
            }
            setModalOpen(false);
            load();
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

    const handleAssign = async () => {
        if (!assignTarget || !residentId) {
            return;
        }
        setAssignSaving(true);
        setAssignError(null);
        try {
            const res = await api.put(`/appartements/${assignTarget.id}/assign`, { resident_id: residentId });
            setItems((prev) => prev.map((a) => (a.id === res.data.data.appartement.id ? res.data.data.appartement : a)));
            setAssignOpen(false);
        } catch (err) {
            setAssignError(getErrorMessage(err));
        } finally {
            setAssignSaving(false);
        }
    };

    const handleDeassign = async (ap) => {
        setActionError(null);
        try {
            const res = await api.delete(`/appartements/${ap.id}/assign`);
            setItems((prev) => prev.map((a) => (a.id === res.data.data.appartement.id ? res.data.data.appartement : a)));
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    const handleDelete = async (id) => {
        setActionError(null);
        try {
            await api.delete(`/immeubles/${immeubleId}/appartements/${id}`);
            load();
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    return (
        <div>
            <PageHeader
                title={`Appartements de l'immeuble #${immeubleId}`}
                subtitle="Gérez les appartements, l'affectation des résidents et les charges"
                actions={<Button onClick={openCreate}>Créer un appartement</Button>}
            />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}
            {actionError && <div className="mb-4"><Alert type="error">{actionError}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucun appartement" /></div>
                ) : (
                    <>
                        <Table headers={['N°', 'Étage', 'Superficie', 'Statut', 'Résident', 'Actions']}>
                            {items.map((ap) => (
                                <tr key={ap.id}>
                                    <Td className="font-medium text-gray-900">{ap.numero}</Td>
                                    <Td>{ap.etage}</Td>
                                    <Td>{ap.superficie ? `${ap.superficie} m²` : '—'}</Td>
                                    <Td><Badge color={statutConfig[ap.statut]?.color}>{statutConfig[ap.statut]?.label ?? ap.statut}</Badge></Td>
                                    <Td>{ap.resident_id ? `#${ap.resident_id}` : '—'}</Td>
                                    <Td>
                                        <div className="flex flex-wrap items-center gap-3">
                                            <button
                                                className="text-sm font-medium text-blue-600 hover:underline"
                                                onClick={() => navigate(`/syndic/appartements/${ap.id}/charges`)}
                                            >
                                                Charges
                                            </button>
                                            {user.role === 'admin' || user.role === 'syndic' ? (
                                                <>
                                                    <button className="text-sm font-medium text-green-600 hover:underline" onClick={() => openAssign(ap)}>
                                                        Assigner résident
                                                    </button>
                                                    {ap.resident_id && (
                                                        <button className="text-sm font-medium text-amber-600 hover:underline" onClick={() => handleDeassign(ap)}>
                                                            Désassigner
                                                        </button>
                                                    )}
                                                </>
                                            ) : null}
                                            <button className="text-sm font-medium text-gray-600 hover:underline" onClick={() => openEdit(ap)}>
                                                Modifier
                                            </button>
                                            <ConfirmButton onConfirm={() => handleDelete(ap.id)} className="text-sm font-medium text-red-600 hover:underline">
                                                Supprimer
                                            </ConfirmButton>
                                        </div>
                                    </Td>
                                </tr>
                            ))}
                        </Table>
                        <Pagination pagination={pagination} onPage={load} loading={loading} />
                    </>
                )}
            </Card>

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? 'Modifier l\'appartement' : 'Créer un appartement'} wide>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    <div className="grid grid-cols-2 gap-4">
                        <Input label="Numéro" name="numero" required value={form.numero} onChange={handleChange} error={fieldErrors.numero?.[0]} />
                        <Input label="Étage" type="number" name="etage" required value={form.etage} onChange={handleChange} error={fieldErrors.etage?.[0]} />
                    </div>
                    <Input label="Superficie (m²)" type="number" step="0.01" min={0} name="superficie" value={form.superficie} onChange={handleChange} error={fieldErrors.superficie?.[0]} />
                    <Select label="Statut" name="statut" value={form.statut} onChange={handleChange} error={fieldErrors.statut?.[0]}>
                        <option value="disponible">Disponible</option>
                        <option value="occupe">Occupé</option>
                        <option value="vacant">Vacant</option>
                    </Select>
                    <div className="flex justify-end gap-2 pt-2">
                        <Button variant="secondary" type="button" onClick={() => setModalOpen(false)}>Annuler</Button>
                        <Button type="submit" loading={saving}>{editing ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </form>
            </Modal>

            <Modal open={assignOpen} onClose={() => setAssignOpen(false)} title="Affecter un résident">
                <div className="space-y-4">
                    {assignError && <Alert type="error">{assignError}</Alert>}
                    <Select label="Résident" value={residentId} onChange={(e) => setResidentId(e.target.value)}>
                        <option value="">Choisir un résident…</option>
                        {residents.map((r) => (
                            <option key={r.id} value={r.id}>{r.name} ({r.email})</option>
                        ))}
                    </Select>
                    <div className="flex justify-end gap-2">
                        <Button variant="secondary" type="button" onClick={() => setAssignOpen(false)}>Annuler</Button>
                        <Button type="button" loading={assignSaving} disabled={!residentId} onClick={handleAssign}>Affecter</Button>
                    </div>
                </div>
            </Modal>
        </div>
    );
}