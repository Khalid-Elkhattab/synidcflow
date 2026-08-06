import { useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Input, ConfirmButton } from '../../components/ui';
import { Alert } from '../../components/Alert';
import { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';

export default function SyndicImmeubles() {
    const { residenceId } = useParams();
    const navigate = useNavigate();
    const { items, pagination, loading, error, load } = usePaginatedList('immeubles', (page) =>
        api.get(`/residences/${residenceId}/immeubles`, { params: { page } })
    );

    const [modalOpen, setModalOpen] = useState(false);
    const [editing, setEditing] = useState(null);
    const [saving, setSaving] = useState(false);
    const [form, setForm] = useState({ name: '', address: '', nombre_etages: 1 });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);
    const [actionError, setActionError] = useState(null);

    const openCreate = () => {
        setEditing(null);
        setForm({ name: '', address: '', nombre_etages: 1 });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
    };

    const openEdit = (im) => {
        setEditing(im);
        setForm({ name: im.name, address: im.address ?? '', nombre_etages: im.nombre_etages });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
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
                await api.put(`/residences/${residenceId}/immeubles/${editing.id}`, form);
            } else {
                await api.post(`/residences/${residenceId}/immeubles`, form);
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

    const handleDelete = async (id) => {
        setActionError(null);
        try {
            await api.delete(`/residences/${residenceId}/immeubles/${id}`);
            load();
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    return (
        <div>
            <PageHeader
                title={`Immeubles de la résidence #${residenceId}`}
                subtitle="Gérez les immeubles et accédez à leurs appartements"
                actions={<Button onClick={openCreate}>Créer un immeuble</Button>}
            />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}
            {actionError && <div className="mb-4"><Alert type="error">{actionError}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucun immeuble" message="Créez votre premier immeuble." /></div>
                ) : (
                    <>
                        <Table headers={['Nom', 'Adresse', 'Étages', 'Actions']}>
                            {items.map((im) => (
                                <tr key={im.id}>
                                    <Td className="font-medium text-gray-900">{im.name}</Td>
                                    <Td>{im.address}</Td>
                                    <Td>{im.nombre_etages}</Td>
                                    <Td>
                                        <div className="flex items-center gap-3">
                                            <button
                                                className="text-sm font-medium text-blue-600 hover:underline"
                                                onClick={() => navigate(`/syndic/immeubles/${im.id}/appartements`)}
                                            >
                                                Appartements
                                            </button>
                                            <button className="text-sm font-medium text-gray-600 hover:underline" onClick={() => openEdit(im)}>
                                                Modifier
                                            </button>
                                            <ConfirmButton onConfirm={() => handleDelete(im.id)} className="text-sm font-medium text-red-600 hover:underline">
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

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? 'Modifier l\'immeuble' : 'Créer un immeuble'} wide>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    <Input label="Nom" name="name" required value={form.name} onChange={handleChange} error={fieldErrors.name?.[0]} />
                    <Input label="Adresse" name="address" value={form.address} onChange={handleChange} error={fieldErrors.address?.[0]} />
                    <Input label="Nombre d'étages" type="number" min={1} name="nombre_etages" required value={form.nombre_etages} onChange={handleChange} error={fieldErrors.nombre_etages?.[0]} />
                    <div className="flex justify-end gap-2 pt-2">
                        <Button variant="secondary" type="button" onClick={() => setModalOpen(false)}>Annuler</Button>
                        <Button type="submit" loading={saving}>{editing ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </form>
            </Modal>
        </div>
    );
}