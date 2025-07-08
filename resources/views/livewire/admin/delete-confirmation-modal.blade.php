<div>
    <div class="p-4">
        <div class="flex items-center mb-4">
            <i class="fas fa-trash-alt text-red-500 mr-2"></i>
            <span class="text-lg font-semibold">Delete Scholar</span>
        </div>

        <p class="mb-3">Are you sure you want to delete this scholar? This action cannot be undone.</p>
        <p class="font-semibold text-gray-700 mb-4">{{ $name }}</p>

        <div class="mb-4">
            <label for="confirmation" class="block text-sm font-medium text-gray-700 mb-1">Type <span class="font-bold text-red-500">delete</span> to confirm:</label>
            <input
                type="text"
                id="confirmation"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                wire:model.live="confirmText"
                placeholder="delete"
            >
            @error('confirmText') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <x-button
                variant="outline-secondary"
                size="sm"
                wire:click="$dispatch('closeModal')"
            >
                Cancel
            </x-button>

            <x-button
                type="button"
                variant="danger"
                size="sm"
                wire:click="delete"
                wire:loading.attr="disabled"
                :disabled="$confirmText !== 'delete'"
            >
                <span wire:loading.remove wire:target="delete">Delete</span>
                <span wire:loading wire:target="delete">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Deleting...
                </span>
            </x-button>
        </div>
    </div>
</div>
