import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Register() {
  const { data, setData, post, processing, errors, reset } = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const submit = (e) => {
    e.preventDefault();

    post(route("register"), {
      onFinish: () => reset("password", "password_confirmation"),
    });
  };

  return (
    <GuestLayout data-oid="govhuqo">
      <Head title="Register" data-oid="bdmew_o" />

      <form onSubmit={submit} data-oid="wkf7.sp">
        <div data-oid="z1g0csk">
          <InputLabel htmlFor="name" value="Name" data-oid="zf57hz7" />

          <TextInput
            id="name"
            name="name"
            value={data.name}
            className="mt-1 block w-full"
            autoComplete="name"
            isFocused={true}
            onChange={(e) => setData("name", e.target.value)}
            required
            data-oid="m2od5s4"
          />

          <InputError
            message={errors.name}
            className="mt-2"
            data-oid="a40udzt"
          />
        </div>

        <div className="mt-4" data-oid="-zlg7h.">
          <InputLabel htmlFor="email" value="Email" data-oid="-358gek" />

          <TextInput
            id="email"
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            onChange={(e) => setData("email", e.target.value)}
            required
            data-oid="uq1krmw"
          />

          <InputError
            message={errors.email}
            className="mt-2"
            data-oid="az1v0qh"
          />
        </div>

        <div className="mt-4" data-oid="-._vjow">
          <InputLabel htmlFor="password" value="Password" data-oid="oz84s62" />

          <TextInput
            id="password"
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="new-password"
            onChange={(e) => setData("password", e.target.value)}
            required
            data-oid="3c--649"
          />

          <InputError
            message={errors.password}
            className="mt-2"
            data-oid="ovsko8c"
          />
        </div>

        <div className="mt-4" data-oid="avbcssi">
          <InputLabel
            htmlFor="password_confirmation"
            value="Confirm Password"
            data-oid="k448:ip"
          />

          <TextInput
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            value={data.password_confirmation}
            className="mt-1 block w-full"
            autoComplete="new-password"
            onChange={(e) => setData("password_confirmation", e.target.value)}
            required
            data-oid="naxa72t"
          />

          <InputError
            message={errors.password_confirmation}
            className="mt-2"
            data-oid="j47jcyg"
          />
        </div>

        <div className="mt-4 flex items-center justify-end" data-oid="1agu8wd">
          <Link
            href={route("login")}
            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            data-oid="46iz.yb"
          >
            Already registered?
          </Link>

          <PrimaryButton
            className="ms-4"
            disabled={processing}
            data-oid="mu0y728"
          >
            Register
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
