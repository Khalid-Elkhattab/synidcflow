import { useState } from 'react';
import usePaginatedList, { Pagination } from '../../hooks/usePaginatedList';
import { api, getErrorMessage, getFieldErrors } from '../../api/client';
import { PageHeader, Card, Table, Td } from '../../components/Page';
import { Button, Input, Select } from '../../components/ui';
import { Alert } from '../../components/Alert';
import Badge, { EmptyState } from '../../components/Badge';
import Modal from '../../components/Modal';
import Spinner from '../../components/Spinner';
import { ROLE_LABELS } from '../../context/AuthContext';
import { formatDate } from '../../lib/labels';

const roleColor = {
    admin: 'red',
    syndic: 'blue',
    resident: 'default',
};

export default function Users() {
    const { items, pagination, loading, error, load } = usePaginatedList('users', (page) =>
        api.get('/users', { params: { page } })
    );

    const [modalOpen, setModalOpen] = useState(false);
    const [saving, setSaving] = useState(false);
    const [form, setForm] = useState({ name: '', email: '', password: '', role: 'resident' });
    const [fieldErrors, setFieldErrors] = useState({});
    const [formError, setFormError] = useState(null);

    const openModal = () => {
        setForm({ name: '', email: '', password: '', role: 'resident' });
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
            await api.post('/users', form);
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

    return (
        <div>
            <PageHeader
                title="Utilisateurs"
                subtitle="Gérez les comptes et les rôles (admin, syndic, résident)"
                actions={<Button onClick={openModal}>Créer un utilisateur</Button>}
            />

            {error && <div className="mb-4"><Alert type="error">{error}</Alert></div>}

            <Card>
                {loading ? (
                    <Spinner />
                ) : items.length === 0 ? (
                    <div className="p-6"><EmptyState title="Aucun utilisateur" /></div>
                ) : (
                    <>
                        <Table headers={['Nom', 'Email', 'Rôle', 'Créé le']}>
                            {items.map((u) => (
                                <tr key={u.id}>
                                    <Td className="font-medium text-gray-900">{u.name}</Td>
                                    <Td>{u.email}</Td>
                                    <Td><Badge color={roleColor[u.role]}>{ROLE_LABELS[u.role] ?? u.role}</Badge></Td>
                                    <Td>{formatDate(u.created_at)}</Td>
                                </tr>
                            ))}
                        </Table>
                        <Pagination pagination={pagination} onPage={load} loading={loading} />
                    </>
                )}
            </Card>

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title="Créer un utilisateur">
                <form onSubmit={handleSubmit} className="space-y-4">
                    {formError && <Alert type="error">{formError}</Alert>}
                    <Input
                        label="Nom complet"
                        name="name"
                        required
                        value={form.name}
                        onChange={handleChange}
                        error={fieldErrors.name?.[0]}
                    />
                    <Input
                        label="Email"
                        type="email"
                        name="email"
                        required
                        value={form.email}
                        onChange={handleChange}
                        error={fieldErrors.email?.[0]}
                    />
                    <Input
                        label="Mot de passe"
                        type="password"
                        name="password"
                        required
                        value={form.password}
                        onChange={handleChange}
                        error={fieldErrors.password?.[0]}
                    />
                    <Select
                        label="Rôle"
                        name="role"
                        value={form.role}
                        onChange={handleChange}
                        error={fieldErrors.role?.[0]}
                    >
                        <option value="admin">Administrateur</option>
                        <option value="syndic">Syndic</option>
                        <option value="resident">Résident</option>
                    </Select>
                    <div className="flex justify-end gap-2 pt-2">
                        <Button variant="secondary" type="button" onClick={() => setModalOpen(false)}>Annuler</Button>
                        <Button type="submit" loading={saving}>Créer</Button>
                    </div>
                </form>
            </Modal>
        </div>
    );
}