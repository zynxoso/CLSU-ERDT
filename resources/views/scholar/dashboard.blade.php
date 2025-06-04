@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Top Navigation Bar -->
<div class="bg-white shadow-sm mb-6 -mt-8 -mx-8 px-8 py-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 ml-1">Dashboard</h1>
        <div class="flex items-center space-x-4">
            <!-- Notification Dropdown -->
            <div class="relative" x-data="{ open: false, notificationCount: {{ count($notifications->where('is_read', false)) }} }">
                <button @click="open = !open" class="relative p-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span x-show="notificationCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full shadow-sm" x-text="notificationCount"></span>
                </button>
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-[350px] bg-white rounded-lg shadow-xl overflow-hidden z-50 border border-gray-200"
                     style="display: none;">
                    <div class="py-3 px-4 bg-blue-50 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-gray-800">Notifications</span>
                        </div>
                        <a href="{{ route('scholar.notifications') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <span>View All</span>
                        </a>
                    </div>
                    <div class="max-h-[500px] overflow-y-auto">
                        @if(count($notifications) > 0)
                            @foreach($notifications->take(5) as $notification)
                                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150" x-data="{ expanded: false }">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-blue-50 transition-colors duration-150">
                                                @if($notification->type == 'profile_update')
                                                    <i class="fas fa-user-edit text-blue-600"></i>
                                                @elseif($notification->type == 'fund_request')
                                                    <i class="fas fa-money-bill-wave text-green-600"></i>
                                                @elseif($notification->type == 'document')
                                                    <i class="fas fa-file-alt text-purple-600"></i>
                                                @elseif($notification->type == 'manuscript')
                                                    <i class="fas fa-book text-indigo-600"></i>
                                                @else
                                                    <i class="fas fa-bell text-orange-600"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-lg font-bold text-gray-800">{{ $notification->title }}</p>
                                            <div>
                                                <p class="text-sm text-gray-600 mt-1" x-show="!expanded">{{ Str::limit($notification->message, 80) }}
                                                    @if(strlen($notification->message) > 80)
                                                        <button @click="expanded = true" class="text-xs text-blue-600 hover:text-blue-800 hover:underline ml-1 focus:outline-none">Read more</button>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-600 mt-1" x-show="expanded" x-cloak>{{ $notification->message }}
                                                    <button @click="expanded = false" class="text-xs text-blue-600 hover:text-blue-800 hover:underline ml-1 focus:outline-none">Show less</button>
                                                </p>
                                            </div>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 transition-colors duration-150">
                                                        Details
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-4 text-center text-gray-500">
                                <p>No notifications to display.</p>
                            </div>
                        @endif
                    </div>
                    @if(count($notifications->where('is_read', false)) > 0)
                        <div class="py-2 px-4 bg-gray-50 border-t border-gray-200 text-center">
                            <a href="{{ route('scholar.notifications.mark-all-as-read') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Mark all as read
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100 shadow-sm">
                        <span class="text-blue-600 font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-50"
                     style="display: none;">
                    <a href="{{ route('scholar.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2 text-black" style="margin-right: 0.5rem; color: #6B7280;"></i> My Profile
                    </a>
                    <a href="{{ route('scholar.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog" style="margin-right: 0.5rem; color: #6B7280;"></i> Settings
                    </a>
                    <a href="{{ route('scholar.password.change') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-key mr-2 text-gray-500" style="margin-right: 0.5rem; color: #6B7280;"></i> Change Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2" style="margin-right: 0.5rem; color:#FF4842"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ Auth::user()->name }}</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
        <div class="flex items-center mb-3">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            <h2 class="text-base font-semibold text-gray-800">Quick Actions</h2>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('scholar.fund-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 p-3 rounded-lg text-center border border-blue-700 transition-all hover:shadow">
                <i class="fas fa-money-bill-wave text-white text-xl mb-1"></i>
                <p class="text-white font-medium text-sm">Request Funds</p>
            </a>
            <a href="{{ route('scholar.profile.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 p-3 rounded-lg text-center border border-yellow-600 transition-all hover:shadow">
                <i class="fas fa-user-edit text-white text-xl mb-1"></i>
                <p class="text-white font-medium text-sm">Update Profile</p>
            </a>
        </div>
    </div>

    <!-- Fund Summary -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
        <div class="flex items-center mb-3">
            <i class="fas fa-chart-pie text-green-600 mr-2"></i>
            <h2 class="text-base font-semibold text-gray-800">Fund Summary</h2>
        </div>
        <div class="flex justify-between">
            <div class="text-center bg-gray-50 p-3 rounded-lg border border-gray-100 flex-1 mr-2">
                <p class="text-sm text-gray-600 mb-1">Approved Requests</p>
                <p class="text-lg font-bold text-gray-800">{{ $approvedRequests }}</p>
            </div>
            <div class="text-center bg-gray-50 p-3 rounded-lg border border-gray-100 flex-1 ml-2">
                <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                <p class="text-lg font-bold text-gray-800">{{ $pendingRequestsCount }}</p>
            </div>
        </div>
    </div>
</div>
<!-- Recent Activity -->
<div class="grid grid-cols-1 gap-6">
    <!-- Recent Fund Requests -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Recent Fund Requests</h2>
        </div>
        <div class="p-4">
            @if(count($recentFundRequests) > 0)
                <div class="space-y-2">
                    @foreach($recentFundRequests->take(3) as $request)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm" data-fund-request-id="{{ $request->id }}">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-sm text-gray-700">{{ $request->purpose }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-800">₱******</p>
                                    <span class="status-badge px-2 py-0.5 text-xs rounded-full
                                        @if($request->status == 'Approved') bg-green-100 text-green-800
                                        @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $request->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('scholar.fund-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-xs">View All Requests</a>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500 text-sm">No fund requests yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Real-time Status Updates Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch the latest status updates
    function fetchStatusUpdates() {
        const requestCards = document.querySelectorAll('[data-fund-request-id]');

        if (requestCards.length === 0) return;

        // Collect all fund request IDs
        const requestIds = Array.from(requestCards).map(card => card.dataset.fundRequestId);

        // Fetch updates for all requests
        fetch('/scholar/fund-requests/status-updates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ request_ids: requestIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update each request card with new status information
                data.updates.forEach(update => {
                    const card = document.querySelector(`[data-fund-request-id="${update.id}"]`);
                    if (card) {
                        // Update status badge
                        const statusBadge = card.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = update.status;

                            // Remove all existing status classes
                            statusBadge.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800');

                            // Add appropriate class based on status
                            if (update.status === 'Approved') {
                                statusBadge.classList.add('bg-green-100', 'text-green-800');
                            } else if (update.status === 'Rejected') {
                                statusBadge.classList.add('bg-red-100', 'text-red-800');
                            } else {
                                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                            }
                        }

                        // Update timeline if there are new status history entries
                        if (update.status_history && update.status_history.length > 0) {
                            const timelineContainer = card.querySelector('.status-timeline-container');
                            if (timelineContainer) {
                                // Clear existing timeline
                                timelineContainer.innerHTML = '';

                                // Add new timeline entries (most recent first, limit to 3)
                                const recentHistory = update.status_history.slice().reverse().slice(0, 3);

                                recentHistory.forEach(history => {
                                    const entryDiv = document.createElement('div');
                                    entryDiv.className = 'flex items-center';

                                    // Determine icon and styling based on status
                                    let iconClass, bgColorClass, textColorClass, borderColorClass;

                                    if (history.status === 'Approved') {
                                        iconClass = 'fa-check-circle';
                                        bgColorClass = 'bg-green-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-green-700';
                                    } else if (history.status === 'Rejected') {
                                        iconClass = 'fa-times-circle';
                                        bgColorClass = 'bg-red-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-red-700';
                                    } else if (history.status === 'Submitted') {
                                        iconClass = 'fa-paper-plane';
                                        bgColorClass = 'bg-blue-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-blue-700';
                                    } else if (history.status === 'Under Review') {
                                        iconClass = 'fa-search';
                                        bgColorClass = 'bg-purple-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-purple-700';
                                    } else if (history.status === 'Draft') {
                                        iconClass = 'fa-pencil-alt';
                                        bgColorClass = 'bg-yellow-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-yellow-700';
                                    } else {
                                        iconClass = 'fa-clock';
                                        bgColorClass = 'bg-yellow-600';
                                        textColorClass = 'text-white';
                                        borderColorClass = 'border-yellow-700';
                                    }

                                    // Format timestamp
                                    const timestamp = new Date(history.timestamp);
                                    const timeAgo = timeSince(timestamp);

                                    entryDiv.innerHTML = `
                                        <div class="absolute -left-6 w-6 h-6 rounded-full flex items-center justify-center ${bgColorClass} ${textColorClass} border ${borderColorClass} transform transition-all duration-300 hover:scale-110 shadow-sm">
                                            <i class="fas ${iconClass} text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium">
                                                ${history.status}
                                                <span class="text-gray-400 font-normal">${timeAgo}</span>
                                            </p>
                                            ${history.notes ? `<p class="text-xs text-gray-500">${truncateText(history.notes, 50)}</p>` : ''}
                                        </div>
                                    `;

                                    timelineContainer.appendChild(entryDiv);
                                });

                                // Add animations to make the timeline interactive
                                // 1. Highlight the container
                                timelineContainer.classList.add('status-update-highlight', 'animating');

                                // 2. Animate the timeline track
                                const timelineTrack = card.querySelector('.timeline-track');
                                if (timelineTrack) {
                                    timelineTrack.classList.add('animating');
                                }

                                // 3. Add pulse animation to the most recent status icon
                                const firstStatusIcon = timelineContainer.querySelector('.flex.items-center:first-child div[class*="rounded-full"]');
                                if (firstStatusIcon) {
                                    firstStatusIcon.classList.add('status-icon-animating');
                                }

                                // 4. Animate the shipping truck along the timeline
                                const shippingTruck = card.querySelector('.shipping-truck');
                                if (shippingTruck) {
                                    // Reset any previous animations
                                    shippingTruck.classList.remove('animating', 'bounce');
                                    void shippingTruck.offsetWidth; // Force reflow to restart animation

                                    // Start the truck animation
                                    shippingTruck.classList.add('animating');

                                    // Add bounce effect at each status point
                                    const statusPoints = timelineContainer.querySelectorAll('.flex.items-center');
                                    if (statusPoints.length > 0) {
                                        let delay = 500; // Start delay
                                        statusPoints.forEach((point, index) => {
                                            // Calculate position based on index (0 = top, length-1 = bottom)
                                            const position = index / (statusPoints.length - 1);

                                            // Schedule bounce effect at this position
                                            setTimeout(() => {
                                                // Temporarily pause the truck at this point
                                                shippingTruck.style.animationPlayState = 'paused';
                                                shippingTruck.classList.add('bounce');

                                                // Resume after bounce
                                                setTimeout(() => {
                                                    shippingTruck.classList.remove('bounce');
                                                    shippingTruck.style.animationPlayState = 'running';
                                                }, 500);
                                            }, delay + position * 1000); // Distribute over animation time
                                        });
                                    }
                                }

                                // Remove animations after they complete
                                setTimeout(() => {
                                    timelineContainer.classList.remove('status-update-highlight', 'animating');
                                    if (timelineTrack) timelineTrack.classList.remove('animating');
                                    if (firstStatusIcon) firstStatusIcon.classList.remove('status-icon-animating');
                                    // Truck animation will end itself with opacity: 0
                                }, 2500);
                            }
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching status updates:', error));
    }

    // Helper function to format time since a date
    function timeSince(date) {
        const seconds = Math.floor((new Date() - date) / 1000);

        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " years ago";

        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months ago";

        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";

        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours ago";

        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes ago";

        return Math.floor(seconds) + " seconds ago";
    }

    // Helper function to truncate text
    function truncateText(text, maxLength) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    // Check for updates every 30 seconds
    setInterval(fetchStatusUpdates, 30000);

    // Initial fetch after page load
    setTimeout(fetchStatusUpdates, 3000);
});
</script>

<style>
/* Timeline animations */
@keyframes timeline-pulse {
    0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
    70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

@keyframes timeline-track-progress {
    from { background: linear-gradient(to bottom, #3b82f6 0%, #3b82f6 0%, #e5e7eb 0%, #e5e7eb 100%); }
    to { background: linear-gradient(to bottom, #3b82f6 0%, #3b82f6 100%, #e5e7eb 100%, #e5e7eb 100%); }
}

@keyframes truck-move-down {
    0% { opacity: 0; transform: translateY(0) translateX(-50%) rotate(90deg); }
    10% { opacity: 1; transform: translateY(0) translateX(-50%) rotate(90deg); }
    90% { opacity: 1; transform: translateY(100%) translateX(-50%) rotate(90deg); }
    100% { opacity: 0; transform: translateY(100%) translateX(-50%) rotate(90deg); }
}

@keyframes truck-bounce {
    0%, 100% { transform: translateY(0) translateX(-50%) rotate(90deg); }
    50% { transform: translateY(0) translateX(-50%) rotate(90deg) scale(1.2); }
}

.timeline-track.animating {
    animation: timeline-track-progress 1.5s ease-in-out forwards;
}

.status-icon-animating {
    animation: timeline-pulse 1.5s infinite;
}

.shipping-truck {
    transition: all 0.3s ease-in-out;
}

.shipping-truck.animating {
    animation: truck-move-down 2s ease-in-out forwards;
}

.shipping-truck.bounce {
    animation: truck-bounce 0.5s ease-in-out;
}

.status-update-highlight {
    transition: all 0.3s ease-in-out;
}

.status-update-highlight.animating {
    background-color: rgba(219, 234, 254, 0.7);
    border-color: rgba(59, 130, 246, 0.3);
}
</style>
@endsection
