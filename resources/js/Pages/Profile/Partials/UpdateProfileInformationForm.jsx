import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Transition } from "@headlessui/react";
import { Link, useForm, usePage } from "@inertiajs/react";

export default function UpdateProfileInformation({
  mustVerifyEmail,
  status,
  className = "",
}) {
  const user = usePage().props.auth.user;

  const { data, setData, patch, errors, processing, recentlySuccessful } =
    useForm({
      name: user.name,
      email: user.email,
    });

  const submit = (e) => {
    e.preventDefault();

    patch(route("profile.update"));
  };

  return (
    <section className={className} data-oid="uyhhhtk">
      <header data-oid="triosib">
        <h2
          className="text-lg font-medium text-gray-900 dark:text-gray-100"
          data-oid=".ekani3"
        >
          Profile Information
        </h2>

        <p
          className="mt-1 text-sm text-gray-600 dark:text-gray-400"
          data-oid="dfb1:x8"
        >
          Update your account's profile information and email address.
        </p>
      </header>

      <form onSubmit={submit} className="mt-6 space-y-6" data-oid="-o5r3um">
        <div data-oid="yo__9ll">
          <InputLabel htmlFor="name" value="Name" data-oid="4m-jpo." />

          <TextInput
            id="name"
            className="mt-1 block w-full"
            value={data.name}
            onChange={(e) => setData("name", e.target.value)}
            required
            isFocused
            autoComplete="name"
            data-oid="0yqlux2"
          />

          <InputError
            className="mt-2"
            message={errors.name}
            data-oid="2_npmmq"
          />
        </div>

        <div data-oid="163zvjh">
          <InputLabel htmlFor="email" value="Email" data-oid="k-07gp7" />

          <TextInput
            id="email"
            type="email"
            className="mt-1 block w-full"
            value={data.email}
            onChange={(e) => setData("email", e.target.value)}
            required
            autoComplete="username"
            data-oid=".pct3me"
          />

          <InputError
            className="mt-2"
            message={errors.email}
            data-oid="f5xzxw8"
          />
        </div>

        {mustVerifyEmail && user.email_verified_at === null && (
          <div data-oid="j6w0oqg">
            <p
              className="mt-2 text-sm text-gray-800 dark:text-gray-200"
              data-oid="7w61jt0"
            >
              Your email address is unverified.
              <Link
                href={route("verification.send")}
                method="post"
                as="button"
                className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                data-oid=".zojy6r"
              >
                Click here to re-send the verification email.
              </Link>
            </p>

            {status === "verification-link-sent" && (
              <div
                className="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                data-oid="8p1jgby"
              >
                A new verification link has been sent to your email address.
              </div>
            )}
          </div>
        )}

        <div className="flex items-center gap-4" data-oid="c81cet1">
          <PrimaryButton disabled={processing} data-oid="kgdt35c">
            Save
          </PrimaryButton>

          <Transition
            show={recentlySuccessful}
            enter="transition ease-in-out"
            enterFrom="opacity-0"
            leave="transition ease-in-out"
            leaveTo="opacity-0"
            data-oid="tbp:33v"
          >
            <p
              className="text-sm text-gray-600 dark:text-gray-400"
              data-oid="f6gux5u"
            >
              Saved.
            </p>
          </Transition>
        </div>
      </form>
    </section>
  );
}
