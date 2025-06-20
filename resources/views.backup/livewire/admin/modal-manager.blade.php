<div>
    @if($show)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <!-- Modal panel -->
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto z-50">
                @if($component === 'delete-confirmation-modal')
                    <livewire:admin.delete-confirmation-modal :id="$arguments['id'] ?? null" :name="$arguments['name'] ?? ''" :key="'delete-modal-' . ($arguments['id'] ?? 'new')" />
                @endif
            </div>
        </div>
    @endif
</div>
