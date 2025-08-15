<div>
    <!-- Include the navigation component -->
    <x-navigation />

        <!-- Responsive Hero Section -->
        <div class="relative w-full min-h-screen sm:min-h-[70vh] md:min-h-[80vh] lg:min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('/images/Homepage-hero-bg.png');">
            <div class="absolute inset-0 bg-green-900 bg-opacity-60"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-4xl mx-auto relative z-10">
                        <h1 class="text-2xl md:text-4xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                            <span class="block">Welcome to the</span>
                            <span class="block text-green-100">CLSU-ERDT Portal</span>
                        </h1>
                        <h2 class="text-lg md:text-xl lg:text-2xl font-medium text-green-50 mb-6 leading-relaxed">
                            Streamlining Your Scholarship Journey
                        </h2>
                        <p class="text-base md:text-lg lg:text-xl text-green-50 mb-8 max-w-3xl mx-auto leading-relaxed">
                            This official portal for the Engineering Research & Development for Technology (ERDT) program at CLSU is designed to help you manage your scholarship, track your progress, and access resources seamlessly.
                        </p>

                        <!-- Call to Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-6 sm:gap-8 justify-center items-center">
                            <a href="{{ route('scholar.login') }}"
                               class="inline-flex items-center justify-center bg-white text-green-700 hover:bg-green-50 active:bg-green-100 focus:bg-green-50 focus:outline-none focus:ring-4 focus:ring-green-200 font-bold py-4 px-8 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl active:shadow-md text-base md:text-lg min-h-[48px] w-full sm:w-auto touch-manipulation"
                               role="button"
                               aria-label="Access Scholar Portal - Login to your scholarship account"
                               tabindex="0">
                                <svg class="h-5 w-5 md:h-6 md:w-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Scholar Portal</span>
                            </a>
                            <a href="{{ route('how-to-apply') }}"
                               class="inline-flex items-center justify-center bg-green-800 text-white hover:bg-green-900 active:bg-green-950 focus:bg-green-900 focus:outline-none focus:ring-4 focus:ring-green-300 font-bold py-4 px-8 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl active:shadow-md text-base md:text-lg border-2 border-green-800 min-h-[48px] w-full sm:w-auto touch-manipulation"
                               role="button"
                               aria-label="Apply Now - Start your CLSU-ERDT scholarship application"
                               tabindex="0">
                                <svg class="h-5 w-5 md:h-6 md:w-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Apply Now</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Statistics Section -->
    <div class="py-16 sm:py-20 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-12 max-w-6xl mx-auto">
                <div class="text-center bg-gray-50 rounded-lg p-6 sm:p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-green-700 mb-3 sm:mb-4">8</div>
                    <div class="text-gray-700 text-base sm:text-lg font-medium leading-tight">Partner Universities</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg p-6 sm:p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-green-700 mb-3 sm:mb-4">₱38K</div>
                    <div class="text-gray-700 text-base sm:text-lg font-medium leading-tight">Monthly Stipend</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg p-6 sm:p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-green-700 mb-3 sm:mb-4">200+</div>
                    <div class="text-gray-700 text-base sm:text-lg font-medium leading-tight">Scholars Supported</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg p-6 sm:p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-green-700 mb-3 sm:mb-4">15+</div>
                    <div class="text-gray-700 text-base sm:text-lg font-medium leading-tight">Research Areas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Benefits Section -->
    <div class="py-16 sm:py-20 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-12 sm:mb-16">
                <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-full text-sm sm:text-base font-medium mb-4 sm:mb-6 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Scholarship Benefits
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6 leading-tight">A System Built for Your Success</h2>
                <div class="w-16 sm:w-24 h-1.5 sm:h-2 mx-auto mb-6 sm:mb-8 rounded bg-green-600"></div>
                <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">The CLSU-ERDT portal provides a comprehensive suite of tools to support you throughout your scholarship.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 max-w-5xl mx-auto">
                <!-- Financial Support -->
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col sm:flex-row items-start mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 sm:p-4 rounded-lg mb-4 sm:mb-0 sm:mr-6">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Full Financial Support</h3>
                            <p class="text-base sm:text-lg text-gray-700 leading-relaxed">Submit fund requests, monitor their status, and view your disbursement history seamlessly.</p>
                        </div>
                    </div>
                </div>

                <!-- Research Excellence -->
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col sm:flex-row items-start mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 sm:p-4 rounded-lg mb-4 sm:mb-0 sm:mr-6">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Streamlined Research</h3>
                            <p class="text-base sm:text-lg text-gray-700 leading-relaxed">Upload manuscripts, track progress, and manage academic documents in one centralized location.</p>
                        </div>
                    </div>
                </div>

                <!-- Expert Faculty -->
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col sm:flex-row items-start mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 sm:p-4 rounded-lg mb-4 sm:mb-0 sm:mr-6">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Expert Guidance</h3>
                            <p class="text-base sm:text-lg text-gray-700 leading-relaxed">Stay informed with timely announcements and updates from distinguished faculty and staff.</p>
                        </div>
                    </div>
                </div>

                <!-- Career Opportunities -->
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 hover:shadow-lg transition-all duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col sm:flex-row items-start mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 sm:p-4 rounded-lg mb-4 sm:mb-0 sm:mr-6">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" /></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Career Opportunities</h3>
                            <p class="text-base sm:text-lg text-gray-700 leading-relaxed">Stay informed about CIP and connect with a network of industry partners for your future career.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="py-16 sm:py-20 lg:py-24 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6">Latest Announcements</h2>
                <div class="w-16 sm:w-24 h-1.5 sm:h-2 mx-auto mb-6 sm:mb-8 rounded bg-green-600"></div>
                <p class="text-lg sm:text-xl text-gray-700 leading-relaxed">Stay updated with the latest news, events, and important information.</p>
            </div>

            <div class="max-w-4xl mx-auto">
                @forelse($announcements as $announcement)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sm:p-8 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                            <div class="w-full">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">{{ $announcement->title }}</h3>
                                <div class="flex flex-col sm:flex-row sm:items-center text-sm sm:text-base text-gray-600 gap-2 sm:gap-0">
                                    <span class="inline-flex items-center px-3 sm:px-4 py-1 sm:py-2 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800 w-fit">
                                        {{ $announcement->badge_label }}
                                    </span>
                                    <span class="hidden sm:inline mx-3">·</span>
                                    <span class="text-sm sm:text-base">{{ $announcement->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-base sm:text-lg text-gray-700 leading-relaxed" x-data="{ expanded: false }">
                            <div x-show="!expanded" x-cloak>
                                <p>{{ Str::limit($announcement->content, 200) }}</p>
                                @if(strlen($announcement->content) > 200)
                                    <button @click="expanded = true" 
                                            class="text-green-600 hover:text-green-800 active:text-green-900 focus:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-200 font-medium mt-3 sm:mt-4 inline-flex items-center text-base sm:text-lg min-h-[48px] px-2 py-1 rounded touch-manipulation"
                                            role="button"
                                            aria-label="Read more about {{ $announcement->title }}"
                                            tabindex="0">
                                        Read more
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg>
                                    </button>
                                @endif
                            </div>
                            <div x-show="expanded" x-collapse x-cloak>
                                <p>{{ $announcement->content }}</p>
                                <button @click="expanded = false" 
                                        class="text-green-600 hover:text-green-800 active:text-green-900 focus:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-200 font-medium mt-3 sm:mt-4 inline-flex items-center text-base sm:text-lg min-h-[48px] px-2 py-1 rounded touch-manipulation"
                                        role="button"
                                        aria-label="Show less content for {{ $announcement->title }}"
                                        tabindex="0">
                                    Show less
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 sm:p-16 text-center">
                        <div class="flex justify-center mb-6 sm:mb-8">
                            <div class="bg-gray-100 p-4 sm:p-6 rounded-full">
                                <svg class="h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-600 mb-3 sm:mb-4">No Announcements</h3>
                        <p class="text-base sm:text-lg text-gray-600">Check back later for updates and important information.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="py-16 sm:py-20 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6">Explore Our Program</h2>
                <div class="w-16 sm:w-24 h-1.5 sm:h-2 mx-auto mb-6 sm:mb-8 rounded bg-green-600"></div>
                <p class="text-lg sm:text-xl text-gray-700 leading-relaxed">Everything you need to know about joining the CLSU-ERDT program.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 lg:gap-10 max-w-5xl mx-auto">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sm:p-8 hover:shadow-lg active:shadow-md focus-within:shadow-lg focus-within:ring-4 focus-within:ring-green-200 transition-all duration-300">
                    <div class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-green-100 text-green-700 rounded-lg mb-4 sm:mb-6 mx-auto">
                        <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 text-center mb-3 sm:mb-4">How to Apply</h3>
                    <p class="text-base sm:text-lg text-gray-700 text-center mb-6 sm:mb-8 leading-relaxed">Step-by-step guide to the application process, requirements, and important deadlines.</p>
                    <div class="text-center">
                        <a href="{{ route('how-to-apply') }}" 
                           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 font-medium text-base sm:text-lg rounded-lg transition-all duration-200 min-h-[48px] w-full sm:w-auto justify-center touch-manipulation"
                           role="button"
                           aria-label="Learn more about how to apply for CLSU-ERDT scholarship"
                           tabindex="0">
                            Learn More
                            <svg class="ml-2 sm:ml-3 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sm:p-8 hover:shadow-lg active:shadow-md focus-within:shadow-lg focus-within:ring-4 focus-within:ring-green-200 transition-all duration-300">
                    <div class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-green-100 text-green-700 rounded-lg mb-4 sm:mb-6 mx-auto">
                        <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 text-center mb-3 sm:mb-4">About CLSU-ERDT</h3>
                    <p class="text-base sm:text-lg text-gray-700 text-center mb-6 sm:mb-8 leading-relaxed">Discover our mission, vision, values, and the impact we're making in engineering education.</p>
                    <div class="text-center">
                        <a href="{{ route('about') }}" 
                           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 font-medium text-base sm:text-lg rounded-lg transition-all duration-200 min-h-[48px] w-full sm:w-auto justify-center touch-manipulation"
                           role="button"
                           aria-label="Learn more about CLSU-ERDT program"
                           tabindex="0">
                            Learn More
                            <svg class="ml-2 sm:ml-3 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sm:p-8 hover:shadow-lg active:shadow-md focus-within:shadow-lg focus-within:ring-4 focus-within:ring-green-200 transition-all duration-300">
                    <div class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-green-100 text-green-700 rounded-lg mb-4 sm:mb-6 mx-auto">
                        <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 text-center mb-3 sm:mb-4">Our History</h3>
                    <p class="text-base sm:text-lg text-gray-700 text-center mb-6 sm:mb-8 leading-relaxed">Explore our journey, milestones, and achievements in engineering research and development.</p>
                    <div class="text-center">
                        <a href="{{ route('history') }}" 
                           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 font-medium text-base sm:text-lg rounded-lg transition-all duration-200 min-h-[48px] w-full sm:w-auto justify-center touch-manipulation"
                           role="button"
                           aria-label="Learn more about CLSU-ERDT history"
                           tabindex="0">
                            Learn More
                            <svg class="ml-2 sm:ml-3 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="py-16 sm:py-20 lg:py-24 bg-gray-50 relative" style="background-image: url('/images/bg1.png'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-green-900 bg-opacity-70"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-100 mb-4 sm:mb-6">Ready to Shape the Future?</h2>
                <p class="text-lg sm:text-xl text-gray-200 mb-8 sm:mb-12 leading-relaxed">
                    Join CLSU-ERDT and become part of a community dedicated to engineering excellence. Our integrated online system is designed to support you every step of the way, from application to graduation. Your journey to becoming a world-class engineer starts here.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center">
                    <a href="{{ route('how-to-apply') }}" 
                       class="inline-flex items-center justify-center bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 font-bold py-4 px-8 sm:px-10 rounded-lg transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl active:shadow-md text-base sm:text-lg lg:text-xl min-h-[48px] w-full sm:w-auto touch-manipulation"
                       role="button"
                       aria-label="Start Your Application - Begin your CLSU-ERDT scholarship application process"
                       tabindex="0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 mr-2 sm:mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Start Your Application</span>
                    </a>
                    <a href="{{ route('scholar.login') }}" 
                       class="inline-flex items-center justify-center bg-white text-gray-700 hover:bg-gray-100 active:bg-gray-200 focus:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 font-bold py-4 px-8 sm:px-10 rounded-lg transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl active:shadow-md border border-gray-300 text-base sm:text-lg lg:text-xl min-h-[48px] w-full sm:w-auto touch-manipulation"
                       role="button"
                       aria-label="Access Portal - Login to your scholar portal account"
                       tabindex="0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 mr-2 sm:mr-3 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Access Portal</span>
                    </a>
                </div>
            </div>
        </div>
    </div>   
    <x-footer />
</div>
