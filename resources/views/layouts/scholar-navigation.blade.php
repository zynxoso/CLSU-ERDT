<!-- Scholar Navigation and Collapsible Sidebar -->
<div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }"
    @resize.window="if (window.innerWidth > 1024) sidebarOpen = false">
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
        <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
    </div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg border-r border-gray-200 transform transition-all duration-300 lg:translate-x-0"
        x-cloak
        :class="{
            '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
            'translate-x-0': sidebarOpen || window.innerWidth >= 1024,
            'w-64': !sidebarCollapsed,
            'w-20': sidebarCollapsed && window.innerWidth >= 1024
        }">
        <div class="flex flex-col h-full">
            <!-- Logo and Toggle Button -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 bg-gray-100">
                <a href="{{ route('scholar.dashboard') }}" class="text-xl font-bold text-green-700"
                    :class="{ 'opacity-0': sidebarCollapsed && window.innerWidth >= 1024 }">
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">CLSU-ERDT</span>
                </a>

                <!-- Desktop Toggle Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
                    :class="{ 'absolute right-2': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        :class="{ 'rotate-180': sidebarCollapsed }">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Mobile Close Button -->
                <button @click="sidebarOpen = false"
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 absolute right-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto text-sm">
                <a href="{{ route('scholar.dashboard') }}"
                    class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('scholar.dashboard') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.dashboard') ? 'text-white' : 'text-gray-500' }}"
                        :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">Dashboard</span>
                </a>

                <a href="{{ route('scholar.fund-requests') }}"
                    class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('scholar.fund-requests*') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.fund-requests*') ? 'text-white' : 'text-gray-500' }}"
                        :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">Fund Requests</span>
                </a>

                <a href="{{ route('scholar.manuscripts.index') }}"
                    class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('scholar.manuscripts*') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.manuscripts*') ? 'text-white' : 'text-gray-500' }}"
                        :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">Manuscripts</span>
                </a>

                <a href="{{ route('scholar.profile') }}"
                    class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('scholar.profile*') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.profile*') ? 'text-white' : 'text-gray-500' }}"
                        :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">My Profile</span>
                </a>

                <a href="{{ route('scholar.settings') }}"
                    class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('scholar.settings') || request()->routeIs('scholar.password*') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.settings') || request()->routeIs('scholar.password*') ? 'text-white' : 'text-gray-500' }}"
                        :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || window.innerWidth < 1024">Settings</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200 bg-gray-100"
                :class="{ 'px-2': sidebarCollapsed && window.innerWidth >= 1024 }">
                <!-- Notification Icon for Desktop -->
                <div x-data="{
                    notificationCount: 0,
                    async updateNotificationCount() {
                        try {
                            const response = await fetch('{{ route('scholar.notifications.unread-count', [], false) }}', {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                this.notificationCount = data.count;
                            }
                        } catch (error) {
                            console.warn('Failed to fetch notification count');
                        }
                    }
                }" x-init="updateNotificationCount();
                setInterval(() => updateNotificationCount(), 30000)"
                    @notification-updated.window="updateNotificationCount()">
                    <a href="{{ route('scholar.notifications') }}"
                        class="flex items-center px-4 py-3 mb-4 font-medium rounded-lg {{ request()->routeIs('scholar.notifications*') ? 'text-white bg-green-700 border-l-4 border-green-800' : 'text-gray-600 hover:bg-gray-100' }}"
                        @click="if (window.innerWidth < 1024) sidebarOpen = false; setTimeout(() => updateNotificationCount(), 1000)">
                        <div class="relative flex items-center w-full">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('scholar.notifications*') ? 'text-white' : 'text-gray-500' }}"
                                :class="{ 'mr-3': !sidebarCollapsed, 'mx-auto': sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="!sidebarCollapsed || window.innerWidth < 1024"
                                class="truncate">Notifications</span>
                            <!-- Desktop Notification Badge -->
                            <span x-show="notificationCount > 0 && (!sidebarCollapsed || window.innerWidth < 1024)"
                                class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.25rem] h-5"
                                x-text="notificationCount > 99 ? '99+' : notificationCount">
                            </span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center"
                    :class="{ 'justify-center': sidebarCollapsed && window.innerWidth >= 1024 }">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center border shadow-sm bg-green-100 border-green-700">
                            <span class="font-medium text-green-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3" x-show="!sidebarCollapsed || window.innerWidth < 1024">
                        <p class="text-sm font-medium text-gray-600">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Scholar</p>
                    </div>
                </div>

                <!-- Logout Button with SweetAlert -->
                <form id="scholar-logout-form" method="POST" action="{{ route('scholar-logout') }}"
                    class="mt-4">
                    @csrf
                    <button type="button" id="scholar-logout-button"
                        class="flex items-center w-full px-4 py-2 font-medium bg-white rounded-lg border border-red-200 text-red-600 hover:bg-red-50"
                        :class="{ 'justify-center px-2': sidebarCollapsed && window.innerWidth >= 1024 }">
                        <svg class="w-5 h-5 flex-shrink-0"
                            :class="{ 'mr-3': !sidebarCollapsed || window.innerWidth < 1024 }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="!sidebarCollapsed || window.innerWidth < 1024">Logout</span>
                    </button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const logoutButton = document.getElementById('scholar-logout-button');
                        if (logoutButton) {
                            const logoutForm = document.getElementById('scholar-logout-form');

                            logoutButton.addEventListener('click', function(e) {
                                e.preventDefault();

                                Swal.fire({
                                    title: '<span style="font-weight:600;font-size:1.1em;color:#2E7D32;">Logout Confirmation</span>',
                                    text: 'Are you sure you want to logout?',
                                    showCancelButton: true,
                                    focusConfirm: false,
                                    customClass: {
                                        popup: 'clsu-erdt-swal-clean',
                                        confirmButton: 'clsu-erdt-confirm-clean',
                                        cancelButton: 'clsu-erdt-cancel-clean'
                                    },
                                    confirmButtonText: 'Logout',
                                    cancelButtonText: 'Cancel',
                                    buttonsStyling: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        logoutForm.submit();
                                    }
                                });
                            });
                        }
                    });
                </script>
                <style>
                    .clsu-erdt-swal-clean {
                        border-radius: 0.75rem !important;
                        box-shadow: 0 4px 24px 0 rgba(46, 125, 50, 0.08);
                        padding-top: 2em !important;
                    }

                    .clsu-erdt-confirm-clean {
                        background: #2E7D32 !important;
                        color: #fff !important;
                        border: none !important;
                        border-radius: 0.375rem !important;
                        font-weight: 600 !important;
                        font-size: 1em !important;
                        padding: 0.6em 2em !important;
                        margin-right: 0.5em;
                    }

                    .clsu-erdt-cancel-clean {
                        background: #f3f4f6 !important;
                        color: #374151 !important;
                        border: none !important;
                        border-radius: 0.375rem !important;
                        font-weight: 600 !important;
                        font-size: 1em !important;
                        padding: 0.6em 2em !important;
                    }
                </style>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-white transition-all duration-300 lg:pl-64"
        :class="{
            'lg:pl-64': !sidebarCollapsed,
            'lg:pl-20': sidebarCollapsed
        }">

        <!-- Desktop Header with Semester Chip -->
        <div class="hidden lg:flex items-center justify-end p-4 bg-white border-b border-gray-200">
            <livewire:components.current-semester-chip />
        </div>
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <button @click="sidebarOpen = true"
                class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex flex-col items-center space-y-1">
                <h1 class="text-lg font-semibold text-gray-600">CLSU-ERDT Scholar</h1>
                <livewire:components.current-semester-chip />
            </div>

            <!-- Mobile Notification Icon -->
            <div class="relative" x-data="{
                notificationCount: 0,
                async updateNotificationCount() {
                    try {
                        const response = await fetch('{{ route('scholar.notifications.unread-count', [], false) }}', {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.notificationCount = data.count;
                        }
                    } catch (error) {
                        console.warn('Failed to fetch notification count');
                    }
                }
            }" x-init="updateNotificationCount();
            setInterval(() => updateNotificationCount(), 30000)"
                @notification-updated.window="updateNotificationCount()">
                <a href="{{ route('scholar.notifications') }}"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
                    @click="setTimeout(() => updateNotificationCount(), 1000)">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Notification Badge -->
                    <span x-show="notificationCount > 0"
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem] h-5"
                        x-text="notificationCount > 99 ? '99+' : notificationCount">
                    </span>
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-4 lg:p-8 bg-gray-50 min-h-[calc(100vh-8rem)]">
            @yield('content')
        </div>
    </div>
</div>

<style>
    nav a {
        min-height: 44px;
    }

    nav a:hover {
        transform: translateX(2px);
    }
</style>
