import AdminLayout from "@/Layouts/AdminLayout";
import { Route, Routes } from "react-router-dom";
import Analytics from "./Analytics";
import AuditLogs from "./AuditLogs/Index";
import Dashboard from "./Dashboard";
import Documents from "./Documents/Index";
import DocumentShow from "./Documents/Show";
import FundRequests from "./FundRequests/Index";
import FundRequestShow from "./FundRequests/Show";
import Manuscripts from "./Manuscripts/Index";
import ManuscriptShow from "./Manuscripts/Show";
import Profile from "./Profile";
import Reports from "./Reports/Index";
import ScholarCreate from "./Scholars/Create";
import ScholarEdit from "./Scholars/Edit";
import Scholars from "./Scholars/Index";
import ScholarShow from "./Scholars/Show";
import Settings from "./Settings/Index";

export default function AdminRoutes() {
  return (
    <Routes data-oid="7hh475e">
      <Route
        path="/"
        element={<AdminLayout data-oid="2.-yhqv" />}
        data-oid="ly_c.m9"
      >
        <Route
          index
          element={<Dashboard data-oid="9jq-8cx" />}
          data-oid="uhf1og."
        />

        <Route
          path="analytics"
          element={<Analytics data-oid="aoyagvv" />}
          data-oid="z0e:4ic"
        />

        {/* Scholar Routes */}
        <Route path="scholars" data-oid="sopu4u_">
          <Route
            index
            element={<Scholars data-oid="5p46-3q" />}
            data-oid="-mtopir"
          />

          <Route
            path="create"
            element={<ScholarCreate data-oid="sepn4.2" />}
            data-oid="xh-_303"
          />

          <Route
            path=":id"
            element={<ScholarShow data-oid="v8fr4hv" />}
            data-oid="ty_-lj9"
          />

          <Route
            path=":id/edit"
            element={<ScholarEdit data-oid="0fho_m-" />}
            data-oid="e0bv7-q"
          />
        </Route>

        {/* Fund Request Routes */}
        <Route path="fund-requests" data-oid="nyfzp9-">
          <Route
            index
            element={<FundRequests data-oid="d3y_dbm" />}
            data-oid="1u1n1i9"
          />

          <Route
            path=":id"
            element={<FundRequestShow data-oid="o8o:-h7" />}
            data-oid="x5wsb52"
          />
        </Route>

        {/* Document Routes */}
        <Route path="documents" data-oid="3l8qb8g">
          <Route
            index
            element={<Documents data-oid="3hb-awg" />}
            data-oid="4zz0l99"
          />

          <Route
            path=":id"
            element={<DocumentShow data-oid="6i::kd7" />}
            data-oid="8li7y1z"
          />
        </Route>

        {/* Manuscript Routes */}
        <Route path="manuscripts" data-oid="6189kwd">
          <Route
            index
            element={<Manuscripts data-oid="hgc_sa8" />}
            data-oid="v_lgf8d"
          />

          <Route
            path=":id"
            element={<ManuscriptShow data-oid="3xtznmr" />}
            data-oid="ecr.03c"
          />
        </Route>

        {/* Report Routes */}
        <Route path="reports" data-oid="5w698xu">
          <Route
            index
            element={<Reports data-oid="o57z0mp" />}
            data-oid=".vks2zb"
          />
        </Route>

        {/* Audit Log Routes */}
        <Route path="audit-logs" data-oid="1:ip7xw">
          <Route
            index
            element={<AuditLogs data-oid="yshu832" />}
            data-oid="u3w._.2"
          />
        </Route>

        {/* Profile and Settings Routes */}
        <Route
          path="profile"
          element={<Profile data-oid="5us..vm" />}
          data-oid="s-._uz-"
        />

        <Route
          path="settings"
          element={<Settings data-oid="88_n45r" />}
          data-oid="5n5tw3a"
        />
      </Route>
    </Routes>
  );
}
