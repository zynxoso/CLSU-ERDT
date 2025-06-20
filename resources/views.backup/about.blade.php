@extends('layouts.app')

@section('content')
    <!-- Enhanced Navigation with Glassmorphism -->
    <nav
        style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(34, 197, 94, 0.2);">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="relative">
                        <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo"
                            class="h-12 w-12 rounded-xl shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105">
                        <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            style="background: linear-gradient(to right, rgba(34, 197, 94, 0.2), rgba(127, 29, 29, 0.2));">
                        </div>
                    </div>
                    <div class="ml-4">
                        <span class="font-bold text-xl tracking-tight text-gray-800">CLSU-ERDT</span>
                        <div class="text-xs font-medium text-blue-800">Engineering Excellence</div>
                    </div>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        Home
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300"
                            style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('how-to-apply') }}"
                        class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        How to Apply
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300"
                            style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('about') }}" class="text-blue-800 font-semibold relative py-2">
                        About
                        <span class="absolute bottom-0 left-0 w-full h-0.5"
                            style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                    <a href="{{ route('history') }}"
                        class="text-gray-600 hover:text-blue-800 font-medium relative group py-2 transition-colors duration-200">
                        History
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300"
                            style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                    </a>
                </div>

                <button id="mobile-menu-button"
                    class="md:hidden text-gray-500 hover:text-blue-800 focus:outline-none p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4" style="border-top: 1px solid rgba(34, 197, 94, 0.2);">
                <a href="{{ route('home') }}"
                    class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">Home</a>
                <a href="{{ route('how-to-apply') }}"
                    class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">How
                    to Apply</a>
                <a href="{{ route('about') }}"
                    class="block py-3 text-blue-800 font-semibold bg-blue-50 rounded-lg px-3">About</a>
                <a href="{{ route('history') }}"
                    class="block py-3 text-gray-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg px-3 transition-colors duration-200">History</a>
            </div>
        </div>

        <!-- Reading Progress Bar -->
        <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
            <div id="reading-progress" class="h-full transition-all duration-300"
                style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gray-50 py-24 p-12">
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
                    class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500">
                    <div class="flex items-start mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-700 p-4 rounded-lg mr-6">
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
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-blue-100 text-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    About ERDT
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">What is ERDT?</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-blue-500 to-blue-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Understanding the Engineering Research & Development for
                    Technology program and its impact.</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-12 mb-8">
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
                                    class="bg-blue-100 text-blue-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
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
                                    class="bg-purple-100 text-purple-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
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

    <!-- Why Choose CLSU-ERDT Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-purple-100 text-purple-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Why Choose Us
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose CLSU-ERDT?</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-purple-500 to-purple-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Discover what makes our program stand out in engineering
                    education and research.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Excellence in Education -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Excellence in Education</h3>
                            <p class="text-base text-gray-600 mt-2">World-class curriculum designed by leading experts in
                                engineering and technology fields.</p>
                        </div>
                    </div>
                </div>

                <!-- Research Opportunities -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Research Opportunities</h3>
                            <p class="text-base text-gray-600 mt-2">Access to state-of-the-art laboratories and
                                cutting-edge research projects.</p>
                        </div>
                    </div>
                </div>

                <!-- Financial Support -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-purple-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-purple-100 text-purple-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Financial Support</h3>
                            <p class="text-base text-gray-600 mt-2">Comprehensive scholarship package including tuition,
                                stipend, and research funding.</p>
                        </div>
                    </div>
                </div>

                <!-- Expert Faculty -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-orange-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-orange-100 text-orange-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Expert Faculty</h3>
                            <p class="text-base text-gray-600 mt-2">Learn from distinguished faculty members with extensive
                                research and industry experience.</p>
                        </div>
                    </div>
                </div>

                <!-- Industry Connections -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-teal-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-teal-100 text-teal-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Industry Connections</h3>
                            <p class="text-base text-gray-600 mt-2">Strong partnerships with leading companies and
                                organizations for career opportunities.</p>
                        </div>
                    </div>
                </div>

                <!-- Global Recognition -->
                <div
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-red-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-red-100 text-red-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Global Recognition</h3>
                            <p class="text-base text-gray-600 mt-2">Internationally recognized program with graduates
                                working worldwide.</p>
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
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-orange-100 text-orange-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Faculty & Leadership
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Meet Our Distinguished Faculty</h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-orange-500 to-orange-700"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Learn from leading experts and researchers who are
                    shaping the future of engineering.</p>
            </div>

            <div class="max-w-6xl mx-auto">
                @if ($facultyMembers->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($facultyMembers as $faculty)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                @if ($faculty->photo)
                                    <div class="aspect-w-4 aspect-h-3">
                                        <img src="{{ asset('storage/' . $faculty->photo) }}" alt="{{ $faculty->name }}"
                                            class="w-full h-48 object-cover">
                                    </div>
                                @else
                                    <div
                                        class="h-48 bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center">
                                        <div class="bg-white p-4 rounded-full">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $faculty->name }}</h3>
                                    <p class="text-green-600 font-medium mb-3">{{ $faculty->position }}</p>
                                    @if ($faculty->specialization)
                                        <p class="text-gray-600 text-sm mb-3"><strong>Specialization:</strong>
                                            {{ $faculty->specialization }}</p>
                                    @endif
                                    @if ($faculty->bio)
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ Str::limit($faculty->bio, 120) }}</p>
                                    @endif
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
    </style>
@endsection
