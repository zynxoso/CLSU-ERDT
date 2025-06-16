<div>
    <!-- Enhanced Navigation with Glassmorphism -->
    <nav style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(34, 197, 94, 0.2);">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="relative">
                        <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-12 w-12 rounded-xl shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105">
                        <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(to right, rgba(34, 197, 94, 0.2), rgba(127, 29, 29, 0.2));"></div>
                    </div>
                    <div class="ml-4">
                        <span class="font-bold text-xl tracking-tight text-gray-800">CLSU-ERDT</span>
                        <div class="text-xs font-medium text-blue-800">Engineering Excellence</div>
                    </div>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-blue-800 font-semibold relative py-2">
                        Home
                        <span class="absolute bottom-0 left-0 w-full h-0.5" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('how-to-apply') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        How to Apply
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        About
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('history') }}" class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        History
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
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
                <a href="{{ route('home') }}" class="block py-3 text-blue-800 font-semibold bg-blue-50 rounded-lg px-3">Home</a>
                <a href="{{ route('how-to-apply') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">How to Apply</a>
                <a href="{{ route('about') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">About</a>
                <a href="{{ route('history') }}" class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">History</a>
            </div>
        </div>

        <!-- Reading Progress Bar -->
        <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
            <div id="reading-progress" class="h-full transition-all duration-300" style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
        </div>
    </nav>

    <!-- Enhanced Hero Section -->
    <div class="relative p-12 overflow-hidden" style="background: linear-gradient(to bottom right, #228B22, rgb(59, 246, 87), #fff, #8B0000), url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover; background-blend-mode: overlay;">
        <!-- Dark overlay for better text visibility -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Animated particles background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-blue-300/40 rounded-full animate-ping"></div>
            <div class="absolute bottom-1/4 left-1/3 w-3 h-3 bg-green-300/20 rounded-full animate-bounce"></div>
        </div>

        <div class="container mx-auto p-12 relative z-10">
            <div class="text-center max-w-5xl mx-auto p-12">
                <h1 class="text-4xl md:text-7xl font-bold text-white mb-6 drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                    <span class="block">Welcome to</span>
                    <span class="block bg-clip-text text-transparent" style="background: linear-gradient(to right, #60a5fa, #34d399); -webkit-background-clip: text;">CLSU-ERDT</span>
                </h1>
                <h2 class="text-2xl md:text-4xl font-semibold text-white mb-8 drop-shadow-md" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                    Engineering Research & Development for Technology
                </h2>   

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12 max-w-6xl mx-auto">
                    <div class="backdrop-blur-sm rounded-2xl p-6 border border-white/30 shadow-lg text-center transform hover:scale-105 transition-all duration-300" style="background: rgba(255, 255, 255, 0.15);">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">8</div>
                        <div class="text-white/90 text-sm md:text-base drop-shadow-md">Partner Universities</div>
                    </div>
                    <div class="backdrop-blur-sm rounded-2xl p-6 border border-white/30 shadow-lg text-center transform hover:scale-105 transition-all duration-300" style="background: rgba(255, 255, 255, 0.15);">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">₱38K</div>
                        <div class="text-white/90 text-sm md:text-base drop-shadow-md">Monthly Stipend</div>
                    </div>
                    <div class="backdrop-blur-sm rounded-2xl p-6 border border-white/30 shadow-lg text-center transform hover:scale-105 transition-all duration-300" style="background: rgba(255, 255, 255, 0.15);">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">200+</div>
                        <div class="text-white/90 text-sm md:text-base drop-shadow-md">Scholars Supported</div>
                    </div>
                    <div class="backdrop-blur-sm rounded-2xl p-6 border border-white/30 shadow-lg text-center transform hover:scale-105 transition-all duration-300" style="background: rgba(255, 255, 255, 0.15);">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">15+</div>
                        <div class="text-white/90 text-sm md:text-base drop-shadow-md">Research Areas</div>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('scholar-login') }}"
                       class="inline-flex items-center bg-white text-blue-800 hover:bg-blue-50 font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Scholar Portal
                    </a>
                    <a href="{{ route('how-to-apply') }}"
                       class="inline-flex items-center text-white font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border-2 border-white hover:bg-white hover:text-blue-800">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Benefits Section -->
    <div class="py-20" style="background: linear-gradient(to bottom, #f0fdf4, #ffffff);">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Scholarship Benefits
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Why Choose CLSU-ERDT?</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover the comprehensive benefits and opportunities that make our program the premier choice for engineering excellence.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Financial Support -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-green-500">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-4 rounded-2xl mr-6 shadow-lg">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Full Financial Support</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                        Complete financial assistance covering tuition fees, monthly stipend, research allowance, and thesis support. Focus entirely on your academic excellence without financial concerns.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600 mb-1">₱38,000</div>
                            <div class="text-sm text-gray-600">Monthly Stipend (PhD)</div>
                        </div>
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600 mb-1">₱300K</div>
                            <div class="text-sm text-gray-600">Research Grant</div>
                        </div>
                    </div>
                </div>

                <!-- Research Excellence -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-blue-500">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-600 p-4 rounded-2xl mr-6 shadow-lg">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Research Excellence</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                        Access to state-of-the-art laboratories, cutting-edge equipment, and collaborative research opportunities with leading industry partners and international institutions.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">Modern research facilities</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">Industry partnerships</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">Publication support</span>
                        </div>
                    </div>
                </div>

                <!-- Expert Faculty -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-purple-500">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-600 p-4 rounded-2xl mr-6 shadow-lg">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">World-Class Faculty</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                        Learn from distinguished professors and researchers with extensive experience in cutting-edge engineering fields, industry applications, and international collaborations.
                    </p>
                    <div class="bg-purple-50 rounded-xl p-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-1">50+</div>
                            <div class="text-sm text-gray-600">Expert Faculty Members</div>
                        </div>
                    </div>
                </div>

                <!-- Career Opportunities -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-orange-500">
                    <div class="flex items-center mb-6">
                        <div class="bg-orange-600 p-4 rounded-2xl mr-6 shadow-lg">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Career Excellence</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                        Access to Career Incentive Program (CIP) and exceptional job placement opportunities in government agencies, private corporations, and international organizations.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">CIP opportunities</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">Industry connections</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-gray-700">Global opportunities</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="py-20 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Latest Updates
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Announcements</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #3b82f6, #16a34a);"></div>
                <p class="text-xl text-gray-600">Stay updated with the latest news, events, and important information</p>
            </div>

            <div class="max-w-4xl mx-auto">
                @forelse($announcements as $announcement)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-6 mb-6 transition-all duration-300 transform hover:-translate-y-1 border-l-4 {{ $announcement->badge_color === 'red' ? 'border-red-500' : ($announcement->badge_color === 'blue' ? 'border-blue-500' : ($announcement->badge_color === 'green' ? 'border-green-500' : ($announcement->badge_color === 'purple' ? 'border-purple-500' : 'border-yellow-500'))) }}">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $announcement->badge_color === 'red' ? 'bg-red-100 text-red-800' : ($announcement->badge_color === 'blue' ? 'bg-blue-100 text-blue-800' : ($announcement->badge_color === 'green' ? 'bg-green-100 text-green-800' : ($announcement->badge_color === 'purple' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                        {{ $announcement->badge_label }}
                                    </span>
                                    <span class="ml-3 text-sm text-gray-500">
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $announcement->title }}</h3>
                                <div class="text-gray-700 leading-relaxed" x-data="{ expanded: false }">
                                    <div x-show="!expanded">
                                        <p>{{ Str::limit($announcement->content, 150) }}</p>
                                        @if(strlen($announcement->content) > 150)
                                            <button @click="expanded = true" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-flex items-center">
                                                Read more
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div x-show="expanded" x-collapse>
                                        <p>{{ $announcement->content }}</p>
                                        <button @click="expanded = false" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-flex items-center">
                                            Show less
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="flex justify-center mb-6">
                            <div class="bg-gray-100 p-4 rounded-full">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Announcements</h3>
                        <p class="text-gray-500">Check back later for updates and important information.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Quick Access
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Explore Our Program</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #3b82f6, #16a34a);"></div>
                <p class="text-xl text-gray-600">Everything you need to know about joining the CLSU-ERDT program</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <a href="{{ route('how-to-apply') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-blue-500">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 group-hover:bg-blue-200 mb-6 transition-colors duration-300">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-blue-600 transition-colors duration-300">How to Apply</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Step-by-step guide to the application process, requirements, and important deadlines.</p>
                    <div class="flex items-center text-blue-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span>Learn More</span>
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>

                <a href="{{ route('about') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-green-500">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-green-100 group-hover:bg-green-200 mb-6 transition-colors duration-300">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-green-600 transition-colors duration-300">About CLSU-ERDT</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Discover our mission, vision, values, and the impact we're making in engineering education.</p>
                    <div class="flex items-center text-green-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span>Discover More</span>
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>

                <a href="{{ route('history') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-purple-500">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-100 group-hover:bg-purple-200 mb-6 transition-colors duration-300">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-purple-600 transition-colors duration-300">Our History</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Explore our journey, milestones, and achievements in engineering research and development.</p>
                    <div class="flex items-center text-purple-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span>Explore History</span>
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="py-20 relative overflow-hidden" style="background: linear-gradient(to right, #16a34a, #1e40af, #7c3aed);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="4"/></g></svg>');"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Shape the Future?</h2>
                <p class="text-xl md:text-2xl text-white/90 mb-12 leading-relaxed">
                    Join CLSU-ERDT and become part of a community dedicated to engineering excellence, innovation, and national development. Your journey to becoming a world-class engineer starts here.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('how-to-apply') }}" class="inline-flex items-center bg-white text-green-600 hover:bg-gray-100 font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Start Your Application
                    </a>
                    <a href="{{ route('scholar-login') }}" class="inline-flex items-center text-white font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border-2 border-white hover:bg-white hover:text-blue-600">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Access Portal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Reading progress bar
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progressBar = document.getElementById('reading-progress');
            if (progressBar) {
                progressBar.style.width = scrolled + '%';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.transform').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</div>
