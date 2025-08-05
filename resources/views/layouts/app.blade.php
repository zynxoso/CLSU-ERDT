<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CLSU-ERDT')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/CLSU-FAVICON.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(Auth::check() && Auth::user()->role !== 'scholar')
        @vite(['resources/css/admin-analytics.css'])
    @endif
    @yield('styles')

    <!-- Global Light Mode Pagination Styles -->
    <style>
        /* Main container */
        .pagination nav {
            background-color: #ffffff !important;
            color: #4b5563 !important;
        }

        /* Results info section */
        .pagination nav > div:first-child {
            color: white !important;
        }
        .pagination nav > div:first-child > p {
            color: #6b7280 !important;
        }
        .pagination nav > div:first-child span {
            color: #16a34a !important;
            font-weight: 600 !important;
        }

        /* Pagination buttons */
        .pagination nav > div:last-child > * {
            background-color: #ffffff !important;
            color: #16a34a !important;
            border-color: #dcfce7 !important;
            font-weight: 500 !important;
        }

        /* Active page */
        .pagination nav > div:last-child > span[aria-current="page"] {
            background-color: #16a34a !important;
            color: #ffffff !important;
            border-color: #16a34a !important;
        }

        /* Disabled buttons */
        .pagination nav > div:last-child > span.cursor-not-allowed {
            background-color: #f9fafb !important;
            color: #9ca3af !important;
            border-color: #e5e7eb !important;
        }

        /* SVG icons */
        .pagination nav svg {
            color: #4f46e5 !important;
            fill: currentColor !important;
        }

        /* Disabled SVG icons */
        .pagination nav > div:last-child > span.cursor-not-allowed svg {
            color: #9ca3af !important;
        }

        /* Text elements */
        .pagination nav p, .pagination nav span, .pagination nav div {
            color: #4b5563 !important;
        }

        /* Tooltip styles for manuscript titles */
        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            max-width: 300px;
            white-space: normal;
            word-wrap: break-word;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        [data-tooltip]:hover::before {
            content: '';
            position: absolute;
            bottom: 94%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
    
            z-index: 1000;
        }

        /* Ensure tooltips don't overflow on mobile */
        @media (max-width: 640px) {
            [data-tooltip]:hover::after {
                max-width: 250px;
                left: 0;
                transform: none;
            }

            [data-tooltip]:hover::before {
                left: 20px;
                transform: none;
            }
        }
    </style>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    @auth
        @if(Auth::user()->isScholar())
            <!-- Scholar Layout -->
            @include('layouts.scholar-navigation')
        @else
            <!-- Admin Layout -->
            @include('layouts.admin-navigation')
        @endif
    @else
        <!-- Guest Layout -->
        <div class="min-h-screen bg-gray-100">
            <main>
                @yield('content')
            </main>
        </div>
    @endauth

    <!-- Modal Manager for Livewire Components -->
    <livewire:admin.modal-manager />

    @yield('scripts')

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- SweetAlert2 CDN Fallback -->
    <script>
        // Check if SweetAlert is loaded, if not load from CDN
        if (typeof window.Swal === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js';
            script.onload = function() {
                window.Swal = Swal;
                // Initialize toast functions if not already available
                if (typeof window.toast === 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    
                    window.toast = {
                        success: (message) => Toast.fire({ icon: 'success', title: message }),
                        error: (message) => Toast.fire({ icon: 'error', title: message }),
                        warning: (message) => Toast.fire({ icon: 'warning', title: message }),
                        info: (message) => Toast.fire({ icon: 'info', title: message })
                    };
                }
            };
            script.onerror = function() {
                console.warn('Failed to load SweetAlert2 from CDN');
            };
            document.head.appendChild(script);
        }
    </script>

    <!-- SweetAlert Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for SweetAlert to be available
            function waitForSwal(callback) {
                if (typeof window.toast !== 'undefined' && typeof window.Swal !== 'undefined') {
                    callback();
                } else {
                    setTimeout(() => waitForSwal(callback), 100);
                }
            }

            waitForSwal(function() {
                // Success message
                @if(session('success'))
                    window.toast.success({!! json_encode(session('success')) !!});
                @endif

                // Error message
                @if(session('error'))
                    window.toast.error("{{ session('error') }}");
                @endif

                // Warning message
                @if(session('warning'))
                    window.toast.warning("{{ session('warning') }}");
                @endif

                // Info message
                @if(session('info'))
                    window.toast.info("{{ session('info') }}");
                @endif

                // Listen for Livewire events
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('scholarDeleted', () => {
                        window.toast.success('Scholar deleted successfully');
                    });
                });
            });
        });
    </script>

    <!-- Toast Notifications -->
    <div x-data="{
        toasts: [],
        addToast(toast) {
            const id = Date.now();
            this.toasts.push({ ...toast, id });
            setTimeout(() => this.removeToast(id), toast.duration || 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }"
    @show-toast.window="addToast($event.detail)"
    class="fixed top-4 right-4 z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div x-show="toast.type === 'success'" class="w-6 h-6 text-green-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div x-show="toast.type === 'error'" class="w-6 h-6 text-red-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div x-show="toast.type === 'info'" class="w-6 h-6 text-blue-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900" x-text="toast.title || 'Notification'"></p>
                            <p class="mt-1 text-sm text-gray-500" x-text="toast.message"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="removeToast(toast.id)"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</body>
</html>
