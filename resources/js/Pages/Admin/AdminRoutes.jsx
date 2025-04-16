import AdminLayout from '@/Layouts/AdminLayout';
import { Route, Routes } from 'react-router-dom';
import Analytics from './Analytics';
import AuditLogs from './AuditLogs/Index';
import Dashboard from './Dashboard';
import Documents from './Documents/Index';
import DocumentShow from './Documents/Show';
import FundRequests from './FundRequests/Index';
import FundRequestShow from './FundRequests/Show';
import Manuscripts from './Manuscripts/Index';
import ManuscriptShow from './Manuscripts/Show';
import Profile from './Profile';
import Reports from './Reports/Index';
import ScholarCreate from './Scholars/Create';
import ScholarEdit from './Scholars/Edit';
import Scholars from './Scholars/Index';
import ScholarShow from './Scholars/Show';
import Settings from './Settings/Index';

export default function AdminRoutes() {
    return (
        <Routes>
            <Route path="/" element={<AdminLayout />}>
                <Route index element={<Dashboard />} />
                <Route path="analytics" element={<Analytics />} />

                {/* Scholar Routes */}
                <Route path="scholars">
                    <Route index element={<Scholars />} />
                    <Route path="create" element={<ScholarCreate />} />
                    <Route path=":id" element={<ScholarShow />} />
                    <Route path=":id/edit" element={<ScholarEdit />} />
                </Route>

                {/* Fund Request Routes */}
                <Route path="fund-requests">
                    <Route index element={<FundRequests />} />
                    <Route path=":id" element={<FundRequestShow />} />
                </Route>

                {/* Document Routes */}
                <Route path="documents">
                    <Route index element={<Documents />} />
                    <Route path=":id" element={<DocumentShow />} />
                </Route>

                {/* Manuscript Routes */}
                <Route path="manuscripts">
                    <Route index element={<Manuscripts />} />
                    <Route path=":id" element={<ManuscriptShow />} />
                </Route>

                {/* Report Routes */}
                <Route path="reports">
                    <Route index element={<Reports />} />
                </Route>

                {/* Audit Log Routes */}
                <Route path="audit-logs">
                    <Route index element={<AuditLogs />} />
                </Route>

                {/* Profile and Settings Routes */}
                <Route path="profile" element={<Profile />} />
                <Route path="settings" element={<Settings />} />
            </Route>
        </Routes>
    );
}
