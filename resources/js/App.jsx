import { Navigate, Route, Routes } from 'react-router-dom';
import { RequireAuth, RequireGuest, RequireRole, homeFor } from './components/guards';
import { useAuth } from './context/AuthContext';
import Layout from './components/Layout';
import Login from './pages/auth/Login';
import Register from './pages/auth/Register';
import NotFound from './pages/NotFound';

import AdminDashboard from './pages/admin/Dashboard';
import AdminResidences from './pages/admin/Residences';
import AdminUsers from './pages/admin/Users';
import AdminReclamations from './pages/admin/Reclamations';
import AdminAudits from './pages/admin/Audits';

import SyndicDashboard from './pages/syndic/Dashboard';
import SyndicResidences from './pages/syndic/Residences';
import SyndicImmeubles from './pages/syndic/Immeubles';
import SyndicAppartements from './pages/syndic/Appartements';
import SyndicCharges from './pages/syndic/Charges';
import SyndicReclamations from './pages/syndic/Reclamations';
import SyndicAudits from './pages/syndic/Audits';

import ResidentDashboard from './pages/resident/Dashboard';
import ResidentAppartements from './pages/resident/Appartements';
import ResidentCharges from './pages/resident/Charges';
import ResidentReclamations from './pages/resident/Reclamations';

const ROLES_WRAP = {
    admin: ['admin'],
    syndic: ['admin', 'syndic'],
    resident: ['resident'],
};

export default function App() {
    return (
        <Routes>
            <Route path="/login" element={<RequireGuest><Login /></RequireGuest>} />
            <Route path="/register" element={<RequireGuest><Register /></RequireGuest>} />

            <Route element={<RequireAuth><Layout /></RequireAuth>}>
                <Route path="/admin" element={<RequireRole roles={ROLES_WRAP.admin}><AdminDashboard /></RequireRole>} />
                <Route path="/admin/residences" element={<RequireRole roles={ROLES_WRAP.admin}><AdminResidences /></RequireRole>} />
                <Route path="/admin/users" element={<RequireRole roles={ROLES_WRAP.admin}><AdminUsers /></RequireRole>} />
                <Route path="/admin/reclamations" element={<RequireRole roles={ROLES_WRAP.admin}><AdminReclamations /></RequireRole>} />
                <Route path="/admin/audits" element={<RequireRole roles={ROLES_WRAP.admin}><AdminAudits /></RequireRole>} />

                <Route path="/syndic" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicDashboard /></RequireRole>} />
                <Route path="/syndic/residences" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicResidences /></RequireRole>} />
                <Route path="/syndic/residences/:residenceId/immeubles" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicImmeubles /></RequireRole>} />
                <Route path="/syndic/immeubles/:immeubleId/appartements" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicAppartements /></RequireRole>} />
                <Route path="/syndic/appartements/:appartementId/charges" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicCharges /></RequireRole>} />
                <Route path="/syndic/reclamations" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicReclamations /></RequireRole>} />
                <Route path="/syndic/audits" element={<RequireRole roles={ROLES_WRAP.syndic}><SyndicAudits /></RequireRole>} />

                <Route path="/resident" element={<RequireRole roles={ROLES_WRAP.resident}><ResidentDashboard /></RequireRole>} />
                <Route path="/resident/appartements" element={<RequireRole roles={ROLES_WRAP.resident}><ResidentAppartements /></RequireRole>} />
                <Route path="/resident/charges" element={<RequireRole roles={ROLES_WRAP.resident}><ResidentCharges /></RequireRole>} />
                <Route path="/resident/reclamations" element={<RequireRole roles={ROLES_WRAP.resident}><ResidentReclamations /></RequireRole>} />
            </Route>

            <Route path="/" element={<RootRedirect />} />
            <Route path="*" element={<NotFound />} />
        </Routes>
    );
}

function RootRedirect() {
    // Handled client-side after auth loads (protected via Layout). If guest, go to login.
    const { user } = useAuth();
    return <Navigate to={user ? homeFor(user.role) : '/login'} replace />;
}