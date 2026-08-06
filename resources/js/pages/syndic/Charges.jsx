import { useState } from 'react';
import { useParams } from 'react-router-dom';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Input, ConfirmButton } from '../../components/ui';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';
import { formatDate, CHARGE_STATUT } from '../../lib/labels';
import { useAuth } from '../../context/AuthContext';

function InputFile({ label, onChange, accept, required }) {
    return (
        <label className="block">
            {label && <span className="mb-1 block text-sm font-medium text-gray-700">{label}</span>}
            <input
                type="file"
                accept={accept}
                required={required}
                onChange={onChange}
                className="w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-blue-600 hover:file:bg-blue-100"
            />
        </label>
    );
}

export default function SyndicCharges() {
    const { appartementId } = useParams();
    const { user } = useAuth();
    const canManage = user.role === 'admin' || user.role === 'syndic';
    const { items, pagination, loading, error, load, setItems } = usePaginatedList('charges', (page) =>
        api.get(`/appartements/${appartementId}/charges`, { params: { page } })
    );

    const [modalOpen, setModalOpen] = useState(false);
    const [editing, setEditing] = useState(null);
    const [saving, setSaving] = useState(false);
    const [form, setForm] = useState({ libelle: '', description: '', montant: '', date_echeance: '', periode: '' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);
    const [actionError, setActionError] = useState(null);

    const [recuOpen, setRecuOpen] = useState(false);
    const [recuCharge, setRecuCharge] = useState(null);
    const [recuForm, setRecuForm] = useState({ fichier: null, date_paiement: '', montant_paye: '' });
    const [recuSaving, setRecuSaving] = useState(false);
    const [recuError, setRecuError] = useState(null);

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
            setActionError('Impossible de télécharger le reçu.');
        }
    };

    const openCreate = () => {
        setEditing(null);
        setForm({ libelle: '', description: '', montant: '', date_echeance: '', periode: '' });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
    };

    const openEdit = (charge) => {
        setEditing(charge);
        setForm({
            libelle: charge.libelle,
            description: charge.description ?? '',
            montant: charge.montant,
            date_echeance: charge.date_echeance ?? '',
            periode: charge.periode ?? '',
        });
        setFieldErrors({});
        setFormError(null);
        setModalOpen(true);
    };

    const openRecu = (charge) => {
        setRecuCharge(charge);
        setRecuForm({ fichier: null, date_paiement: '', montant_paye: '' });
        setRecuError(null);
        setRecuOpen(true);
    };

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleRecuChange = (e) => {
        const { name, type, value } = e.target;
        setRecuForm({ ...recuForm, [name]: type === 'file' ? e.target.files[0] : value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setFormError(null);
        setFieldErrors({});
        try {
            if (editing) {
                await api.put(`/appartements/${appartementId}/charges/${editing.id}`, form);
            } else {
                await api.post(`/appartements/${appartementId}/charges`, form);
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

    const handleRecuSubmit = async () => {
        if (!recuCharge) {
            return;
        }
        setRecuSaving(true);
        setRecuError(null);
        try {
            const fd = new FormData();
            if (recuForm.fichier) {
                fd.append('fichier', recuForm.fichier);
            }
            fd.append('date_paiement', recuForm.date_paiement || new Date().toISOString().slice(0, 10));
            fd.append('montant_paye', recuForm.montant_paye);
            const res = await api.post(`/charges/${recuCharge.id}/recus`, fd);
            setItems((prev) => prev.map((c) => (c.id === recuCharge.id ? { ...c, recu: res.data.data.recu } : c)));
            setRecuOpen(false);
        } catch (err) {
            setRecuError(getErrorMessage(err));
        } finally {
            setRecuSaving(false);
        }
    };

    const handleMarkPaid = async (charge) => {
        setActionError(null);
        try {
            const res = await api.patch(`/charges/${charge.id}/payer`);
            setItems((prev) => prev.map((c) => (c.id === res.data.data.charge.id ? res.data.data.charge : c)));
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    const handleDeleteRecu = async (charge, recuId) => {
        setActionError(null);
        try {
            await api.delete(`/recus/${recuId}`);
            setItems((prev) => prev.map((c) => (c.id === charge.id ? { ...c, recu: null } : c)));
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    const handleDeleteCharge = async (id) => {
        setActionError(null);
        try {
            await api.delete(`/appartements/${appartementId}/charges/${id}`);
            load();
        } catch (err) {
            setActionError(getErrorMessage(err));
        }
    };

    return (
        <div>
            <PageHeader
                title={`Charges de l'appartement #${appartementId}`}
                subtitle="Gérez les charges, les paiements et les reçus"
                actions={canManage && <Button onClick={openCreate}>Créer une charge</Button>}
            />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}
            {actionError && <div className="mb-4"><Alert type="error">{actionError}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucune charge" /></div>
                ) : (
                    <>
                        <Table headers={['Libellé', 'Montant', 'Échéance', 'Période', 'Statut', 'Reçu', 'Actions']}>
                            {items.map((charge) => (
                                <tr key={charge.id}>
                                    <Td className="font-medium text-gray-900">{charge.libelle}</Td>
                                    <Td>{Number(charge.montant).toLocaleString('fr-FR')} €</Td>
                                    <Td>{formatDate(charge.date_echeance)}</Td>
                                    <Td>{charge.periode}</Td>
                                    <Td><Badge color={CHARGE_STATUT[charge.statut]?.color}>{CHARGE_STATUT[charge.statut]?.label ?? charge.statut}</Badge></Td>
                                    <Td>
                                        {charge.recu ? (
                                            <div className="flex items-center gap-2">
                                                <button
                                                    className="text-sm font-medium text-blue-600 hover:underline"
                                                    onClick={() => downloadRecu(charge.recu)}
                                                >
                                                    Télécharger
                                                </button>
                                                {canManage && (
                                                    <ConfirmButton onConfirm={() => handleDeleteRecu(charge, charge.recu.id)} className="text-xs font-medium text-red-600 hover:underline">
                                                        Retirer
                                                    </ConfirmButton>
                                                )}
                                            </div>
                                        ) : (
                                            <span className="text-sm text-gray-400">—</span>
                                        )}
                                    </Td>
                                    <Td>
                                        {canManage && (
                                            <div className="flex flex-wrap items-center gap-3">
                                                <button className="text-sm font-medium text-gray-600 hover:underline" onClick={() => openEdit(charge)}>
                                                    Modifier
                                                </button>
                                                {charge.statut !== 'paid' && (
                                                    <button className="text-sm font-medium text-green-600 hover:underline" onClick={() => handleMarkPaid(charge)}>
                                                        Marquer payée
                                                    </button>
                                                )}
                                                {charge.statut === 'paid' && (
                                                    <button className="text-sm font-medium text-blue-600 hover:underline" onClick={() => openRecu(charge)}>
                                                        Joindre un reçu
                                                    </button>
                                                )}
                                                <ConfirmButton onConfirm={() => handleDeleteCharge(charge.id)} className="text-sm font-medium text-red-600 hover:underline">
                                                    Supprimer
                                                </ConfirmButton>
                                            </div>
                                        )}
                                    </Td>
                                </tr>
                            ))}
                        </Table>
                        <Pagination pagination={pagination} onPage={load} loading={loading} />
                    </>
                )}
            </Card>

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? 'Modifier la charge' : 'Créer une charge'} wide>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    <Input label="Libellé" name="libelle" required value={form.libelle} onChange={handleChange} error={fieldErrors.libelle?.[0]} />
                    <Input label="Description" name="description" value={form.description} onChange={handleChange} error={fieldErrors.description?.[0]} />
                    <div className="grid grid-cols-2 gap-4">
                        <Input label="Montant (€)" type="number" step="0.01" min={0} name="montant" required value={form.montant} onChange={handleChange} error={fieldErrors.montant?.[0]} />
                        <Input label="Date d'échéance" type="date" name="date_echeance" required value={form.date_echeance} onChange={handleChange} error={fieldErrors.date_echeance?.[0]} />
                    </div>
                    <Input label="Période (ex. Janvier 2026)" name="periode" placeholder="Janvier 2026" value={form.periode} onChange={handleChange} error={fieldErrors.periode?.[0]} />
                    <div className="flex justify-end gap-2 pt-2">
                        <Button variant="secondary" type="button" onClick={() => setModalOpen(false)}>Annuler</Button>
                        <Button type="submit" loading={saving}>{editing ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </form>
            </Modal>

            <Modal open={recuOpen} onClose={() => setRecuOpen(false)} title="Ajouter un reçu">
                <div className="space-y-4">
                    {recuError && <Alert type="error">{recuError}</Alert>}
                    <InputFile label="Fichier (JPG/PNG, max 2 Mo)" accept="image/*" required onChange={handleRecuChange} />
                    <div className="grid grid-cols-2 gap-4">
                        <Input label="Date de paiement" type="date" name="date_paiement" value={recuForm.date_paiement} onChange={handleRecuChange} />
                        <Input label="Montant payé (€)" type="number" step="0.01" min={0} name="montant_paye" value={recuForm.montant_paye} onChange={handleRecuChange} />
                    </div>
                    <div className="flex justify-end gap-2">
                        <Button variant="secondary" type="button" onClick={() => setRecuOpen(false)}>Annuler</Button>
                        <Button type="button" loading={recuSaving} onClick={handleRecuSubmit}>Téléverser</Button>
                    </div>
                </div>
            </Modal>
        </div>
    );
}