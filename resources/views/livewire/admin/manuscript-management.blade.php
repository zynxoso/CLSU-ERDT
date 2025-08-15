@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manuscript-management.css') }}">
@endpush

<div>
    <div class="max-w-7xl mx-auto manuscript-container">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold manuscript-header-title">Manuscripts</h1>
                    <p class="mt-1 manuscript-header-subtitle">Manage and review scholar manuscripts</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-4">
                    <button wire:click="openBatchDownloadModal"
                            class="px-4 py-2 rounded-lg inline-flex items-center text-white justify-center shadow-sm btn-batch-download bg-green-600"
                            title="Configure and download manuscripts in batch">
                        <i class="fas fa-file-archive mr-2 btn-icon"></i> Batch Download
                    </button>
                    <a href="{{ route('admin.manuscripts.create') }}"
                       class="px-4 py-2 rounded-lg inline-flex items-center text-white justify-center shadow-sm btn-add-manuscript bg-green-600">
                        <i class="fas fa-plus mr-2 btn-icon"></i> Add Manuscript
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if($successMessage)
                <div class="border-l-4 p-4 mb-4 relative rounded-lg alert-success" role="alert">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle mr-3 mt-0.5 alert-success-icon"></i>
                        <div>
                            <p class="font-bold alert-success-title">Success!</p>
                            <p class="alert-success-text">{{ $successMessage }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('successMessage', '')" class="absolute top-0 right-0 mt-4 mr-4 alert-success-text">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errorMessage)
                <div class="border-l-4 p-4 mb-4 relative rounded-lg alert-error" role="alert">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mr-3 mt-0.5 alert-error-icon"></i>
                        <div>
                            <p class="font-bold alert-error-title">Error!</p>
                            <p class="alert-error-text">{{ $errorMessage }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('errorMessage', '')" class="absolute top-0 right-0 mt-4 mr-4 alert-error-text">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Filters -->
            <div class="rounded-lg p-4 shadow border border-gray-200 filter-container">
                <div class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="status" class="block text-sm font-medium mb-1 filter-label">Status</label>
                        <select wire:model.live="status" class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                            <x-manuscript-status-options />
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="scholar" class="block text-sm font-medium mb-1 filter-label">Scholar</label>
                        <input type="text" wire:model.live.debounce.300ms="scholar" placeholder="Scholar Name"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block text-sm font-medium mb-1 filter-label">Search Title</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="submission_date_from" class="block text-sm font-medium mb-1 filter-label">Submission Date From</label>
                        <input type="month" wire:model.live="submissionDateFrom"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="submission_date_to" class="block text-sm font-medium mb-1 filter-label">Submission Date To</label>
                        <input type="month" wire:model.live="submissionDateTo"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="type" class="block text-sm font-medium mb-1 filter-label">Manuscript Type</label>
                        <select wire:model.live="type" class="w-full border border-gray-300 rounded-md px-3 py-2 filter-input">
                            <x-manuscript-type-options />
                        </select>
                    </div>
                    <div class="flex items-end w-full sm:w-auto">
                        <button type="button" wire:click="resetFilters"
                                class="w-full sm:w-auto px-4 py-2 rounded-lg text-white bg-green-600 btn-reset">
                            <i class="fas fa-times mr-2 btn-icon"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading indicator -->
            <div wire:loading class="w-full">
                <div class="flex justify-center items-center py-8">
                    <div class="rounded-full h-10 w-10 border-b-2 loading-spinner"></div>
                </div>
            </div>

            <!-- Manuscripts Table -->
            <div class="rounded-lg overflow-hidden shadow border table-container" wire:loading.class="opacity-50">
                @include('admin.manuscripts._manuscript_list', ['manuscripts' => $manuscripts])
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $manuscripts->links() }}
            </div>
        </div>
    </div>

    <!-- Batch Download Modal -->
    @if($showBatchDownloadModal)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 modal-backdrop" wire:click="closeBatchDownloadModal"></div>

            <!-- Modal Panel -->
            <div class="fixed inset-x-0 bottom-0 z-50 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center sm:p-4">
                <div class="w-full transform rounded-t-xl sm:rounded-xl shadow-xl sm:max-w-2xl sm:w-full modal-container">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b modal-header">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mr-3 modal-header-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium modal-title" id="modal-title">
                                    Batch Download Configuration
                                </h3>
                                <p class="text-sm mt-1 modal-subtitle">
                                    Configure filters for manuscript download
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeBatchDownloadModal" class="modal-close">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4 max-h-96 sm:max-h-80 overflow-y-auto">
                        <!-- Preview Counter -->
                        <div class="border rounded-lg p-4 mb-6 preview-counter">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2 preview-counter-icon"></i>
                                    <span class="font-medium preview-counter-text">Download Preview</span>
                                </div>
                                <div class="font-semibold preview-counter-count" wire:loading.remove>
                                    {{ $this->downloadPreviewCount }} manuscripts will be downloaded
                                </div>
                                <div class="preview-counter-count" wire:loading>
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Calculating...
                                </div>
                            </div>
                        </div>

                        <!-- Filter Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Status</label>
                                <select wire:model.live="downloadStatus" class="w-full border rounded-lg px-3 py-2 filter-input">
                                    <x-manuscript-status-options />
                                </select>
                            </div>

                            <!-- Scholar Filter -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Scholar</label>
                                <select wire:model.live="downloadScholar" class="w-full border rounded-lg px-3 py-2 filter-input">
                                    <option value="">All Scholars</option>
                                    @foreach($scholars as $scholarOption)
                                        <option value="{{ $scholarOption->name }}">{{ $scholarOption->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title Search -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Search Title</label>
                                <input type="text" wire:model.live.debounce.300ms="downloadSearch" placeholder="Search by title"
                                       class="w-full border rounded-lg px-3 py-2 filter-input">
                            </div>

                            <!-- Manuscript Type -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Manuscript Type</label>
                                <select wire:model.live="downloadType" class="w-full border rounded-lg px-3 py-2 filter-input">
                                    <x-manuscript-type-options />
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Submission Date From</label>
                                <input type="date" wire:model.live="downloadSubmissionDateFrom"
                                       class="w-full border rounded-lg px-3 py-2 filter-input">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-sm font-medium mb-2 filter-label">Submission Date To</label>
                                <input type="date" wire:model.live="downloadSubmissionDateTo"
                                       class="w-full border rounded-lg px-3 py-2 filter-input">
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button wire:click="applyCurrentFiltersToDownload"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm btn-secondary">
                                <i class="fas fa-copy mr-1.5"></i> Use Current Filters
                            </button>
                            <button wire:click="resetDownloadFilters"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm btn-outline">
                                <i class="fas fa-eraser mr-1.5"></i> Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t rounded-b-xl flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0 sm:space-x-3 modal-footer">
                        <!-- Cancel Button -->
                        <button wire:click="closeBatchDownloadModal" class="w-full sm:w-auto px-4 py-2 border rounded-lg btn-outline">
                            Cancel
                        </button>

                        <!-- Download Button -->
                        <button wire:click="proceedWithBatchDownload"
                                @if($this->downloadPreviewCount == 0) disabled @endif
                                class="w-full sm:w-auto px-6 py-2 rounded-lg disabled:cursor-not-allowed inline-flex items-center justify-center btn-primary">
                            <i class="fas fa-download mr-2"></i>
                            Download @if($this->downloadPreviewCount > 0)({{ $this->downloadPreviewCount }})@endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('triggerDownload', (event) => {
            // Mark download start to prevent global loader
            if (window.markDownloadStart) {
                window.markDownloadStart();
            }
            
            // Create a temporary link element to trigger the download
            const link = document.createElement('a');
            link.href = event.downloadUrl;
            link.style.display = 'none';
            link.setAttribute('download', ''); // This will use the filename from the server

            // Add to DOM, click, and remove
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Mark download end after a short delay
            setTimeout(() => {
                if (window.markDownloadEnd) {
                    window.markDownloadEnd();
                }
            }, 1000);
        });
    });
    </script>
</div>
