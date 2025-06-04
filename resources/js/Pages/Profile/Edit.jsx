import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import DeleteUserForm from "./Partials/DeleteUserForm";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm";

export default function Edit({ mustVerifyEmail, status }) {
  return (
    <AuthenticatedLayout
      header={
        <h2
          className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
          data-oid="ifc432w"
        >
          Profile
        </h2>
      }
      data-oid="x8.nhnl"
    >
      <Head title="Profile" data-oid="fvw-1dt" />

      <div className="py-12" data-oid="4wq1tww">
        <div
          className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8"
          data-oid="4a06k.q"
        >
          <div
            className="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
            data-oid="3k8wcxb"
          >
            <UpdateProfileInformationForm
              mustVerifyEmail={mustVerifyEmail}
              status={status}
              className="max-w-xl"
              data-oid="4sq7_lw"
            />
          </div>

          <div
            className="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
            data-oid="igmioa7"
          >
            <UpdatePasswordForm className="max-w-xl" data-oid="ait1baq" />
          </div>

          <div
            className="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
            data-oid="on986oi"
          >
            <DeleteUserForm className="max-w-xl" data-oid="g3xfqll" />
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
