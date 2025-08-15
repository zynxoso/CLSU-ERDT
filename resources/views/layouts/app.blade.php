<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-role" content="{{ Auth::user()->role }}">
    <meta name="authenticated" content="true">
    <meta name="session-lifetime" content="{{ config('session.lifetime') }}">
    <meta name="user-type" content="{{ Auth::guard('scholar')->check() ? 'scholar' : 'admin' }}">
    @endauth

    <title>@yield('title', 'CLSU-ERDT')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/CLSU-FAVICON.png') }}">

    <!-- Fonts -->
    <link href="{{ asset('fonts/figtree-fonts.css') }}" rel="stylesheet" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(Auth::check() && Auth::user()->role !== 'scholar' && !request()->routeIs('admin.scholars.index'))
        @vite(['resources/css/admin.css'])
    @endif
    @yield('styles')

    <!-- Global Styles -->
    <style>
        [x-cloak]{display:none !important;}
        .pagination nav{background-color:#fff !important;color:#4b5563 !important;}
        .pagination nav>div:first-child{color:#fff !important;}
        .pagination nav>div:first-child>p{color:#6b7280 !important;}
        .pagination nav>div:first-child span{color:#16a34a !important;font-weight:600 !important;}
        .pagination nav>div:last-child>*{background-color:#fff !important;color:#16a34a !important;border-color:#dcfce7 !important;font-weight:500 !important;}
        .pagination nav>div:last-child>span[aria-current="page"]{background-color:#16a34a !important;color:#fff !important;border-color:#16a34a !important;}
        .pagination nav>div:last-child>span.cursor-not-allowed{background-color:#f9fafb !important;color:#9ca3af !important;border-color:#e5e7eb !important;}
        .pagination nav svg{color:#4f46e5 !important;fill:currentColor !important;}
        .pagination nav>div:last-child>span.cursor-not-allowed svg{color:#9ca3af !important;}
        .pagination nav p,.pagination nav span,.pagination nav div{color:#4b5563 !important;}
        [data-tooltip]{position:relative;}
        [data-tooltip]:hover::after{content:attr(data-tooltip);position:absolute;bottom:100%;left:50%;transform:translateX(-50%);color:#fff;padding:8px 12px;border-radius:6px;font-size:12px;white-space:nowrap;z-index:1000;max-width:300px;white-space:normal;word-wrap:break-word;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);}
        [data-tooltip]:hover::before{content:'';position:absolute;bottom:94%;left:50%;transform:translateX(-50%);border:5px solid transparent;z-index:1000;}
        @media (max-width:640px){[data-tooltip]:hover::after{max-width:250px;left:0;transform:none;}[data-tooltip]:hover::before{left:20px;transform:none;}}
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

    <!-- Global Loading Overlay -->
    @include('components.global-loading')

    <!-- Core Scripts -->
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    @auth
    <script src="{{ asset('js/session-timeout.js') }}"></script>
    @endauth
    
    @yield('scripts')
    

    
    <!-- Dashboard-specific scripts -->
    @if(request()->routeIs('scholar.dashboard', 'admin.dashboard', 'super_admin.dashboard') || str_contains(request()->path(), 'dashboard'))
        <script src="{{ asset('js/dashboard-loading.js') }}" defer></script>
    @endif

    <!-- Livewire Scripts (includes Alpine.js) -->
    @livewireScripts

    @if(Auth::check() && Auth::user()->role !== 'scholar')
        <script src="{{ asset('js/admin-navigation.js') }}" defer></script>
    @endif

    <!-- SweetAlert2 CDN Fallback -->
    <script>
        if(typeof window.Swal==='undefined'){
            const script=document.createElement('script');
            script.src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
            script.onload=()=>{
                window.Swal=Swal;
                const Toast=Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true});
                window.toast={success:m=>Toast.fire({icon:'success',title:m}),error:m=>Toast.fire({icon:'error',title:m}),warning:m=>Toast.fire({icon:'warning',title:m}),info:m=>Toast.fire({icon:'info',title:m})};
            };
            script.onerror=()=>console.warn('Failed to load SweetAlert2 from CDN');
            document.head.appendChild(script);
        }
    </script>

    <!-- Flash Messages & Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initializeApp() {
                if (typeof window.toast === 'undefined') {
                    setTimeout(initializeApp, 50);
                    return;
                }
                
                // Flash messages
                @if(session('success') && !request()->routeIs('scholar.fund-requests.show'))
                    window.toast.success({!! json_encode(session('success')) !!});
                @endif
                @if(session('error'))
                    window.toast.error("{{ session('error') }}");
                @endif
                @if(session('warning'))
                    window.toast.warning("{{ session('warning') }}");
                @endif
                @if(session('info'))
                    window.toast.info("{{ session('info') }}");
                @endif
                
                // Livewire events
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('scholarDeleted', () => window.toast.success('Scholar deleted successfully'));
                });
                

            }
            initializeApp();
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
                 x-transition:enter="transform ease-out duration-300"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="max-w-sm w-full bg-white shadow-lg rounded-lg ring-1 ring-black ring-opacity-5">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0 w-6 h-6" :class="{
                        'text-green-400': toast.type === 'success',
                        'text-red-400': toast.type === 'error', 
                        'text-blue-400': toast.type === 'info'
                    }">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path x-show="toast.type === 'error'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path x-show="toast.type === 'info'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900" x-text="toast.title || 'Notification'"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="toast.message"></p>
                    </div>
                    <button @click="removeToast(toast.id)" class="ml-4 text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</body>
</html>
