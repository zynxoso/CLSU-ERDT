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

    <!-- Simplified Hero Section -->
    <div class="bg-gray-50 py-24">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                    <span class="block">Welcome to the</span>
                    <span class="block text-green-700">CLSU-ERDT Portal</span>
                </h1>
                <h2 class="text-xl md:text-2xl font-medium text-gray-600 mb-8">
                    Streamlining Your Scholarship Journey
                </h2>
                <p class="text-lg text-gray-700 mb-12 max-w-3xl mx-auto">
                    This official portal for the Engineering Research & Development for Technology (ERDT) program at CLSU is designed to help you manage your scholarship, track your progress, and access resources seamlessly.
                </p>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('scholar-login') }}"
                       class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Scholar Portal
                    </a>
                    <a href="{{ route('how-to-apply') }}"
                       class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                        <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">8</div>
                    <div class="text-gray-600 text-base">Partner Universities</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">₱38K</div>
                    <div class="text-gray-600 text-base">Monthly Stipend</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">200+</div>
                    <div class="text-gray-600 text-base">Scholars Supported</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">15+</div>
                    <div class="text-gray-600 text-base">Research Areas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Benefits Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Scholarship Benefits
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">A System Built for Your Success</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">The CLSU-ERDT portal provides a comprehensive suite of tools to support you throughout your scholarship.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Financial Support -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Full Financial Support</h3>
                            <p class="text-base text-gray-600 mt-2">Submit fund requests, monitor their status, and view your disbursement history seamlessly.</p>
                        </div>
                    </div>
                </div>

                <!-- Research Excellence -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Streamlined Research</h3>
                            <p class="text-base text-gray-600 mt-2">Upload manuscripts, track progress, and manage academic documents in one centralized location.</p>
                        </div>
                    </div>
                </div>

                <!-- Expert Faculty -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-purple-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-purple-100 text-purple-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Expert Guidance</h3>
                            <p class="text-base text-gray-600 mt-2">Stay informed with timely announcements and updates from distinguished faculty and staff.</p>
                        </div>
                    </div>
                </div>

                <!-- Career Opportunities -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-orange-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-orange-100 text-orange-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Career Opportunities</h3>
                            <p class="text-base text-gray-600 mt-2">Stay informed about CIP and connect with a network of industry partners for your future career.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Latest Announcements</h2>
                <p class="text-lg text-gray-600">Stay updated with the latest news, events, and important information.</p>
            </div>

            <div class="max-w-3xl mx-auto">
                @forelse($announcements as $announcement)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4 transition-shadow duration-300 hover:shadow-md">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $announcement->title }}</h3>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->badge_color === 'red' ? 'bg-red-100 text-red-800' : ($announcement->badge_color === 'blue' ? 'bg-blue-100 text-blue-800' : ($announcement->badge_color === 'green' ? 'bg-green-100 text-green-800' : ($announcement->badge_color === 'purple' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                        {{ $announcement->badge_label }}
                                    </span>
                                    <span class="mx-2">·</span>
                                    <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                                <div class="text-gray-700 leading-relaxed" x-data="{ expanded: false }">
                            <div x-show="!expanded" x-cloak>
                                <p>{{ Str::limit($announcement->content, 200) }}</p>
                                @if(strlen($announcement->content) > 200)
                                            <button @click="expanded = true" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-flex items-center">
                                                Read more
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                            <div x-show="expanded" x-collapse x-cloak>
                                        <p>{{ $announcement->content }}</p>
                                        <button @click="expanded = false" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-flex items-center">
                                            Show less
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
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
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Explore Our Program</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-blue-500 to-purple-500"></div>
                <p class="text-lg text-gray-600">Everything you need to know about joining the CLSU-ERDT program.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <a href="{{ route('how-to-apply') }}" class="group block bg-white rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors duration-300">How to Apply</h3>
                    </div>
                    </div>
                    <p class="text-base text-gray-600">Step-by-step guide to the application process, requirements, and important deadlines.</p>
                </a>

                <a href="{{ route('about') }}" class="group block bg-white rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 text-green-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition-colors duration-300">About CLSU-ERDT</h3>
                    </div>
                    </div>
                    <p class="text-base text-gray-600">Discover our mission, vision, values, and the impact we're making in engineering education.</p>
                </a>

                <a href="{{ route('history') }}" class="group block bg-white rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 text-purple-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-purple-600 transition-colors duration-300">Our History</h3>
                    </div>
                    </div>
                    <p class="text-base text-gray-600">Explore our journey, milestones, and achievements in engineering research and development.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Ready to Shape the Future?</h2>
                <p class="text-xl text-gray-700 mb-12 leading-relaxed">
                    Join CLSU-ERDT and become part of a community dedicated to engineering excellence. Our integrated online system is designed to support you every step of the way, from application to graduation. Your journey to becoming a world-class engineer starts here.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('how-to-apply') }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Start Your Application
                    </a>
                    <a href="{{ route('scholar-login') }}" class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                        <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.transform').forEach(el => {
            observer.observe(el);
        });
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</div>
