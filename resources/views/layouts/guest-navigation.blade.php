<nav class="bg-slate-900 text-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold">CLSU-ERDT</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-800">Login</a>
                {{-- Registration removed - accounts will be created by admin only --}}
            </div>
        </div>
    </div>
</nav>
