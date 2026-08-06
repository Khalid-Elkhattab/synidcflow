import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Input, ConfirmButton } from '../../components/ui';
import { Alert } from '../../components/Alert';
import { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';
import { formatDate } from '../../lib/labels';

export default function SyndicResidences() {
    const navigate = useNavigate();
    const { items, pagination, loading, error, load } = usePaginatedList('residences', (page) =>
        api.get('/residences', { params: { page } })
    );

    const [modalOpen, setModalOpen] = useState(false);
    const [saving, setSaving] = useState(false);
    const [form, setForm] = useState({ name: '', address: '', city: '', postal_code: '', description: '' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);
    const [actionError, setActionError] = useState(null);

    const openModal = () => {
        setForm({ name: '', address: '', city: '', postal_code: '', description: '' });
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
            await api.post('/residences', form);
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
            await api.delete(`/residences/${id}`);
            load();
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    return (
        <div>
            <PageHeader
                title="Mes résidences"
                subtitle="Les résidences dont vous êtes gestionnaire"
                actions={<Button onClick={openModal}>Créer une résidence</Button>}
            />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}
            {actionError && <div className="mb-4"><Alert type="error">{actionError}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucune résidence" message="Créez votre première résidence." /></div>
                ) : (
                    <>
                        <Table headers={['Nom', 'Adresse', 'Ville', 'Code postal', 'Actions']}>
                            {items.map((r) => (
                                <tr key={r.id}>
                                    <Td className="font-medium text-gray-900">{r.name}</Td>
                                    <Td>{r.address}</Td>
                                    <Td>{r.city}</Td>
                                    <Td>{r.postal_code}</Td>
                                    <Td>
                                        <div className="flex items-center gap-3">
                                            <button
                                                className="text-sm font-medium text-blue-600 hover:underline"
                                                onClick={() => navigate(`/syndic/residences/${r.id}/immeubles`)}
                                            >
                                                Immeubles
                                            </button>
                                            <ConfirmButton onConfirm={() => handleDelete(r.id)} className="text-sm font-medium text-red-600 hover:underline">
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

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title="Créer une résidence" wide>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    <Input label="Nom" name="name" required value={form.name} onChange={handleChange} error={fieldErrors.name?.[0]} />
                    <Input label="Adresse" name="address" required value={form.address} onChange={handleChange} error={fieldErrors.address?.[0]} />
                    <div className="grid grid-cols-2 gap-4">
                        <Input label="Ville" name="city" required value={form.city} onChange={handleChange} error={fieldErrors.city?.[0]} />
                        <Input label="Code postal" name="postal_code" required value={form.postal_code} onChange={handleChange} error={fieldErrors.postal_code?.[0]} />
                    </div>
                    <Input label="Description" name="description" value={form.description} onChange={handleChange} error={fieldErrors.description?.[0]} />
                    <div className="flex justify-end gap-2 pt-2">
                        <Button variant="secondary" type="button" onClick={() => setModalOpen(false)}>Annuler</Button>
                        <Button type="submit" loading={saving}>Créer</Button>
                    </div>
                </form>
            </Modal>
        </div>
    );
}