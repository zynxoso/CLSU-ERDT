<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CLSU-ERDT')</title>

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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

    @yield('scripts')

    <!-- SweetAlert Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success message
            @if(session('success'))
                window.toast.success("{{ session('success') }}");
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
        });
    </script>
</body>
</html>
