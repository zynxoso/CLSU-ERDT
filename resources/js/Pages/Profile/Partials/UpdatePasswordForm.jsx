import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Transition } from "@headlessui/react";
import { useForm } from "@inertiajs/react";
import { useRef } from "react";

export default function UpdatePasswordForm({ className = "" }) {
  const passwordInput = useRef();
  const currentPasswordInput = useRef();

  const { data, setData, errors, put, reset, processing, recentlySuccessful } =
    useForm({
      current_password: "",
      password: "",
      password_confirmation: "",
    });

  const updatePassword = (e) => {
    e.preventDefault();

    put(route("password.update"), {
      preserveScroll: true,
      onSuccess: () => reset(),
      onError: (errors) => {
        if (errors.password) {
          reset("password", "password_confirmation");
          passwordInput.current.focus();
        }

        if (errors.current_password) {
          reset("current_password");
          currentPasswordInput.current.focus();
        }
      },
    });
  };

  return (
    <section className={className} data-oid="dwgr1bw">
      <header data-oid="_81en7g">
        <h2
          className="text-lg font-medium text-gray-900 dark:text-gray-100"
          data-oid="bj8e8ud"
        >
          Update Password
        </h2>

        <p
          className="mt-1 text-sm text-gray-600 dark:text-gray-400"
          data-oid="kvg:fnu"
        >
          Ensure your account is using a long, random password to stay secure.
        </p>
      </header>

      <form
        onSubmit={updatePassword}
        className="mt-6 space-y-6"
        data-oid="m.g417q"
      >
        <div data-oid="_w:gjo2">
          <InputLabel
            htmlFor="current_password"
            value="Current Password"
            data-oid="j_giakp"
          />

          <TextInput
            id="current_password"
            ref={currentPasswordInput}
            value={data.current_password}
            onChange={(e) => setData("current_password", e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="current-password"
            data-oid="m93so1h"
          />

          <InputError
            message={errors.current_password}
            className="mt-2"
            data-oid="x-c0ety"
          />
        </div>

        <div data-oid="wc0l5-5">
          <InputLabel
            htmlFor="password"
            value="New Password"
            data-oid="8qn6ms0"
          />

          <TextInput
            id="password"
            ref={passwordInput}
            value={data.password}
            onChange={(e) => setData("password", e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="new-password"
            data-oid="52:bi:n"
          />

          <InputError
            message={errors.password}
            className="mt-2"
            data-oid="cokesde"
          />
        </div>

        <div data-oid="9tb9kw9">
          <InputLabel
            htmlFor="password_confirmation"
            value="Confirm Password"
            data-oid="3dvosqy"
          />

          <TextInput
            id="password_confirmation"
            value={data.password_confirmation}
            onChange={(e) => setData("password_confirmation", e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="new-password"
            data-oid="q0l:::7"
          />

          <InputError
            message={errors.password_confirmation}
            className="mt-2"
            data-oid="_aq99dr"
          />
        </div>

        <div className="flex items-center gap-4" data-oid="7.q.08b">
          <PrimaryButton disabled={processing} data-oid="6bib-kw">
            Save
          </PrimaryButton>

          <Transition
            show={recentlySuccessful}
            enter="transition ease-in-out"
            enterFrom="opacity-0"
            leave="transition ease-in-out"
            leaveTo="opacity-0"
            data-oid="mr_36ww"
          >
            <p
              className="text-sm text-gray-600 dark:text-gray-400"
              data-oid="ugj0orp"
            >
              Saved.
            </p>
          </Transition>
        </div>
      </form>
    </section>
  );
}
