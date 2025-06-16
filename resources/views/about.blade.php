@extends('layouts.app')

@section('content')
<!-- Simple Navigation -->
<nav style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(34, 197, 94, 0.2);">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('scholar-login') }}" class="flex items-center group">
                <div class="relative">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-10 w-10 rounded-lg shadow-sm group-hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-0 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(to right, rgba(34, 197, 94, 0.2), rgba(127, 29, 29, 0.2));"></div>
                </div>
                <div class="ml-3">
                    <span class="font-bold text-gray-800 text-xl tracking-tight">CLSU-ERDT</span>
                    <div class="text-xs text-green-600 font-medium">Engineering Excellence</div>
                </div>
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-green-600 font-medium relative group py-2">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #800000);"></span>
                </a>
                <a href="{{ route('how-to-apply') }}" class="text-gray-600 hover:text-green-600 font-medium relative group py-2">
                    How to Apply
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #800000);"></span>
                </a>
                <a href="{{ route('about') }}" class="text-green-600 font-semibold relative py-2">
                    About
                    <span class="absolute bottom-0 left-0 w-full h-0.5" style="background: linear-gradient(to right, #22c55e, #800000);"></span>
                </a>
                <a href="{{ route('history') }}" class="text-gray-600 hover:text-green-600 font-medium relative group py-2">
                    History
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #800000);"></span>
                </a>
            </div>

            <button id="mobile-menu-button" class="md:hidden text-gray-500 hover:text-green-600 focus:outline-none p-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4" style="border-top: 1px solid rgba(34, 197, 94, 0.2);">
            <a href="{{ route('home') }}" class="block py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg px-2 transition-colors duration-200">Home</a>
            <a href="{{ route('how-to-apply') }}" class="block py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg px-2 transition-colors duration-200">How to Apply</a>
            <a href="{{ route('about') }}" class="block py-3 text-green-600 font-semibold bg-green-50 rounded-lg px-2">About</a>
            <a href="{{ route('history') }}" class="block py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg px-2 transition-colors duration-200">History</a>
        </div>
    </div>

    <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
        <div id="reading-progress" class="h-full transition-all duration-300" style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
    </div>
</nav>

<!-- Hero Section -->
<div class="relative p-12 overflow-hidden" style="background: linear-gradient(135deg, #800000, #166534, #7f1d1d), url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover; background-blend-mode: overlay;">
    <!-- Dark overlay for better text visibility -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- Animated particles background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
        <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-green-300/40 rounded-full animate-ping"></div>
        <div class="absolute bottom-1/4 left-1/3 w-3 h-3 bg-red-300/20 rounded-full animate-bounce"></div>
    </div>

    <div class="container mx-auto p-12 relative z-10">
        <div class="text-center max-w-4xl mx-auto p-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                <span class="block">Fostering</span>
                <span class="block bg-clip-text text-transparent" style="background: linear-gradient(to right, #86efac, #ffffff); -webkit-background-clip: text;">Advanced Education</span>
                <span class="block">& Research Excellence</span>
            </h1>
            <p class="text-xl md:text-2xl text-white mb-8 max-w-3xl mx-auto leading-relaxed drop-shadow-md" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.6);">
                Engineering innovation for national development and global competitiveness
            </p>
            <a href="{{ route('how-to-apply') }}" class="inline-flex items-center text-white font-semibold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl" style="background: linear-gradient(to right, #16a34a, #7f1d1d);" onmouseover="this.style.background='linear-gradient(to right, #15803d, #991b1b)'" onmouseout="this.style.background='linear-gradient(to right, #16a34a, #7f1d1d)'">
                <span>Begin Your Journey</span>
                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- What is ERDT Section -->
<div class="p-12 mt-12" id="what-is-erdt" style="background: linear-gradient(to bottom, #f0fdf4, #ffffff);">
    <div class="container mx-auto px-4"  >
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">What is ERDT?</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #800000);"></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300">
                <div class="space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        The <strong class="text-green-600">Engineering Research and Development for Technology Program</strong> is a
                        graduate level scholarship program supported by the Department of Science and Technology - Science
                        Education Institute (DOST-SEI).
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        It is aimed at contributing to the creation of a critical mass of research scientists and engineers
                        who possess master's and doctoral degrees. The development of human capital is an important component
                        towards achieving the Philippines' sustainable, inclusive development and industrialization goals.
                    </p>
                    <div class="bg-green-50 rounded-xl p-6 border-l-4 border-green-500">
                        <p class="text-gray-700 font-medium">
                            ERDT scholars may pursue master's and doctoral studies in engineering and allied fields at any of
                            the eight delivering member-universities of the ERDT consortium.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl p-8 text-white" style="background: linear-gradient(to right, #16a34a, #15803d);">
                    <h3 class="text-2xl font-bold mb-4">Partner Universities</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>Ateneo de Manila University (ADMU)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>Central Luzon State University (CLSU)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>De La Salle University (DLSU)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>Mapua University (MU)</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl p-8 text-white" style="background: linear-gradient(to right, #800000, #660000);">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>Mindanao State University-Iligan Institute of Technology (MSU-IIT)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>University of the Philippines Diliman (UPD)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>University of San Carlos (USC)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span>University of the Philippines Los Baños (UPLB)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vision and Mission Section -->
<div class="p-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Vision and Mission</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #16a34a, #7f1d1d);"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="rounded-2xl p-8 shadow-lg border-l-4 border-green-600" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Our Vision</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        To be a leading center for engineering research and development, producing globally competitive engineers and innovators who drive sustainable progress and address societal challenges.
                    </p>
                </div>

                <div class="rounded-2xl p-8 shadow-lg border-l-4 border-red-800" style="background: linear-gradient(135deg, #fef2f2, #fee2e2);">
                    <div class="flex items-center mb-6">
                        <div class="bg-red-800 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Our Mission</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        To provide comprehensive financial assistance, access to cutting-edge research facilities, and mentorship from distinguished faculty members. We emphasize interdisciplinary research, innovation, and the application of engineering solutions to real-world challenges.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Faculty Expertise Carousel Section -->
<div class="p-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Connect with Other Members</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #16a34a, #7f1d1d);"></div>
                <p class="text-xl text-gray-600">Learn about our accomplished scientists and researchers who form the backbone of CLSU-ERDT program</p>
            </div>

            @if(isset($facultyMembers) && $facultyMembers->count() > 0)
                <div class="relative" id="facultyCarousel">
                    <!-- Carousel Container -->
                    <div class="overflow-hidden rounded-2xl">
                        <div class="carousel-track" id="carouselTrack" style="transform: translateX(0%);">
                            @foreach($facultyMembers->chunk(3) as $chunkIndex => $facultyChunk)
                                <div class="carousel-slide min-w-full">
                                    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-8 px-4">
                                        @foreach($facultyChunk as $faculty)
                                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 h-full flex flex-col">
                                                <div class="relative">
                                                    <!-- Profile Image with Fixed 5:7 Aspect Ratio -->
                                                    <div class="w-full relative overflow-hidden" style="aspect-ratio: 5/7; background: linear-gradient(135deg, #dcfce7, #fecaca);">
                                                        @if($faculty->photo_path && file_exists(storage_path('app/public/' . $faculty->photo_path)))
                                                            <img src="{{ asset('storage/' . $faculty->photo_path) }}" alt="{{ $faculty->name }}" class="absolute inset-0 w-full h-full object-cover object-center">
                                                        @else
                                                            <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                                                                <div class="w-32 h-32 bg-white rounded-full shadow-lg flex items-center justify-center">
                                                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- Quote Icon -->
                                                    <div class="absolute top-4 left-4">
                                                        <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(to right, #16a34a, #7f1d1d);">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="p-6 flex-1 flex flex-col">
                                                    <!-- Quote/Description with Fixed Height -->
                                                    <div class="mb-4 flex-1">
                                                        <p class="text-gray-600 italic text-sm leading-relaxed line-clamp-4">
                                                            "{{ Str::limit($faculty->research_description, 120) }}"
                                                        </p>
                                                    </div>

                                                    <!-- Faculty Info -->
                                                    <div class="border-t pt-4 mt-auto">
                                                        <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2">{{ $faculty->name }}</h3>
                                                        <p class="text-green-600 font-medium text-sm mb-2 line-clamp-1">{{ $faculty->position }}</p>
                                                        <p class="text-gray-600 text-sm line-clamp-1">{{ $faculty->department }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <div class="flex justify-center items-center mt-8 space-x-4">
                        <!-- Previous Button -->
                        <button id="prevBtn" class="p-3 rounded-full bg-gray-200 hover:bg-green-100 transition-colors duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-6 h-6 text-gray-600 hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Dots Indicator -->
                        <div class="flex space-x-2" id="dotsContainer">
                            @foreach($facultyMembers->chunk(3) as $chunkIndex => $facultyChunk)
                                <button class="carousel-dot w-3 h-3 rounded-full transition-colors duration-200 {{ $chunkIndex === 0 ? 'bg-green-600' : 'bg-gray-300' }} hover:scale-110" data-slide="{{ $chunkIndex }}"></button>
                            @endforeach
                        </div>

                        <!-- Next Button -->
                        <button id="nextBtn" class="p-3 rounded-full bg-gray-200 hover:bg-green-100 transition-colors duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-6 h-6 text-gray-600 hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-4.297a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No faculty members available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Key Statistics Section -->
<div class="p-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <span class="text-5xl font-bold text-green-600 block mb-3">200+</span>
                    <span class="text-gray-700 font-medium text-lg">Scholars Supported</span>
                </div>

                <div class="p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #fef2f2, #fee2e2);">
                    <span class="text-5xl font-bold text-red-800 block mb-3">15+</span>
                    <span class="text-gray-700 font-medium text-lg">Research Areas</span>
                </div>

                <div class="p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <span class="text-5xl font-bold text-green-600 block mb-3">50+</span>
                    <span class="text-gray-700 font-medium text-lg">Published Papers</span>
                </div>

                <div class="p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #fef2f2, #fee2e2);">
                    <span class="text-5xl font-bold text-red-800 block mb-3">8</span>
                    <span class="text-gray-700 font-medium text-lg">Partner Universities</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose CLSU-ERDT Section -->
<div class="p-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Why Choose CLSU-ERDT?</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #16a34a, #7f1d1d);"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover the unique advantages and opportunities that make CLSU-ERDT the premier choice for engineering research and development</p>
            </div>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8 mb-12">
                <!-- Financial Support -->
                <div class="rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-green-600" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Full Financial Support</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Comprehensive scholarship covering tuition fees, monthly stipend, thesis allowance, and research materials. Focus on your studies without financial worries.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Monthly stipend</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Tuition fee coverage</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Research allowance</li>
                    </ul>
                </div>

                <!-- World-Class Faculty -->
                <div class="rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4" style="background: linear-gradient(to bottom right, #fef2f2, #fee2e2); border-left: 4px solid #991b1b;">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-full mr-4" style="background-color: #991b1b;">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Expert Faculty</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Learn from distinguished professors and researchers with extensive experience in cutting-edge engineering fields and industry applications.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>PhD holders</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Industry experts</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Research mentors</li>
                    </ul>
                </div>

                <!-- Research Excellence -->
                <div class="rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-green-600" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Research Excellence</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Access to state-of-the-art laboratories, advanced equipment, and collaborative research opportunities with industry partners.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Modern facilities</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Industry partnerships</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Publication support</li>
                    </ul>
                </div>

                <!-- Network & Collaboration -->
                <div class="mt-4 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4" style="background: linear-gradient(to bottom right, #f0fdf4, #fee2e2); border-left: 4px solid #991b1b;">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-full mr-4" style="background-color: #991b1b;">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Strong Network</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Join a prestigious network of 8 partner universities and connect with fellow scholars, alumni, and industry professionals.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>8 partner universities</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Alumni network</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Industry connections</li>
                    </ul>
                </div>

                <!-- Career Opportunities -->
                <div class="mt-4 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4 border-green-600" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-3 rounded-full mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Career Advancement</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Access to Career Incentive Program (CIP) and excellent job placement opportunities in government and private sectors.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>CIP opportunities</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Job placement</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Career guidance</li>
                    </ul>
                </div>

                <!-- Innovation Focus -->
                <div class="mt-4 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-4" style="background: linear-gradient(to bottom right, #fef2f2, #fee2e2); border-left: 4px solid #991b1b;">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-full mr-4" style="background-color: #991b1b;">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Innovation & Impact</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Contribute to groundbreaking research that addresses real-world challenges and drives technological advancement in the Philippines.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Real-world impact</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Technology transfer</li>
                        <li class="flex items-center"><span class="w-2 h-2 rounded-full mr-2" style="background-color: #dc2626;"></span>Social contribution</li>
                    </ul>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="rounded-2xl p-8 text-center text-white" style="background: linear-gradient(to right, #16a34a, #991b1b);">
                <h3 class="text-2xl font-bold mb-4 text-white">Ready to Shape the Future of Engineering?</h3>
                <p class="text-lg mb-6" style="color: #ffffff; opacity: 0.9;">Join CLSU-ERDT and become part of a community dedicated to excellence, innovation, and national development.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('how-to-apply') }}" class="inline-flex items-center bg-white text-green-600 hover:text-green-700 font-semibold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        <span>Apply Now</span>
                        <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('scholar-login') }}" class="inline-flex items-center font-semibold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-green-600" style="color: #ffffff; border: 2px solid #ffffff;">
                        <span>Learn More</span>
                        <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Career Incentive Program Section -->
<div class="p-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Career Incentive Program</h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #16a34a, #7f1d1d);"></div>
            </div>

            <div class="rounded-2xl p-8 max-w-4xl mx-auto shadow-lg border-l-4 border-green-600" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                <div class="flex items-start">
                    <div class="bg-green-600 p-3 rounded-full mr-6 flex-shrink-0">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                            The Career Incentive Program (CIP) is a short-term scheme to address the administration's call to strengthen the country's S&T human resources and meet industry demands.
                        </p>

                        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                            It provides graduates of DOST-SEI programs the opportunity to work in research activities where they can contribute their knowledge and expertise.
                        </p>

                        <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                            MS and PhD graduates of the ERDT program and other interested scholar-graduates may apply to the CIP and shall be provided with a DOST agency/research institution where their specialization is needed.
                        </p>

                        <a href="https://sei.dost.gov.ph/index.php/programs-and-projects/scholarships/career-incentive-program" target="_blank" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium transition-colors duration-200">
                            Learn more about the Career Incentive Program
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="p-12" style="background: linear-gradient(to right, #16a34a, #7f1d1d);">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-6">Need Assistance?</h2>
                <p class="text-xl text-white/90">Get in touch with our team</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-white">
                <div class="text-center group">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 group-hover:bg-white/20 transition-all duration-300">
                        <svg class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-3">Email</h3>
                        <p><a href="mailto:erdt@clsu.edu.ph" class="hover:underline">erdt@clsu.edu.ph</a></p>
                    </div>
                </div>

                <div class="text-center group">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 group-hover:bg-white/20 transition-all duration-300">
                        <svg class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-3">Phone</h3>
                        <p>0920-9312126</p>
                    </div>
                </div>

                <div class="text-center group">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 group-hover:bg-white/20 transition-all duration-300">
                        <svg class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-3">Office</h3>
                        <p>CLSU-ERDT Office, Engineering Building<br>Central Luzon State University<br>Science City of Muñoz, Nueva Ecija</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <p class="text-white/90 text-lg">Office Hours: Monday to Friday, 8:00 AM to 5:00 PM</p>
            </div>
        </div>
    </div>
</div>

<!-- CSS and JavaScript for Carousel -->
<style>
    .carousel-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-slide {
        flex: 0 0 100%;
    }

    .carousel-dot.active {
        background-color: #16a34a !important;
    }

    .carousel-dot:hover {
        background-color: #22c55e;
    }

    /* Line clamp utilities for consistent text heights */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Ensure all faculty cards have equal height */
    .carousel-slide .grid > div {
        height: 100%;
        min-height: 500px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .carousel-slide .grid > div {
            min-height: 450px;
        }
    }

    @media (max-width: 640px) {
        .carousel-slide .grid > div {
            min-height: 400px;
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #16a34a;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #15803d;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reading Progress Bar
        const progressBar = document.getElementById('reading-progress');

        function updateReadingProgress() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollPercentage = (scrollTop / scrollHeight) * 100;

            if (progressBar) {
                progressBar.style.width = Math.min(scrollPercentage, 100) + '%';
            }
        }

        // Update progress on scroll
        window.addEventListener('scroll', updateReadingProgress);

        // Initial update
        updateReadingProgress();

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Faculty Carousel Functionality
        const carouselTrack = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dots = document.querySelectorAll('.carousel-dot');

        let currentSlide = 0;
        const totalSlides = dots.length;
        let autoSlideInterval;

        function updateCarousel() {
            const translateX = -currentSlide * 100;
            carouselTrack.style.transform = `translateX(${translateX}%)`;

            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateCarousel();
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 3000); // 3 seconds
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        // Event listeners
        if (nextBtn) nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });

        if (prevBtn) prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                goToSlide(index);
                startAutoSlide();
            });
        });

        // Pause auto-slide on hover
        const carouselContainer = document.getElementById('facultyCarousel');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);
        }

        // Start auto-slide
        if (totalSlides > 1) {
            startAutoSlide();
        }

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe all major sections
        document.querySelectorAll('section, .container > div').forEach(el => {
            observer.observe(el);
        });
    });
</script>

@endsection
