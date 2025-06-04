import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";

export default function ForgotPassword({ status }) {
  const { data, setData, post, processing, errors } = useForm({
    email: "",
  });

  const submit = (e) => {
    e.preventDefault();

    post(route("password.email"));
  };

  return (
    <GuestLayout data-oid="cqth-kd">
      <Head title="Forgot Password" data-oid="b_0yg71" />

      <div
        className="mb-4 text-sm text-gray-600 dark:text-gray-400"
        data-oid="c6mh42o"
      >
        Forgot your password? No problem. Just let us know your email address
        and we will email you a password reset link that will allow you to
        choose a new one.
      </div>

      {status && (
        <div
          className="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
          data-oid="gbtpuv:"
        >
          {status}
        </div>
      )}

      <form onSubmit={submit} data-oid="05bok6x">
        <TextInput
          id="email"
          type="email"
          name="email"
          value={data.email}
          className="mt-1 block w-full"
          isFocused={true}
          onChange={(e) => setData("email", e.target.value)}
          data-oid="nuxzioo"
        />

        <InputError
          message={errors.email}
          className="mt-2"
          data-oid="lx1bd-5"
        />

        <div className="mt-4 flex items-center justify-end" data-oid="-86nr1a">
          <PrimaryButton
            className="ms-4"
            disabled={processing}
            data-oid=".npgw_l"
          >
            Email Password Reset Link
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
