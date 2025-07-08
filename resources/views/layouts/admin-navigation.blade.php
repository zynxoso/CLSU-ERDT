<!-- Admin Navigation with Professional Design -->
<div class="min-h-screen bg-gray-50" style="background-color: #FAFAFA;" x-data="{ sidebarOpen: false }" @resize.window="if (window.innerWidth > 1024) sidebarOpen = false">
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden"
         @click="sidebarOpen = false">
        <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
    </div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
         :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
         x-cloak>
        <div class="flex flex-col h-full">
            <!-- Logo and Mobile Close Button -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200 relative" style="background-color: #F5F5F5;">
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('super_admin.dashboard') }}" class="text-xl font-bold" style="color: #2E7D32;">
                    CLSU-ERDT
                </a>
                @else
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold" style="color: #2E7D32;">
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

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto" style="font-size: 15px;">
                @if(Auth::user()->role === 'super_admin')
                <!-- Super Admin Navigation -->
                <a href="{{ route('super_admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.dashboard') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('super_admin.dashboard') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.dashboard') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('super_admin.user_management') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.user_management') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('super_admin.user_management') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.user_management') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="truncate">User Management</span>
                </a>

                <!-- Content Management Section -->
                <div class="mt-6">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Content Management</h3>
                    </div>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('super_admin.application_timeline') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.application_timeline*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('super_admin.application_timeline*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                           @click="if (window.innerWidth < 1024) sidebarOpen = false">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.application_timeline*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Application Timeline</span>
                        </a>
                        <a href="{{ route('super_admin.data_management') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.data_management*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('super_admin.data_management*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                           @click="if (window.innerWidth < 1024) sidebarOpen = false">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.data_management*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                            <span class="truncate">Data Management</span>
                        </a>
                        <a href="{{ route('super_admin.website_management') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.website_management*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('super_admin.website_management*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                           @click="if (window.innerWidth < 1024) sidebarOpen = false">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.website_management*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                            </svg>
                            <span class="truncate">Website Management</span>
                        </a>
                        <a href="{{ route('super_admin.history_timeline') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.history_timeline*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('super_admin.history_timeline*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                           @click="if (window.innerWidth < 1024) sidebarOpen = false">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.history_timeline*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">History Timeline</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('super_admin.system_settings') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('super_admin.system_settings') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('super_admin.system_settings') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('super_admin.system_settings') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">System Settings</span>
                </a>
                @else
                <!-- Admin Navigation -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('admin.scholars.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.scholars.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.scholars.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.scholars.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="truncate">Scholars</span>
                </a>
                <a href="{{ route('admin.fund-requests.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.fund-requests.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.fund-requests.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.fund-requests.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="truncate">Fund Requests</span>
                </a>
                <a href="{{ route('admin.manuscripts.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.manuscripts.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.manuscripts.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.manuscripts.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="truncate">Manuscripts</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.notifications.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.notifications.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0v14z" />
                    </svg>
                    <span class="truncate">Notifications</span>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.audit-logs.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.audit-logs.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="truncate">Audit Logs</span>
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.reports.*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="truncate">Reports</span>
                </a>
                <a href="{{ route('admin.settings') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'text-white border-l-4' : 'hover:bg-gray-100' }}"
                   style="{{ request()->routeIs('admin.settings*') ? 'background-color: #2E7D32; border-left-color: #1B5E20;' : 'color: #424242;' }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.settings*') ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">Settings</span>
                </a>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200" style="background-color: #F5F5F5;">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border shadow-sm" style="background-color: #E8F5E8; border-color: #2E7D32;">
                            <span class="font-medium" style="color: #2E7D32;">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium truncate" style="color: #424242;">{{ Auth::user()->name }}</p>
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
                    document.addEventListener('DOMContentLoaded', function() {
                        const logoutButton = document.getElementById('logout-button');
                        const logoutForm = document.getElementById('logout-form');

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
    <div class="lg:pl-64 min-h-screen bg-white">
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <button @click="sidebarOpen = true"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset"
                    style="--tw-ring-color: #2E7D32;">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-lg font-semibold" style="color: #424242;">CLSU-ERDT Admin</h1>
            <div class="w-10"></div> <!-- Spacer for centering -->
        </div>

        <!-- Content Area -->
        <div class="p-4 lg:p-8" style="background-color: #FAFAFA; min-height: calc(100vh - 4rem);">
            @yield('content')
        </div>
    </div>
</div>

<!-- Additional CSS for responsive improvements -->
<style>
    /* Global Typography Improvements */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        line-height: 1.6;
        color: #424242;
    }

    /* Ensure proper scrolling on mobile */
    @media (max-width: 1024px) {
        body {
            overflow-x: hidden;
        }
    }

    /* Hide scrollbar for sidebar on webkit browsers */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.8);
    }

    /* Ensure proper touch targets on mobile */
    @media (max-width: 640px) {
        .nav-link {
            min-height: 44px;
        }
    }

    /* Enhanced button and interactive element styling */
    button, a {
        transition: all 0.2s ease-in-out;
    }

    /* Professional spacing */
    .space-y-1 > * + * {
        margin-top: 0.5rem;
    }
</style>
