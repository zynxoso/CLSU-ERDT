<div class="min-h-screen">
    <div class="container mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">Notifications</h1>
                    <p class="text-gray-500 mt-1">View all your notifications and updates</p>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if ($unreadCount > 0)
                        <button wire:click="markAllAsRead" 
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-secondary-500 text-white rounded-lg hover:bg-secondary-600 transition-colors duration-200 font-medium disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span wire:loading.remove wire:target="markAllAsRead">Mark All as Read</span>
                            <span wire:loading wire:target="markAllAsRead">Marking...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           id="search"
                           placeholder="Search notifications..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-transparent">
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select wire:model.live="filterType" 
                            id="filterType"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach($notificationTypes as $type)
                            <option value="{{ $type }}">{{ $this->getNotificationLabel($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Read Status Filter -->
                <div>
                    <label for="filterRead" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="filterRead" 
                            id="filterRead"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-transparent">
                        <option value="">All</option>
                        <option value="0">Unread</option>
                        <option value="1">Read</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label for="perPage" class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                    <select wire:model.live="perPage" 
                            id="perPage"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-transparent">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-md p-6 border border-gray-200 shadow-sm">
            @if ($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach ($notifications as $notification)
                        <div class="flex items-start p-4 rounded-lg border hover:bg-gray-50 transition-colors duration-200
                                    @if (!$notification->is_read) border-l-4 border-l-[rgb(74_144_226)] bg-secondary-500/5 @else border-gray-200 @endif"
                             wire:key="notification-{{ $notification->id }}">
                            
                            <!-- Icon -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                                        @php
                                            $color = $this->getNotificationColor($notification->type);
                                            $bgClass = match($color) {
                                                'secondary' => 'bg-secondary-500/10',
                                                'primary' => 'bg-primary-500/10',
                                                'purple' => 'bg-purple-100',
                                                'indigo' => 'bg-indigo-100',
                                                default => 'bg-gray-100'
                                            };
                                        @endphp
                                        {{ $bgClass }}">
                                <svg class="w-6 h-6 
                                            @php
                                                $iconColor = match($color) {
                                                    'secondary' => 'text-secondary-500',
                                                    'primary' => 'text-primary-500',
                                                    'purple' => 'text-purple-600',
                                                    'indigo' => 'text-indigo-600',
                                                    default => 'text-gray-600'
                                                };
                                            @endphp
                                            {{ $iconColor }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="{{ $this->getNotificationIcon($notification->type) }}"></path>
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $notification->title }}</h3>
                                        
                                        <!-- Category Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                     @php
                                                         $badgeClass = match($color) {
                                                             'secondary' => 'bg-secondary-500/10 text-secondary-500',
                                                             'primary' => 'bg-primary-500/10 text-primary-800',
                                                             'purple' => 'bg-purple-100 text-purple-700',
                                                             'indigo' => 'bg-indigo-100 text-indigo-700',
                                                             default => 'bg-gray-100 text-gray-700'
                                                         };
                                                     @endphp
                                                     {{ $badgeClass }}">
                                            {{ $this->getNotificationLabel($notification->type) }}
                                        </span>
                                        
                                        @if (!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-warning-400/20 text-warning-700">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500 flex-shrink-0">
                                            {{ $notification->created_at->format('M d, Y - h:i A') }}
                                        </span>
                                        
                                        <!-- Mark as Read Button (visible for unread notifications) -->
                                        @if (!$notification->is_read)
                                            <button wire:click="markAsRead({{ $notification->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-secondary-600 bg-secondary-50 border border-secondary-200 rounded-md hover:bg-secondary-100 hover:border-secondary-300 transition-colors duration-200 disabled:opacity-50">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span wire:loading.remove wire:target="markAsRead({{ $notification->id }})">Mark as Read</span>
                                                <span wire:loading wire:target="markAsRead({{ $notification->id }})">Marking...</span>
                                            </button>
                                        @endif
                                        
                                        <!-- Actions Dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" 
                                                 @click.away="open = false"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                <div class="py-1">
                                                    <button wire:click="deleteNotification({{ $notification->id }})"
                                                            wire:confirm="Are you sure you want to delete this notification?"
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $notification->message }}</p>
                                
                                @if ($notification->link)
                                    <div class="mt-4">
                                        <a href="{{ $notification->link }}"
                                           class="inline-flex items-center px-4 py-2 bg-secondary-500/10 text-secondary-500 rounded-lg hover:bg-secondary-500/20 transition-colors duration-200 font-medium">
                                            <span>View Details</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">
                        @if($search || $filterType || $filterRead !== '')
                            No Matching Notifications
                        @else
                            No Notifications
                        @endif
                    </h3>
                    <p class="text-gray-500">
                        @if($search || $filterType || $filterRead !== '')
                            Try adjusting your filters to see more notifications.
                        @else
                            You don't have any notifications at the moment. You'll be notified about updates to your profile, fund requests, documents, and manuscripts.
                        @endif
                    </p>
                    
                    @if($search || $filterType || $filterRead !== '')
                        <button wire:click="$set('search', ''); $set('filterType', ''); $set('filterRead', '')"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-secondary-500 text-white rounded-lg hover:bg-secondary-600 transition-colors duration-200">
                            Clear Filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>
</div>
@push('styles')
<style>
    .btn-transition{transition:all 0.3s ease;}
    .btn-transition:hover{transform:translateY(-3px);box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05);}
</style>
@endpush