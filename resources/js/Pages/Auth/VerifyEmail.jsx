import PrimaryButton from "@/Components/PrimaryButton";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";

export default function VerifyEmail({ status }) {
  const { post, processing } = useForm({});

  const submit = (e) => {
    e.preventDefault();

    post(route("verification.send"));
  };

  return (
    <GuestLayout data-oid="i1sqpim">
      <Head title="Email Verification" data-oid="zko1m.r" />

      <div
        className="mb-4 text-sm text-gray-600 dark:text-gray-400"
        data-oid="xrb7k.t"
      >
        Thanks for signing up! Before getting started, could you verify your
        email address by clicking on the link we just emailed to you? If you
        didn't receive the email, we will gladly send you another.
      </div>

      {status === "verification-link-sent" && (
        <div
          className="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
          data-oid="b630-sl"
        >
          A new verification link has been sent to the email address you
          provided during registration.
        </div>
      )}

      <form onSubmit={submit} data-oid="0:7:wsk">
        <div
          className="mt-4 flex items-center justify-between"
          data-oid="pr4z2:z"
        >
          <PrimaryButton disabled={processing} data-oid="5fg4aa-">
            Resend Verification Email
          </PrimaryButton>

          <Link
            href={route("logout")}
            method="post"
            as="button"
            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            data-oid="7a40qes"
          >
            Log Out
          </Link>
        </div>
      </form>
    </GuestLayout>
  );
}
