<div>

    <!-- Main Content -->
    <div class="container mx-auto p-12 mt-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6 items-center justify-center">
            <!-- Left Side - Login Form -->
            <div class="w-full md:w-2/5 lg:w-1/3 bg-white p-6 rounded-xl shadow-lg">
                <div class="mb-5 text-center">
                    <div class="flex justify-center mb-3">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Scholar Login</h2>
                    <p class="text-sm text-gray-600">Access your CLSU-ERDT scholarship account</p>
                </div>

                <form wire:submit.prevent="login" class="space-y-4">
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" wire:model="email" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email address">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                            <a href="#" class="text-xs text-blue-700 hover:text-blue-900">Forgot password?</a>
                        </div>
                        <input type="password" id="password" wire:model="password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div class="text-red-500 text-xs mt-1 hidden" id="error-message"></div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember-me" wire:model="remember" type="checkbox" class="h-3 w-3 text-blue-600 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-xs text-gray-700">Remember me</label>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-800 text-white py-2 text-sm rounded-lg hover:bg-blue-900 transition font-medium" style="background-color: #800000;">Sign In</button>
                    </div>
                </form>

                <div class="mt-5">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500">Quick Links</span>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="{{ route('how-to-apply') }}" class="flex justify-center items-center py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            How to Apply
                        </a>
                        <a href="{{ route('about') }}" class="flex justify-center items-center py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            About Us
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="w-full md:w-3/5 lg:w-2/3 mb-6 ml-12">
                <div class="bg-white rounded-xl overflow-hidden shadow-xl">
                    <div class="bg-gradient-to-r from-red-700 to-red-800 p-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Important Announcements</h2>
                        </div>
                    </div>

                    <div class="p-6 max-h-96 overflow-y-auto">
                        @forelse($announcements as $announcement)
                            <!-- Dynamic Announcement Item -->
                            <div class="mb-6 border-l-4 pl-4 p-4 rounded-r-lg {{ $announcement->badge_color }}">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $announcement->title }}</h3>
                                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">{{ $announcement->badge_label }}</span>
                                </div>
                                <p class="text-gray-700 text-sm mb-2">
                                    {{ Str::limit($announcement->content, 200) }}
                                </p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Posted: {{ $announcement->created_at->format('F j, Y') }}
                                </div>
                            </div>
                        @empty
                            <!-- No Announcements -->
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                                <p class="mt-1 text-sm text-gray-500">Check back later for important updates.</p>
                            </div>
                        @endforelse

                        <!-- View More Link -->
                        <div class="text-center pt-4 border-t border-gray-200">
                            <a href="#" class="text-red-700 hover:text-red-800 text-sm font-medium inline-flex items-center">
                                View All Announcements
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
