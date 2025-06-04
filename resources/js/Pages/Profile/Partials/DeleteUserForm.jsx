import DangerButton from "@/Components/DangerButton";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import { useRef, useState } from "react";

export default function DeleteUserForm({ className = "" }) {
  const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
  const passwordInput = useRef();

  const {
    data,
    setData,
    delete: destroy,
    processing,
    reset,
    errors,
    clearErrors,
  } = useForm({
    password: "",
  });

  const confirmUserDeletion = () => {
    setConfirmingUserDeletion(true);
  };

  const deleteUser = (e) => {
    e.preventDefault();

    destroy(route("profile.destroy"), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
      onError: () => passwordInput.current.focus(),
      onFinish: () => reset(),
    });
  };

  const closeModal = () => {
    setConfirmingUserDeletion(false);

    clearErrors();
    reset();
  };

  return (
    <section className={`space-y-6 ${className}`} data-oid="1oyfnly">
      <header data-oid="nrk4dd6">
        <h2
          className="text-lg font-medium text-gray-900 dark:text-gray-100"
          data-oid="jrr_4t0"
        >
          Delete Account
        </h2>

        <p
          className="mt-1 text-sm text-gray-600 dark:text-gray-400"
          data-oid=".d_wrkl"
        >
          Once your account is deleted, all of its resources and data will be
          permanently deleted. Before deleting your account, please download any
          data or information that you wish to retain.
        </p>
      </header>

      <DangerButton onClick={confirmUserDeletion} data-oid="i31pqzu">
        Delete Account
      </DangerButton>

      <Modal
        show={confirmingUserDeletion}
        onClose={closeModal}
        data-oid="uoc210:"
      >
        <form onSubmit={deleteUser} className="p-6" data-oid="5xrexn-">
          <h2
            className="text-lg font-medium text-gray-900 dark:text-gray-100"
            data-oid="i-d.mxk"
          >
            Are you sure you want to delete your account?
          </h2>

          <p
            className="mt-1 text-sm text-gray-600 dark:text-gray-400"
            data-oid="r6acm3o"
          >
            Once your account is deleted, all of its resources and data will be
            permanently deleted. Please enter your password to confirm you would
            like to permanently delete your account.
          </p>

          <div className="mt-6" data-oid="u-c2pqj">
            <InputLabel
              htmlFor="password"
              value="Password"
              className="sr-only"
              data-oid="l543.v1"
            />

            <TextInput
              id="password"
              type="password"
              name="password"
              ref={passwordInput}
              value={data.password}
              onChange={(e) => setData("password", e.target.value)}
              className="mt-1 block w-3/4"
              isFocused
              placeholder="Password"
              data-oid="w84hgfb"
            />

            <InputError
              message={errors.password}
              className="mt-2"
              data-oid="cwav06c"
            />
          </div>

          <div className="mt-6 flex justify-end" data-oid="7xy4te8">
            <SecondaryButton onClick={closeModal} data-oid="5qrzqxv">
              Cancel
            </SecondaryButton>

            <DangerButton
              className="ms-3"
              disabled={processing}
              data-oid="7ei32q6"
            >
              Delete Account
            </DangerButton>
          </div>
        </form>
      </Modal>
    </section>
  );
}
