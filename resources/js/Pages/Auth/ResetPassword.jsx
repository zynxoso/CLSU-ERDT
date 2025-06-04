import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";

export default function ResetPassword({ token, email }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    token: token,
    email: email,
    password: "",
    password_confirmation: "",
  });

  const submit = (e) => {
    e.preventDefault();

    post(route("password.store"), {
      onFinish: () => reset("password", "password_confirmation"),
    });
  };

  return (
    <GuestLayout data-oid="pt:cgk3">
      <Head title="Reset Password" data-oid="zlrc07b" />

      <form onSubmit={submit} data-oid="ckjh._c">
        <div data-oid="cqq069l">
          <InputLabel htmlFor="email" value="Email" data-oid="v-ugb0g" />

          <TextInput
            id="email"
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            onChange={(e) => setData("email", e.target.value)}
            data-oid="1.ngb:y"
          />

          <InputError
            message={errors.email}
            className="mt-2"
            data-oid="vvqln0b"
          />
        </div>

        <div className="mt-4" data-oid="nnzr66i">
          <InputLabel htmlFor="password" value="Password" data-oid="0u7.bs7" />

          <TextInput
            id="password"
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="new-password"
            isFocused={true}
            onChange={(e) => setData("password", e.target.value)}
            data-oid="us3uaq7"
          />

          <InputError
            message={errors.password}
            className="mt-2"
            data-oid="pri4f44"
          />
        </div>

        <div className="mt-4" data-oid="r18:48t">
          <InputLabel
            htmlFor="password_confirmation"
            value="Confirm Password"
            data-oid="notx12-"
          />

          <TextInput
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            value={data.password_confirmation}
            className="mt-1 block w-full"
            autoComplete="new-password"
            onChange={(e) => setData("password_confirmation", e.target.value)}
            data-oid="oiybcgt"
          />

          <InputError
            message={errors.password_confirmation}
            className="mt-2"
            data-oid="ihsf5:h"
          />
        </div>

        <div className="mt-4 flex items-center justify-end" data-oid="u.rs5.b">
          <PrimaryButton
            className="ms-4"
            disabled={processing}
            data-oid="eiqy1.a"
          >
            Reset Password
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
