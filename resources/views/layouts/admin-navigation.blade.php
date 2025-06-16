<!-- Admin Navigation -->
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('super_admin.dashboard') }}" class="text-xl font-bold text-gray-900">
                    CLSU-ERDT
                </a>
                @else
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">
                    CLSU-ERDT
                </a>
                @endif
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1">
                @if(Auth::user()->role === 'super_admin')
                <!-- Super Admin Navigation -->
                <a href="{{ route('super_admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('super_admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('super_admin.user_management') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('super_admin.user_management') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    User Management
                </a>
                <!-- Content Management Section -->
                <div class="mt-6">
                    <h4 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content Management</h4>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('super_admin.application_timeline') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('super_admin.application_timeline*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Application Timeline
                        </a>

                        <a href="{{ route('admin.important-notes.index') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.important-notes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Important Notes
                        </a>
                    </div>
                </div>
                <a href="{{ route('super_admin.system_settings') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('super_admin.system_settings') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    System Settings
                </a>
                @else
                <!-- Admin Navigation -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.scholars.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.scholars.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Scholars
                </a>
                <a href="{{ route('admin.fund-requests.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.fund-requests.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fund Requests
                </a>
                <!-- Documents section removed and integrated into fund requests -->
                <a href="{{ route('admin.manuscripts.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.manuscripts.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Manuscripts
                </a>


                <a href="{{ route('admin.audit-logs.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Logs
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports
                </a>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100 shadow-sm">
                            <span class="text-blue-600 font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">
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
                    <button type="button" id="logout-button" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-600 bg-white rounded-lg border border-gray-200 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const logoutButton = document.getElementById('logout-button');
                        const logoutForm = document.getElementById('logout-form');

                        logoutButton.addEventListener('click', function(e) {
                            e.preventDefault();

                            Swal.fire({
                                title: 'Logout Confirmation',
                                text: 'Are you sure you want to logout?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, logout!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    logoutForm.submit();
                                }
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pl-64 min-h-screen bg-white">
        <div class="p-8">
            @yield('content')
        </div>
    </div>
</div>
