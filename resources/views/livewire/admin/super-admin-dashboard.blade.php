<div>
    <!-- Password Change Modal -->
    @if((auth()->user()->is_default_password || auth()->user()->must_change_password) && !session('password_warning_dismissed'))
    <div x-data="{ showModal: true }"
         x-show="showModal"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <!-- Background overlay with CLSU theme -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <!-- Enhanced modal design with CLSU green theme -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">
                <div class="sm:flex sm:items-start">
                    <!-- Updated icon with CLSU green -->
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-50 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <!-- Enhanced text content with CLSU styling -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                            @if(auth()->user()->must_change_password)
                                Password Change Required
                            @else
                                Security Notice
                            @endif
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">
                                @if(auth()->user()->must_change_password)
                                    Your password has expired and must be changed immediately for security reasons.
                                @else
                                    For enhanced security of your account, we recommend changing your default password to a strong, unique password.
                                @endif
                            </p>
                            <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100">
                                <p class="text-sm text-green-700">
                                    <span class="font-medium">Pro tip:</span> Use a combination of letters, numbers, and symbols to create a strong password that you'll remember.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Improved button design with CLSU green -->
                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                    <a href="{{ route('super_admin.password.change') }}"
                       class="w-full inline-flex justify-center items-center rounded-lg border border-transparent px-4 py-2.5 bg-green-600 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Change Password
                    </a>
                    <button type="button"
                            @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-lg border border-gray-200 px-4 py-2.5 bg-white text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        I'll do it later
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-6">
        <!-- Welcome Section -->
        <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 shadow-sm hover:shadow-md"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome, Super Admin {{ $user->name }}!</h1>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">Last updated: {{ $lastRefresh }}</span>
                    <button wire:click="refreshData"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg wire:loading.remove wire:target="refreshData" class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <svg wire:loading wire:target="refreshData" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="refreshData">Refresh</span>
                        <span wire:loading wire:target="refreshData">Updating...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-6"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800">System Overview</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <!-- Admin Users -->
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg hover:bg-indigo-50 cursor-pointer group relative"
                     x-data="{ showTooltip: false }"
                     @mouseenter="showTooltip = true"
                     @mouseleave="showTooltip = false"
                     wire:key="admin-users-{{ $adminUsers }}">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Admin Users</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 group-hover:bg-indigo-200">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-900 group-hover:text-indigo-700">{{ $adminUsers }}</div>
                        <div class="mt-1 text-xs text-gray-400">System administrators</div>
                    </div>
                    <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                        Number of admin users in the system. Click to manage admin users.
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg hover:bg-purple-50 cursor-pointer group relative"
                     x-data="{ showTooltip: false }"
                     @mouseenter="showTooltip = true"
                     @mouseleave="showTooltip = false"
                     wire:key="total-users-{{ $totalUsers }}">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Total Users</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 group-hover:bg-purple-200">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-900 group-hover:text-purple-700">{{ $totalUsers }}</div>
                        <div class="mt-1 text-xs text-gray-400">Total users in the system</div>
                    </div>
                    <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                        Total number of users in the system including scholars, admins, and super admins. Click to manage users.
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg hover:bg-emerald-50 cursor-pointer group relative"
                     x-data="{ showTooltip: false }"
                     @mouseenter="showTooltip = true"
                     @mouseleave="showTooltip = false"
                     wire:key="active-sessions-{{ $activeSessions }}">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Active Sessions</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 group-hover:bg-emerald-200">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-900 group-hover:text-emerald-700">{{ $activeSessions }}</div>
                        <div class="mt-1 text-xs text-gray-400">Currently online</div>
                    </div>
                    <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                        Number of users currently active in the system (last 30 minutes). Critical for monitoring system usage and security.
                    </div>
                </div>
            </div>
        </div>

        <!-- Super Admin Actions -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Super Admin Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('super_admin.user_management') }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">User Management</h4>
                        <p class="text-xs text-gray-500">Manage all users and roles</p>
                    </div>
                </a>

                <a href="{{ route('super_admin.system_settings') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">System Settings</h4>
                        <p class="text-xs text-gray-500">Configure system parameters</p>
                    </div>
                </a>

                <a href="{{ route('super_admin.system_configuration') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">System Configuration</h4>
                        <p class="text-xs text-gray-500">Academic calendar & scholarship parameters</p>
                    </div>
                </a>

                <a href="{{ route('super_admin.data_management') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Data Management</h4>
                        <p class="text-xs text-gray-500">Backup, restore & import/export</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-2 text-green-700 hover:text-green-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </path>
                </svg>
            </div>
        </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        .tooltip {
            position: relative;
        }
        .tooltip-text {
            visibility: hidden;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(51, 65, 85, 0.95);
            color: white;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            width: max-content;
            max-width: 250px;
            font-size: 0.75rem;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
        }
        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(51, 65, 85, 0.95) transparent transparent transparent;
        }
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .tooltip-popup {
            position: fixed;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(51, 65, 85, 0.95);
            color: white;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            width: max-content;
            max-width: 200px;
            font-size: 0.75rem;
            z-index: 100;
            pointer-events: none;
        }
        .tooltip-popup::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(51, 65, 85, 0.95) transparent transparent transparent;
        }
    </style>
</div>
