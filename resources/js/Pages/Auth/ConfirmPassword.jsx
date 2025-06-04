import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";

export default function ConfirmPassword() {
  const { data, setData, post, processing, errors, reset } = useForm({
    password: "",
  });

  const submit = (e) => {
    e.preventDefault();

    post(route("password.confirm"), {
      onFinish: () => reset("password"),
    });
  };

  return (
    <GuestLayout data-oid="waqht_z">
      <Head title="Confirm Password" data-oid="545min-" />

      <div
        className="mb-4 text-sm text-gray-600 dark:text-gray-400"
        data-oid="e2npi5w"
      >
        This is a secure area of the application. Please confirm your password
        before continuing.
      </div>

      <form onSubmit={submit} data-oid="s:f48iu">
        <div className="mt-4" data-oid="6_7w_08">
          <InputLabel htmlFor="password" value="Password" data-oid="hzuo8du" />

          <TextInput
            id="password"
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            isFocused={true}
            onChange={(e) => setData("password", e.target.value)}
            data-oid="dp5ky:7"
          />

          <InputError
            message={errors.password}
            className="mt-2"
            data-oid="xwk3h4v"
          />
        </div>

        <div className="mt-4 flex items-center justify-end" data-oid="cc0mcs9">
          <PrimaryButton
            className="ms-4"
            disabled={processing}
            data-oid="7.cxf4e"
          >
            Confirm
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
