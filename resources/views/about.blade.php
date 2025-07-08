@extends('layouts.app')

@section('content')
    <!-- Include the navigation component -->
    <x-navigation />

    <!-- Hero Section -->
    <div class="bg-gray-50 py-12 p-12">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    About Us
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                    <span class="block">Engineering Excellence</span>
                    <span class="block text-green-700">at CLSU-ERDT</span>
                </h1>
                <h2 class="text-xl md:text-2xl font-medium text-gray-600 mb-8">
                    Fostering Advanced Education & Research
                </h2>
                <p class="text-lg text-gray-700 mb-12 max-w-3xl mx-auto">
                    Discover our mission to develop world-class engineers and researchers who will drive innovation and
                    technological advancement in the Philippines and beyond.
                </p>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('how-to-apply') }}"
                        class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Apply Now
                    </a>
                    <a href="#mission"
                        class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                        <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Our Mission
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
                    <div class="text-4xl font-bold text-green-700 mb-2">25+</div>
                    <div class="text-gray-600 text-base">Years of Excellence</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">500+</div>
                    <div class="text-gray-600 text-base">Graduates</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">8</div>
                    <div class="text-gray-600 text-base">Partner Universities</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-700 mb-2">15+</div>
                    <div class="text-gray-600 text-base">Research Areas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Vision Section -->
    <div class="py-16 bg-gray-50" id="mission">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Mission & Vision
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Purpose & Direction</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Driving innovation through advanced engineering
                    education and research excellence.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Mission -->
                <div
                    class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-4 rounded-lg mr-6">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Our Mission</h3>
                            <p class="text-gray-600 leading-relaxed">
                                To develop world-class engineers and researchers through advanced graduate education and
                                cutting-edge research programs that address national development priorities and contribute
                                to global technological advancement.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Vision -->
                <div
                    class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-4 rounded-lg mr-6">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Our Vision</h3>
                            <p class="text-gray-600 leading-relaxed">
                                To be the premier center of excellence in engineering research and development, producing
                                innovative leaders who drive sustainable technological solutions for national
                                competitiveness and global impact.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- What is ERDT Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    About ERDT
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">What is ERDT?</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Understanding the Engineering Research & Development for
                    Technology program and its impact.</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="bg-gradient-to-r from-green-50 to-green-50 rounded-2xl p-8 md:p-12 mb-8">
                    <div class="text-center max-w-4xl mx-auto">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Engineering Research & Development
                            for Technology</h3>
                        <p class="text-lg text-gray-700 leading-relaxed mb-8">
                            The ERDT program is a flagship initiative of the Department of Science and Technology (DOST)
                            designed to accelerate the country's technological development through advanced engineering
                            education and research. Established to address the critical need for highly skilled engineers
                            and researchers, ERDT has become the premier scholarship program for graduate studies in
                            engineering.
                        </p>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                                <div
                                    class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Advanced Education</h4>
                                <p class="text-gray-600 text-sm">Comprehensive graduate programs in engineering and
                                    technology fields.</p>
                            </div>
                            <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                                <div
                                    class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Cutting-edge Research</h4>
                                <p class="text-gray-600 text-sm">Innovative research projects addressing national
                                    development needs.</p>
                            </div>
                            <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                                <div
                                    class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Industry Collaboration</h4>
                                <p class="text-gray-600 text-sm">Strong partnerships with leading universities and
                                    industry.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About ERDT at CLSU Section -->
    <div class="py-16 bg-gradient-to-br from-green-50 to-green-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    ERDT at CLSU
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">About ERDT at CLSU</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Specialized focus on Agricultural and Biosystems Engineering for sustainable development</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-8 border border-green-100">
                    <div class="grid lg:grid-cols-2 gap-8 items-center">
                        <!-- Content -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-800">Agricultural & Biosystems Engineering Excellence</h3>
                            </div>
                            <p class="text-lg text-gray-700 leading-relaxed mb-6">
                                The Engineering Research and Development for Technology (ERDT) program at Central Luzon State University (CLSU) is part of a prestigious consortium of eight Philippine universities, funded by the Department of Science and Technology (DOST). Focused on <strong class="text-green-700">Agricultural and Biosystems Engineering (ABE)</strong>, CLSU's ERDT scholarship supports Master's and PhD students in conducting high-impact research to address national challenges like food security, sustainable agriculture, and environmental resilience.
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-green-50 rounded-lg p-6 text-center hover:bg-green-100 transition-all duration-300">
                                <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Food Security</h4>
                                <p class="text-gray-600 text-sm">Innovative solutions for sustainable food production</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center hover:bg-green-100 transition-all duration-300">
                                <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Sustainable Agriculture</h4>
                                <p class="text-gray-600 text-sm">Eco-friendly farming technologies and practices</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center hover:bg-green-100 transition-all duration-300">
                                <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Environmental Resilience</h4>
                                <p class="text-gray-600 text-sm">Climate-adaptive engineering solutions</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center hover:bg-green-100 transition-all duration-300">
                                <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Research Excellence</h4>
                                <p class="text-gray-600 text-sm">High-impact research addressing national challenges</p>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Banner -->
                    <div class="mt-8 bg-green-600 rounded-xl p-6 text-center">
                        <h4 class="text-xl font-bold text-white mb-2">Join CLSU's ERDT Program</h4>
                        <p class="text-green-100 mb-4">Be part of the next generation of agricultural engineers driving innovation for sustainable development</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <span class="inline-flex items-center text-white font-semibold">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Master's & PhD Programs Available
                            </span>
                            <span class="inline-flex items-center text-white font-semibold">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Full DOST Scholarship Support
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- University Consortium Logos Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-indigo-100 text-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Consortium Partners
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Partner Universities</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-indigo-500 to-indigo-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Collaborating with leading educational institutions across the Philippines</p>
            </div>

            <!-- Animated University Logos -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-lg py-8">
                <div class="university-logos-slider">
                    <div class="university-logos-track">
                        <!-- First set of logos -->
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/ATENEODEMANILA.png') }}" alt="Ateneo de Manila University" class="university-logo">
                            <span class="university-name">ADMU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/CLSU.png') }}" alt="Central Luzon State University" class="university-logo clsu-highlight">
                            <span class="university-name clsu-name">CLSU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/DLSU.png') }}" alt="De La Salle University" class="university-logo">
                            <span class="university-name">DLSU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/MAPUA.png') }}" alt="Mapua University" class="university-logo">
                            <span class="university-name">MU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/msu.png') }}" alt="Mindanao State University - IIT" class="university-logo">
                            <span class="university-name">MSU-IIT</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UP.png') }}" alt="University of the Philippines Diliman" class="university-logo">
                            <span class="university-name">UPD</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UP_LOSBANOS.png') }}" alt="University of the Philippines Los Banos" class="university-logo">
                            <span class="university-name">UPLB</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UNIVERSITY_OF_SANCARLOS.png') }}" alt="University of San Carlos" class="university-logo">
                            <span class="university-name">USC</span>
                        </div>

                        <!-- Duplicate set for seamless loop -->
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/ATENEODEMANILA.png') }}" alt="Ateneo de Manila University" class="university-logo">
                            <span class="university-name">ADMU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/CLSU.png') }}" alt="Central Luzon State University" class="university-logo clsu-highlight">
                            <span class="university-name clsu-name">CLSU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/DLSU.png') }}" alt="De La Salle University" class="university-logo">
                            <span class="university-name">DLSU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/MAPUA.png') }}" alt="Mapua University" class="university-logo">
                            <span class="university-name">MU</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/msu.png') }}" alt="Mindanao State University - IIT" class="university-logo">
                            <span class="university-name">MSU-IIT</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UP.png') }}" alt="University of the Philippines Diliman" class="university-logo">
                            <span class="university-name">UPD</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UP_LOSBANOS.png') }}" alt="University of the Philippines Los Banos" class="university-logo">
                            <span class="university-name">UPLB</span>
                        </div>
                        <div class="university-logo-item">
                            <img src="{{ asset('university_logo/UNIVERSITY_OF_SANCARLOS.png') }}" alt="University of San Carlos" class="university-logo">
                            <span class="university-name">USC</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Leadership Section -->
    <div class="py-16 bg-white" id="faculty">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Faculty & Leadership
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Meet Our Distinguished Faculty</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Learn from leading experts and researchers who are
                    shaping the future of engineering.</p>
            </div>

            <div class="max-w-6xl mx-auto">
                @if ($facultyMembers->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                        @foreach ($facultyMembers as $faculty)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                                <div class="relative w-full" style="aspect-ratio: 4/5; min-height: 320px;">
                                    @if ($faculty->photo_path)
                                        <img src="{{ asset('experts/' . $faculty->photo_path) }}" alt="{{ $faculty->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-green-100 flex items-center justify-center">
                                            <div class="bg-white p-4 rounded-full">
                                                <svg class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-0 left-0 w-full p-6 text-white"
                                        style="background: linear-gradient(to top, rgba(22,101,52,0.95) 0%, rgba(34,139,34,0.7) 50%, rgba(74,222,128,0.3) 100%);">
                                        <h3 class="text-md font-semibold mb-1 text-white">{{ $faculty->name }}</h3>
                                        <p class="text-indigo-200 font-medium mb-1 text-xs">{{ $faculty->position }}</p>
                                        {{-- <p class="text-gray-200 mb-1">{{ $faculty->specialization }}</p>
                                        <p class="text-sm text-gray-300">{{ $faculty->department }}</p> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <div class="flex justify-center mb-6">
                            <div class="bg-gray-100 p-4 rounded-full">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Faculty Information Coming Soon</h3>
                        <p class="text-gray-500">Faculty profiles will be available shortly.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Ready to Join Our Community?</h2>
                <p class="text-xl text-gray-700 mb-12 leading-relaxed">
                    Become part of a prestigious community of engineers and researchers who are making a difference. Start
                    your journey with CLSU-ERDT today and contribute to the advancement of engineering and technology in the
                    Philippines.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('how-to-apply') }}"
                        class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Apply Now
                    </a>
                    <a href="{{ route('history') }}"
                        class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                        <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Our History
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
            anchor.addEventListener('click', function(e) {
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

        /* University Logos Animation */
        .university-logos-slider {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .university-logos-track {
            display: flex;
            animation: slideLogos 30s linear infinite;
            width: calc(200% + 32px); /* Double width for seamless loop */
        }

        .university-logo-item {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0 1rem;
            min-width: 120px;
            transition: all 0.3s ease;
        }

        .university-logo-item:hover {
            transform: translateY(-5px);
        }

        .university-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: grayscale(100%) opacity(0.7);
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 8px;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .university-logo:hover {
            filter: grayscale(0%) opacity(1);
            transform: scale(1.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .university-logo.clsu-highlight {
            filter: grayscale(0%) opacity(1);
            border: 3px solid #16a34a;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
        }

        .university-name {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-align: center;
            transition: color 0.3s ease;
        }

        .university-name.clsu-name {
            color: #16a34a;
            font-weight: 700;
        }

        .university-logo-item:hover .university-name {
            color: #374151;
        }

        @keyframes slideLogos {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* Pause animation on hover */
        .university-logos-slider:hover .university-logos-track {
            animation-play-state: paused;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .university-logo {
                width: 60px;
                height: 60px;
            }

            .university-logo-item {
                min-width: 100px;
                margin: 0 0.5rem;
            }

            .university-logos-track {
                animation-duration: 25s;
            }
        }

        @media (max-width: 480px) {
            .university-logo {
                width: 50px;
                height: 50px;
            }

            .university-logo-item {
                min-width: 80px;
                padding: 0.5rem;
            }

            .university-name {
                font-size: 0.625rem;
            }
        }
    </style>

    <x-footer />
@endsection
