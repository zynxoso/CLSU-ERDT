<div class="max-w-7xl mx-auto" style="background-color: rgb(250 250 250); min-height: 100vh;">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold" style="color: rgb(23 23 23);">Announcements Management</h1>
                <p class="mt-1 text-sm" style="color: rgb(97 97 97);">Create and manage announcements for scholars and visitors</p>
            </div>
            <button wire:click="openModal" 
                    class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    style="background-color: rgb(21 128 61); color: rgb(255 255 255);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Announcement
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if($successMessage)
            <div class="border-l-4 p-4 mb-4 relative" role="alert" style="background-color: rgba(76, 175, 80, 0.1); border-color: rgb(34 197 94); color: rgb(21 128 61);">
                <p class="font-bold">Success!</p>
                <p>{{ $successMessage }}</p>
                <button wire:click="$set('successMessage', '')" class="absolute top-0 right-0 mt-4 mr-4" style="color: rgb(34 197 94);">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errorMessage)
            <div class="border-l-4 p-4 mb-4 relative" role="alert" style="background-color: rgba(211, 47, 47, 0.1); border-color: rgb(211 47 47); color: rgb(211 47 47);">
                <p class="font-bold">Error!</p>
                <p>{{ $errorMessage }}</p>
                <button wire:click="$set('errorMessage', '')" class="absolute top-0 right-0 mt-4 mr-4" style="color: rgb(211 47 47);">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="p-4 rounded-lg shadow" style="background-color: rgb(255 255 255); border: 1px solid rgb(224 224 224);">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search announcements..." 
                           class="w-full rounded-md px-3 py-2 focus:outline-none" 
                           style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="typeFilter" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Type</label>
                    <select wire:model.live="typeFilter" class="w-full rounded-md px-3 py-2 focus:outline-none" 
                            style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);">
                        <option value="">All Types</option>
                        @foreach($announcementTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="statusFilter" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Status</label>
                    <select wire:model.live="statusFilter" class="w-full rounded-md px-3 py-2 focus:outline-none" 
                            style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters" 
                            class="px-4 py-2 rounded-md transition-colors duration-200"
                            style="background-color: rgb(115 115 115); color: rgb(255 255 255);"
                            onmouseover="this.style.backgroundColor='rgb(97 97 97)'"
                    onmouseout="this.style.backgroundColor='rgb(117 117 117)'">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="rounded-lg shadow overflow-hidden" style="background-color: rgb(255 255 255); border: 1px solid rgb(224 224 224);">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: rgb(224 224 224);">
                    <thead style="background-color: rgb(245 245 245);">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: rgb(64 64 64);">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                        @forelse($announcements as $announcement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium" style="color: rgb(23 23 23);">{{ $announcement['title'] }}</div>
                                    <div class="text-sm" style="color: rgb(115 115 115);">{{ Str::limit($announcement['content'], 60) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                          style="background-color: {{ $this->getTypeColor($announcement['type']) }}; color: rgb(255 255 255);">
                                        {{ $announcementTypes[$announcement['type']] ?? ucfirst($announcement['type']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium" style="color: rgb(64 64 64);">{{ $announcement['priority'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleStatus({{ $announcement['id'] }})" 
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-200"
                                            style="background-color: {{ $announcement['is_active'] ? 'rgb(76 175 80)' : 'rgb(117 117 117)' }}; color: rgb(255 255 255);"
                                            wire:loading.attr="disabled">
                                        {{ $announcement['is_active'] ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: rgb(115 115 115);">
                                    {{ \Carbon\Carbon::parse($announcement['created_at'])->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="openModal({{ $announcement['id'] }})" 
                                                class="px-3 py-1 rounded transition-colors duration-200"
                                                style="background-color: rgb(29 78 216); color: rgb(255 255 255);"
                                                onmouseover="this.style.backgroundColor='rgb(21 101 192)'"
                                onmouseout="this.style.backgroundColor='rgb(25 118 210)'">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $announcement['id'] }})" 
                                                wire:confirm="Are you sure you want to delete this announcement?"
                                                class="px-3 py-1 rounded transition-colors duration-200"
                                                style="background-color: rgb(211 47 47); color: rgb(255 255 255);"
                                onmouseover="this.style.backgroundColor='rgb(198 40 40)'"
                                onmouseout="this.style.backgroundColor='rgb(211 47 47)'"
                                                wire:loading.attr="disabled">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4" style="color: rgb(189 189 189);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                        <p class="text-lg font-medium" style="color: rgb(115 115 115);">No announcements found</p>
                                        <p class="text-sm" style="color: rgb(189 189 189);">Create your first announcement to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium" style="color: rgb(23 23 23);">
                                    {{ $editingAnnouncement ? 'Edit Announcement' : 'Create New Announcement' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Title *</label>
                                    <input type="text" wire:model="form.title" 
                                           class="w-full rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);"
                                           placeholder="Enter announcement title">
                                    @error('form.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="type" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Type *</label>
                                    <select wire:model="form.type" 
                                            class="w-full rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                            style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);">
                                        @foreach($announcementTypes as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="priority" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Priority (0-10) *</label>
                                    <input type="number" wire:model="form.priority" min="0" max="10"
                                           class="w-full rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);"
                                           placeholder="0">
                                    @error('form.priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="content" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Content *</label>
                                    <textarea wire:model="form.content" rows="6"
                                              class="w-full rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                              style="border: 1px solid rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);"
                                              placeholder="Enter announcement content"></textarea>
                                    @error('form.content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="form.is_active" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm" style="color: rgb(64 64 64);">Active</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                                    style="background-color: rgb(21 128 61);"
                                    onmouseover="this.style.backgroundColor='rgb(27 94 32)'"
                                onmouseout="this.style.backgroundColor='rgb(46 125 50)'"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $editingAnnouncement ? 'Update' : 'Create' }}</span>
                                <span wire:loading>Processing...</span>
                            </button>
                            <button type="button" wire:click="closeModal" 
                                    class="mt-3 w-full inline-flex justify-center rounded-md border shadow-sm px-4 py-2 text-base font-medium sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                                    style="border-color: rgb(224 224 224); background-color: rgb(255 255 255); color: rgb(64 64 64);"
                                    onmouseover="this.style.backgroundColor='rgb(245 245 245)'"
                                    onmouseout="this.style.backgroundColor='white'">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openAnnouncementModal', () => {
            @this.openModal();
        });
    });
</script>

@script
<script>
    $wire.getTypeColor = function(type) {const colors = {'general': 'rgb(117 117 117)','application': 'rgb(25 118 210)','scholarship': 'rgb(56 142 60)','event': 'rgb(245 124 0)','urgent': 'rgb(211 47 47)' };return colors[type] || 'rgb(117 117 117)';};
</script>
@endscript
