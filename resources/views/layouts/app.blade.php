<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CLSU-ERDT')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/erdt_logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(Auth::check() && Auth::user()->isScholar())
        @vite(['resources/css/analytics.css'])
    @else
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
            color: #4b5563 !important;
        }
        .pagination nav > div:first-child > p {
            color: #6b7280 !important;
        }
        .pagination nav > div:first-child span {
            color: #4f46e5 !important;
            font-weight: 600 !important;
        }

        /* Pagination buttons */
        .pagination nav > div:last-child > * {
            background-color: #ffffff !important;
            color: #4f46e5 !important;
            border-color: #e0e7ff !important;
            font-weight: 500 !important;
        }

        /* Active page */
        .pagination nav > div:last-child > span[aria-current="page"] {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-color: #4f46e5 !important;
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
            background-color: #1f2937;
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
            border-top-color: #1f2937;
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

    <!-- SweetAlert Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
</body>
</html>
