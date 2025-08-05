<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CLSU-ERDT</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/CLSU-FAVICON.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen" style="background-image: url('{{ asset('images/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover; background-position: center;">

    <!-- Modern CLSU-ERDT Scholar Login Design -->
    <div class="min-h-screen font-sans text-gray-900 antialiased flex items-center justify-center">
        <!-- Custom Scrollbar Styles -->
        <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #800000, #a0293d);
            border-radius: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #600000, #800000);
        }
        </style>

            <!-- Main Content -->
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-8 items-start justify-center max-w-6xl mx-auto">

                    <!-- Left Side - Login Form -->
                    <div class="w-full lg:w-2/5 xl:w-1/3">
                        <div class="bg-white/95 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
                            <!-- Decorative Elements -->
                            <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                                <div class="w-full h-full rounded-full" style="background: linear-gradient(135deg, #800000, #006400);"></div>
                            </div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 opacity-5">
                                <div class="w-full h-full rounded-full" style="background: linear-gradient(135deg, #1e40af, #800000);"></div>
                            </div>

                            <!-- Form Header -->
                            <div class="mb-6 text-center relative z-10">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl mb-3" style="background: linear-gradient(135deg, #800000, #a0293d);">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-1">Scholar Portal</h2>
                                <p class="text-sm text-gray-600">Sign in to access your scholarship account</p>
                            </div>

                            <!-- Login Form -->
                            <form x-data="{
                                loading: false,
                                email: '{{ old('email') }}',
                                password: '',
                                remember: {{ old('remember') ? 'true' : 'false' }},
                                submit() {
                                    this.loading = true;
                                    $el.submit();
                                }
                            }" method="POST" action="{{ route('scholar-login') }}" class="space-y-4 relative z-10" @submit.prevent="submit">
                                @csrf

                                <!-- Email Field -->
                                <div class="space-y-1">
                                    <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                            </svg>
                                        </div>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            x-model="email"
                                            class="w-full pl-12 pr-4 py-4 text-base border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-800 focus:border-red-800 transition-all duration-200 bg-white placeholder-gray-400"
                                            placeholder="Enter your email address"
                                            autocomplete="email"
                                            required
                                        >
                                    </div>
                                    @error('email')
                                        <div class="flex items-center mt-1">
                                            <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-red-600 text-xs font-medium">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="space-y-1">
                                    <div class="flex justify-between items-center">
                                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            x-model="password"
                                            class="w-full pl-12 pr-4 py-4 text-base border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-800 focus:border-red-800 transition-all duration-200 bg-white placeholder-gray-400"
                                            placeholder="Enter your password"
                                            autocomplete="current-password"
                                            required
                                        >
                                    </div>
                                    @error('password')
                                        <div class="flex items-center mt-1">
                                            <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-red-600 text-xs font-medium">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button
                                        type="submit"
                                        class="w-full py-3 px-4 text-sm font-bold text-white rounded-lg transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none relative overflow-hidden group"
                                        style="background: linear-gradient(135deg, #800000, #a0293d);"
                                        :class="{ 'opacity-75 cursor-not-allowed': loading }"
                                        :disabled="loading"
                                    >
                                        <!-- Button Background Animation -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-red-900 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>

                                        <span x-show="!loading" class="relative z-10 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Sign In to Portal
                                        </span>

                                        <span x-show="loading" class="relative z-10 flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-2 w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Signing In...
                                        </span>
                                    </button>
                                </div>
                            </form>

                            <!-- Quick Links Section -->
                            <div class="mt-6 relative z-10">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-200"></div>
                                    </div>
                                    <div class="relative flex justify-center text-xs">
                                        <span class="px-3 bg-white font-semibold text-gray-500">Quick Access</span>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <a href="{{ route('how-to-apply') }}" class="group flex items-center justify-center px-3 py-2 border border-gray-200 rounded-lg transition-all duration-200 text-xs font-semibold text-white bg-[#4CAF50] hover:bg-[#388E3C] hover:border-[#388E3C]">
                                        <svg class="h-4 w-4 mr-1 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        How to Apply
                                    </a>
                                    <a href="{{ route('about') }}" class="group flex items-center justify-center px-3 py-2 border border-gray-200 rounded-lg transition-all duration-200 text-xs font-semibold text-white bg-[#1976D2] hover:bg-[#1565C0] hover:border-[#1565C0]">
                                        <svg class="h-4 w-4 mr-1 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        About ERDT
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Announcements -->
                    <div class="w-full lg:w-3/5 xl:w-2/3">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl overflow-hidden shadow-xl border border-gray-100">
                            <!-- Header -->
                            <div class="px-6 py-4" style="background: #D32F2F;">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h2 class="text-lg font-bold text-white">Important Announcements</h2>
                                        <p class="text-red-100 text-xs mt-0.5">Stay updated with the latest news</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Announcements Content -->
                            <div class="p-6 max-h-[28rem] overflow-y-auto custom-scrollbar">
                                @forelse($announcements as $announcement)
                                    <div class="mb-4 p-4 rounded-xl border-l-4 {{ $announcement->badge_color ?? 'border-gray-300' }} bg-gradient-to-r from-gray-50 to-white hover:shadow-md transition-all duration-200 group">
                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-bold text-gray-800 text-base group-hover:text-gray-900 transition-colors duration-200">
                                                {{ $announcement->title }}
                                            </h3>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                                                @if($announcement->badge_label === 'URGENT')
                                                    style="background-color: #D32F2F; color: #FFFFFF; border: none;"
                                                @elseif($announcement->badge_label === 'EVENT')
                                                    style="background-color: #FFCA28; color: #424242; border: none;"
                                                @else
                                                    style="background-color: #E0E0E0; color: #424242; border: none;"
                                                @endif>
                                                {{ $announcement->badge_label ?? 'General' }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700 text-sm mb-3 leading-relaxed">
                                            {{ Str::limit($announcement->content, 150) }}
                                        </p>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Published: {{ $announcement->created_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Announcements</h3>
                                        <p class="text-sm text-gray-600 max-w-sm mx-auto">
                                            There are no announcements at this time. Please check back later for important updates.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </div>

    <form id="scholar-logout-form" action="{{ route('scholar-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>
</html>
