import Checkbox from "@/Components/Checkbox";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Login({ status, canResetPassword }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: "",
    password: "",
    remember: false,
  });

  const submit = (e) => {
    e.preventDefault();

    post(route("login"), {
      onFinish: () => reset("password"),
    });
  };

  return (
    <GuestLayout data-oid="usxqpz9">
      <Head title="Log in" data-oid="q4l6ior" />

      {status && (
        <div
          className="mb-4 text-sm font-medium text-green-600"
          data-oid="jli5yzt"
        >
          {status}
        </div>
      )}

      <form onSubmit={submit} data-oid="0kcx::g">
        <div data-oid="9_.1yxs">
          <InputLabel htmlFor="email" value="Email" data-oid="aa891ff" />

          <TextInput
            id="email"
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            isFocused={true}
            onChange={(e) => setData("email", e.target.value)}
            data-oid="2q-qrxg"
          />

          <InputError
            message={errors.email}
            className="mt-2"
            data-oid="hmjymku"
          />
        </div>

        <div className="mt-4" data-oid="tnwqsbl">
          <InputLabel htmlFor="password" value="Password" data-oid="mj1zifc" />

          <TextInput
            id="password"
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="current-password"
            onChange={(e) => setData("password", e.target.value)}
            data-oid="6j9v.vn"
          />

          <InputError
            message={errors.password}
            className="mt-2"
            data-oid="wvj4o3h"
          />
        </div>

        <div className="mt-4 block" data-oid="-5q_.ny">
          <label className="flex items-center" data-oid="r1q1-0z">
            <Checkbox
              name="remember"
              checked={data.remember}
              onChange={(e) => setData("remember", e.target.checked)}
              data-oid="srw65fd"
            />

            <span
              className="ms-2 text-sm text-gray-600 dark:text-gray-400"
              data-oid="cmd00ok"
            >
              Remember me
            </span>
          </label>
        </div>

        <div className="mt-4 flex items-center justify-end" data-oid="hech76f">
          {canResetPassword && (
            <Link
              href={route("password.request")}
              className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
              data-oid="k6dlmwh"
            >
              Forgot your password?
            </Link>
          )}

          <PrimaryButton
            className="ms-4"
            disabled={processing}
            data-oid=":qqdjyl"
          >
            Log in
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
