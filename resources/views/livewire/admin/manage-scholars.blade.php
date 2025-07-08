<div class="min-h-screen" style="background-color: #FAFAFA; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Manage Scholars</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Oversee and manage scholar profiles and status</p>
            </div>
            <a href="{{ route('admin.scholars.create') }}"
               class="inline-flex items-center px-6 py-3 rounded-lg"
               style="background-color: #2E7D32; color: white; font-size: 15px;">
                <i class="fas fa-user-plus mr-2"></i>
                Add New Scholar
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 relative rounded-lg border" style="background-color: #E8F5E8; border-color: #2E7D32; color: #1B5E20;" role="alert">
                <p class="font-bold" style="font-size: 15px;">Success!</p>
                <p style="font-size: 15px;">{!! session('success') !!}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4" style="color: #1B5E20;" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 relative rounded-lg border" style="background-color: #FFEBEE; border-color: #D32F2F; color: #B71C1C;" role="alert">
                <p class="font-bold" style="font-size: 15px;">Error!</p>
                <p style="font-size: 15px;">{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4" style="color: #B71C1C;" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="rounded-lg p-6 mb-6 border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Status</label>
                    <select id="status" wire:model.live="status"
                            class="w-full border rounded-md px-3 py-2"
                            style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="New">New</option>
                        <option value="Completed">Completed</option>
                        <option value="Graduated">Graduated</option>
                        <option value="Terminated">Terminated</option>
                        <option value="On Extension">On Extension</option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Start Date</label>
                    <input type="month" id="start_date" wire:model.live="start_date_filter"
                           class="w-full border rounded-md px-3 py-2"
                           style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;"
                           min="2021-01" max="2025-12">
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Search</label>
                    <div class="relative">
                        <input type="text" id="search" wire:model.live.debounce.300ms="search"
                            placeholder="Name, Email, or ID"
                               class="w-full border rounded-md pl-10 pr-3 py-2"
                               style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search" style="color: #757575;"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="button" wire:click="filter"
                        class="flex-1 px-4 py-2 rounded-lg"
                            style="background-color: #2E7D32; color: white; font-size: 15px;">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <button type="button" wire:click="resetFilters"
                            class="px-4 py-2 border rounded-lg"
                            style="background-color: white; color: #424242; border-color: #E0E0E0; font-size: 15px;">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div wire:loading class="w-full">
            <div class="flex justify-center items-center py-8">
                <div class="h-10 w-10 border-4" style="border-color: #E0E0E0; border-top-color: #2E7D32;"></div>
            </div>
        </div>

        <!-- Scholars Table -->
        <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: white; border-color: #E0E0E0;" wire:loading.class="opacity-50">
            @if(count($scholars) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
                        <thead style="background-color: #F8F9FA;">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Scholar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Program</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: #757575;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                            @foreach($scholars as $scholar)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center" style="background-color: #F8BBD0;">
                                                @if($scholar->profile_photo)
                                                    <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <i class="fas fa-user" style="color: #2E7D32;"></i>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium" style="color: #212121; font-size: 15px;" title="{{ $scholar->user->name }}">
                                                    {{ Str::limit($scholar->user->name, 25, '...') }}
                                                </div>
                                                <div class="text-sm" style="color: #757575; font-size: 14px;" title="{{ $scholar->user->email }}">
                                                    {{ Str::limit($scholar->user->email, 30, '...') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm" style="color: #424242; font-size: 15px;" title="{{ $scholar->program }}">
                                            {{ Str::limit($scholar->program, 40, '...') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full
                                            @if($scholar->status == 'New') " style="background-color: #FFCA28; color: black;"
                                            @elseif($scholar->status == 'Active' || $scholar->status == 'Ongoing') " style="background-color: #2E7D32; color: white;"
                                            @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') " style="background-color: #1976D2; color: white;"
                                            @elseif($scholar->status == 'On Extension') " style="background-color: #388E3C; color: white;"
                                            @elseif($scholar->status == 'Terminated') " style="background-color: #D32F2F; color: white;"
                                            @else " style="background-color: #757575; color: white;" @endif">
                                            {{ $scholar->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('admin.scholars.show', $scholar->id) }}"
                                               class="inline-flex items-center justify-center h-8 w-8 rounded-full"
                                               style="background-color: #1976D2; color: white;"
                                               title="View Scholar Details">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.scholars.edit', $scholar->id) }}"
                                               class="inline-flex items-center justify-center h-8 w-8 rounded-full"
                                               style="background-color: #2E7D32; color: white;"
                                               title="Edit Scholar">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
                        <i class="fas fa-user-graduate text-2xl" style="color: #757575;"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2" style="color: #212121; font-size: 18px;">No Scholars Found</h3>
                    <p class="mb-6" style="color: #757575; font-size: 15px;">There are no scholars matching your filter criteria.</p>
                    <a href="{{ route('admin.scholars.create') }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg"
                       style="background-color: #2E7D32; color: white; font-size: 15px;">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add New Scholar
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($scholars->hasPages())
            <div class="mt-6">
            {{ $scholars->links() }}
        </div>
        @endif
    </div>
</div>
