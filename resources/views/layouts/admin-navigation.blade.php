<div class="min-h-screen bg-gray-50" style="background-color:#FAFAFA;" x-data="{sidebarOpen:false,init(){this.$watch('sidebarOpen',value=>{if(value&&window.innerWidth>=1024){this.sidebarOpen=false;}});}}" @resize.window="if(window.innerWidth>=1024)sidebarOpen=false">
    <div x-show="sidebarOpen"
         x-cloak
         x-transition:enter="transition-all ease-out duration-200"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-sm"
         x-transition:leave="transition-all ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-sm"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 z-40 bg-gray-600/20 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false">
        <div class="absolute inset-0 bg-gray-600/50"></div>
    </div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
         :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
         x-cloak>
        <div class="flex flex-col h-full">
            <!-- Logo and Mobile Close Button -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200 relative" style="background-color: #F5F5F5;">
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('super_admin.dashboard') }}" class="text-xl font-bold" style="color: rgb(21 128 61);">
                    CLSU-ERDT
                </a>
                @else
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold" style="color: rgb(21 128 61);">
                    CLSU-ERDT
                </a>
                @endif

                <!-- Mobile Close Button -->
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 absolute right-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Enhanced Navigation with Better Visual Hierarchy -->
            <nav class="flex-1 px-4 py-4 overflow-y-auto space-y-6"
                 style="font-size: 15px;"
                 x-data="{
                     userMgmt: {{ request()->routeIs('super_admin.user_management*') ? 'true' : 'false' }},
                     dataMgmt: {{ request()->routeIs('super_admin.data_management*') ? 'true' : 'false' }},
                     systemMgmt: {{ request()->routeIs('super_admin.system*') ? 'true' : 'false' }}
                 }">
                @if(Auth::user()->role === 'super_admin')
                <!-- Enhanced Super Admin Navigation -->

                <!-- Main Section -->
                <div class="space-y-1">
                    <a href="{{ route('super_admin.dashboard') }}"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.dashboard') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                       style="{{ request()->routeIs('super_admin.dashboard') ? 'background-color: rgb(21 128 61); border-left-color: #1B5E20;' : 'color: rgb(64 64 64);' }}"
                       @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.dashboard') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>
                </div>

                <!-- User Management Dropdown -->
                <div class="mt-6">
                    <button @click="userMgmt = !userMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">User Management</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': userMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="userMgmt" class="dropdown-transition mt-2 ml-4 space-y-1">
                        <a href="{{ route('super_admin.user_management') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.user_management') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Users</span>
                        </a>
                    </div>
                </div>

                <!-- Data Management Dropdown -->
                <div class="mt-4">
                    <button @click="dataMgmt = !dataMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                            <span class="truncate">Data Management</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': dataMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="dataMgmt" class="dropdown-transition mt-2 ml-4 space-y-1">
                        <a href="{{ route('super_admin.data_management') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.data_management*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Data Management</span>
                        </a>
                    </div>
                </div>

                <!-- System Configuration Dropdown -->
                <div class="mt-4">
                    <button @click="systemMgmt = !systemMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">System</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': systemMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="systemMgmt" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-4 space-y-1">
                        <a href="{{ route('super_admin.system_settings') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.system_settings') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('super_admin.system_settings') ? 'background-color: rgb(21 128 61); border-left-color: #1B5E20;' : 'color: rgb(64 64 64);' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">System Settings</span>
                        </a>
                    </div>
                </div>
                @else
                <!-- Admin Navigation -->

                <!-- Main Section -->
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                           class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>
                </div>

                <!-- Scholar Management Dropdown -->
                <div class="mt-6" x-data="{ scholarMgmt: false }">
                    <button @click="scholarMgmt = !scholarMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="truncate">Scholar Management</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': scholarMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="scholarMgmt" class="dropdown-transition mt-2 ml-4 space-y-1">
                        <a href="{{ route('admin.scholars.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.scholars.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Scholars</span>
                        </a>
                        <a href="{{ route('admin.fund-requests.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.fund-requests.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Fund Requests</span>
                        </a>
                        <a href="{{ route('admin.manuscripts.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.manuscripts.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Manuscripts</span>
                        </a>
                        <a href="{{ route('admin.stipends.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.stipends.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Stipend Management</span>
                        </a>
                    </div>
                </div>

                <!-- Content Management Dropdown -->
                <div class="mt-4" x-data="{ contentMgmt: false }">
                    <button @click="contentMgmt = !contentMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                            </svg>
                            <span class="truncate">Content Management</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': contentMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="contentMgmt" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-4 space-y-1">
                        <a href="{{ route('admin.content-management.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.content-management*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('admin.content-management*') ? 'background-color: rgb(21 128 61); border-left-color: #1B5E20;' : 'color: rgb(64 64 64);' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Content</span>
                        </a>
                    </div>
                </div>

                <!-- Reports & Analytics Dropdown -->
                <div class="mt-4" x-data="{ reportsMgmt: false }">
                    <button @click="reportsMgmt = !reportsMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="truncate">Reports & Analytics</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': reportsMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="reportsMgmt" class="dropdown-transition mt-2 ml-4 space-y-1">
                        <a href="{{ route('admin.reports.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Reports</span>
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Audit Logs</span>
                        </a>
                    </div>
                </div>

                <!-- System Configuration Dropdown -->
                <div class="mt-4" x-data="{ adminSystemMgmt: false }">
                    <button @click="adminSystemMgmt = !adminSystemMgmt" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">System</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': adminSystemMgmt }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="adminSystemMgmt" class="dropdown-transition mt-2 ml-4 space-y-1">
                        <a href="{{ route('admin.settings') }}"
                           class="nav-link flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'nav-active' : 'nav-inactive' }}"
                           @click="setTimeout(() => { if (window.innerWidth < 1024) sidebarOpen = false }, 100)">
                            <span class="truncate">Settings</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200" style="background-color: #F5F5F5;">
                <!-- Notification Icon for Desktop -->
                <div class="mb-4 hidden lg:block">
                    @php
                        $notificationRoute = Auth::user()->role === 'super_admin' ? 'super_admin.notifications.index' : 'admin.notifications.index';
                        $routePattern = Auth::user()->role === 'super_admin' ? 'super_admin.notifications.*' : 'admin.notifications.*';
                        $isActive = request()->routeIs($routePattern);
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    @endphp
                    <a href="{{ route($notificationRoute) }}"
                       class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ $isActive ? 'nav-active' : 'nav-inactive' }}">
                        <div class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="truncate">Notifications</span>
                            @if($unreadCount > 0)
                                <span class="notification-badge ml-auto">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </div>
                    </a>
                </div>

                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border shadow-sm" style="background-color: #E8F5E8; border-color: rgb(21 128 61);">
                            <span class="font-medium" style="color: rgb(21 128 61);">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium truncate" style="color: rgb(64 64 64);">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">
                            @if(Auth::user()->role === 'super_admin')
                                Super Administrator
                            @else
                                Administrator
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Logout Button with SweetAlert -->
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="button" id="logout-button" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium bg-white rounded-lg border transition-all duration-200 hover:bg-red-50"
                            style="color: #d32f2f; border-color: #ffcdd2;">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="truncate">Logout</span>
                    </button>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        document.getElementById('logout-button').addEventListener('click', (e) => {
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
                                if (result.isConfirmed) document.getElementById('logout-form').submit();
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:pl-64 min-h-screen bg-white">  
        <!-- Desktop Header with Semester Chip -->
        <div class="hidden lg:flex items-center justify-end p-4 bg-white border-b border-gray-200">
            <livewire:components.current-semester-chip />
        </div>
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <button @click="sidebarOpen = true"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset"
                    style="--tw-ring-color: rgb(21 128 61);">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex flex-col items-center space-y-1">
                <h1 class="text-lg font-semibold" style="color: rgb(64 64 64);">CLSU-ERDT Admin</h1>
                <livewire:components.current-semester-chip />
            </div>

            <!-- Mobile Notification Icon -->
            <div class="relative">
                <a href="{{ route($notificationRoute) }}"
                   class="mobile-notification-btn p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset transition-all duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($unreadCount > 0)
                        <span class="mobile-notification-badge absolute -top-1 -right-1">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-4 lg:p-8" style="background-color: #FAFAFA; min-height: calc(100vh - 8rem);">
            @yield('content')
        </div>
    </div>
</div>

<style>
    body{font-family:theme(fontFamily.sans);font-size:15px;line-height:1.6;color:#404040;}
    @media (max-width:1024px){body{overflow-x:hidden;}}
    .overflow-y-auto::-webkit-scrollbar{width:4px;}
    .overflow-y-auto::-webkit-scrollbar-track{background:transparent;}
    .overflow-y-auto::-webkit-scrollbar-thumb{background:rgba(156,163,175,0.5);border-radius:2px;}
    .overflow-y-auto::-webkit-scrollbar-thumb:hover{background:rgba(156,163,175,0.8);}
    .nav-active{background-color:#158051;border-left:4px solid #1B5E20;color:white;}
    .nav-inactive{color:#404040;}
    .nav-inactive:hover{background-color:#f3f4f6;}
    .notification-badge,.mobile-notification-badge{display:inline-flex;align-items:center;justify-content:center;padding:0.25rem 0.5rem;font-size:0.75rem;font-weight:700;line-height:1;color:white;background-color:#dc2626;border-radius:9999px;min-width:1.25rem;height:1.25rem;}
    .mobile-notification-badge{transform:translate(50%,-50%);}
    .mobile-notification-btn{--tw-ring-color:#158051;}
    .dropdown-transition{transition:ease-out 200ms;}
    .dropdown-transition[x-transition\:enter-start]{opacity:0;transform:scale(0.95);}
    .dropdown-transition[x-transition\:enter-end]{opacity:1;transform:scale(1);}
    .dropdown-transition[x-transition\:leave]{transition:ease-in 150ms;}
    .dropdown-transition[x-transition\:leave-start]{opacity:1;transform:scale(1);}
    .dropdown-transition[x-transition\:leave-end]{opacity:0;transform:scale(0.95);}
    @media (max-width:640px){.nav-link{min-height:44px;}}
    button,a{transition:all 0.2s ease-in-out;}
    .clsu-erdt-swal-clean{border-radius:0.75rem !important;box-shadow:0 4px 24px 0 rgba(46,125,50,0.08);padding-top:2em !important;}
    .clsu-erdt-confirm-clean{background:#2E7D32 !important;color:#fff !important;border:none !important;border-radius:0.375rem !important;font-weight:600 !important;font-size:1em !important;padding:0.6em 2em !important;margin-right:0.5em;}
    .clsu-erdt-cancel-clean{background:#f3f4f6 !important;color:#374151 !important;border:none !important;border-radius:0.375rem !important;font-weight:600 !important;font-size:1em !important;padding:0.6em 2em !important;}
</style>
