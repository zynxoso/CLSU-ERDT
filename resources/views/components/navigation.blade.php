<!-- Enhanced Navigation with Glassmorphism -->
<nav style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(34, 197, 94, 0.2);">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center group">
                <div class="relative">
                    <img src="{{ asset('images/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-12 w-12 rounded-xl shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105">
                    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(to right, rgba(34, 197, 94, 0.2), rgba(127, 29, 29, 0.2));"></div>
                </div>
                <div class="ml-4">
                    <span class="font-bold text-xl tracking-tight text-gray-800">CLSU-ERDT</span>
                    <div class="text-xs font-medium text-blue-800">Engineering Excellence</div>
                </div>
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-800 font-semibold' : '' }}">
                    Home
                    <span class="absolute bottom-0 left-0 {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }} h-0.5 transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('how-to-apply') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200 {{ request()->routeIs('how-to-apply') ? 'text-blue-800 font-semibold' : '' }}">
                    How to Apply
                    <span class="absolute bottom-0 left-0 {{ request()->routeIs('how-to-apply') ? 'w-full' : 'w-0 group-hover:w-full' }} h-0.5 transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200 {{ request()->routeIs('about') ? 'text-blue-800 font-semibold' : '' }}">
                    About
                    <span class="absolute bottom-0 left-0 {{ request()->routeIs('about') ? 'w-full' : 'w-0 group-hover:w-full' }} h-0.5 transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('history') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200 {{ request()->routeIs('history') ? 'text-blue-800 font-semibold' : '' }}">
                    History
                    <span class="absolute bottom-0 left-0 {{ request()->routeIs('history') ? 'w-full' : 'w-0 group-hover:w-full' }} h-0.5 transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
            </div>

            <button id="mobile-menu-button" class="md:hidden text-gray-500 hover:text-blue-800 focus:outline-none p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4" style="border-top: 1px solid rgba(34, 197, 94, 0.2);">
            <a href="{{ route('home') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-800 font-semibold bg-blue-50' : '' }}">Home</a>
            <a href="{{ route('how-to-apply') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200 {{ request()->routeIs('how-to-apply') ? 'text-blue-800 font-semibold bg-blue-50' : '' }}">How to Apply</a>
            <a href="{{ route('about') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200 {{ request()->routeIs('about') ? 'text-blue-800 font-semibold bg-blue-50' : '' }}">About</a>
            <a href="{{ route('history') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200 {{ request()->routeIs('history') ? 'text-blue-800 font-semibold bg-blue-50' : '' }}">History</a>
        </div>
    </div>

    <!-- Reading Progress Bar -->
    <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
        <div id="reading-progress" class="h-full transition-all duration-300" style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
    </div>
</nav>

<!-- Mobile Menu Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>
