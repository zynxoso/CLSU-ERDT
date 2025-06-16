@extends('layouts.app')

@section('content')
<!-- Enhanced Navigation with Progress Indicator -->
<nav style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(34, 139, 34, 0.2);">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('scholar-login') }}" class="flex items-center group">
                <div class="relative">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-12 w-12 rounded-xl shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105">
                    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(to right, rgba(34, 139, 34, 0.2), rgba(139, 0, 0, 0.2));"></div>
                </div>
                <div class="ml-4">
                    <span class="font-bold text-xl tracking-tight" style="color: #374151;">CLSU-ERDT</span>
                    <div class="text-xs font-medium" style="color: #228B22;">Engineering Excellence</div>
                </div>
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="font-medium relative group py-2 px-1 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'" onmouseout="this.style.color='#6B7280'">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #228B22, #8B0000);"></span>
                </a>
                <a href="{{ route('how-to-apply') }}" class="font-medium relative group py-2 px-1 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'" onmouseout="this.style.color='#6B7280'">
                    How to Apply
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #228B22, #8B0000);"></span>
                </a>
                <a href="{{ route('about') }}" class="font-medium relative group py-2 px-1 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'" onmouseout="this.style.color='#6B7280'">
                    About
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #228B22, #8B0000);"></span>
                </a>
                <a href="{{ route('history') }}" class="font-semibold relative py-2 px-1" style="color: #228B22;">
                    History
                    <span class="absolute bottom-0 left-0 w-full h-0.5" style="background: linear-gradient(to right, #228B22, #8B0000);"></span>
                </a>
            </div>

            <button id="mobile-menu-button" class="md:hidden focus:outline-none p-2 rounded-lg transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'; this.style.backgroundColor='rgba(34, 139, 34, 0.1)'" onmouseout="this.style.color='#6B7280'; this.style.backgroundColor='transparent'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4" style="border-top: 1px solid rgba(34, 139, 34, 0.2);">
            <a href="{{ route('home') }}" class="block py-3 rounded-lg px-3 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'; this.style.backgroundColor='rgba(34, 139, 34, 0.1)'" onmouseout="this.style.color='#6B7280'; this.style.backgroundColor='transparent'">Home</a>
            <a href="{{ route('how-to-apply') }}" class="block py-3 rounded-lg px-3 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'; this.style.backgroundColor='rgba(34, 139, 34, 0.1)'" onmouseout="this.style.color='#6B7280'; this.style.backgroundColor='transparent'">How to Apply</a>
            <a href="{{ route('about') }}" class="block py-3 rounded-lg px-3 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#228B22'; this.style.backgroundColor='rgba(34, 139, 34, 0.1)'" onmouseout="this.style.color='#6B7280'; this.style.backgroundColor='transparent'">About</a>
            <a href="{{ route('history') }}" class="block py-3 font-semibold rounded-lg px-3" style="color: #228B22; background-color: rgba(34, 139, 34, 0.1);">History</a>
        </div>
    </div>

    <!-- Reading Progress Bar -->
    <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
        <div id="reading-progress" class="h-full transition-all duration-300" style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
    </div>
</nav>

<!-- Enhanced Hero Section with Parallax Effect -->

<div class="relative overflow-hidden" id="hero-section">

    <div class="relative p-12 overflow-hidden" style="background: linear-gradient(to bottom right, #8B0000, #A52A2A, #fff, #228B22), url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover; background-blend-mode: overlay;">
        <!-- Dark overlay for better text visibility -->
        <div class="absolute inset-0" style="background-color: rgba(0, 0, 0, 0.5);"></div>

        <div class="container mx-auto p-12 relative z-10"  >
            <div class="text-center max-w-4xl mx-auto p-12">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 drop-shadow-lg" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                    <span class="inline-block">Our Rich History</span>
                </h1>
                <div class="text-xl md:text-2xl mb-4 font-semibold drop-shadow-md" style="color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                    Central Luzon State University - Engineering Research and Development for Technology
                </div>

        </div>
    </div>

    </div>

<!-- Enhanced Introduction Section -->
<div class="p-12 md:py-20 md:p-12" style="background: linear-gradient(to bottom, white, #f9fafb);" id="introduction" >
    <div class="container mx-auto px-4 md:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(34, 139, 34, 0.1); color: #228B22;">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Our Story
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight" style="color: #374151;">
                {{ $introContent['title'] ?? 'Building Engineering Excellence' }}
            </h2>
            @if(isset($introContent['paragraph_1']))
                <p class="text-lg leading-relaxed mb-6 max-w-3xl mx-auto" style="color: #4B5563;">{{ $introContent['paragraph_1'] }}</p>
            @endif
            @if(isset($introContent['paragraph_2']))
                <p class="text-lg leading-relaxed max-w-3xl mx-auto" style="color: #4B5563;">{{ $introContent['paragraph_2'] }}</p>
            @endif
        </div>
    </div>
</div>


<!-- Enhanced Key Achievements Section -->
<div class="p-12 md:py-20 md:p-12" style="background-color: white;" id="achievements">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(139, 0, 0, 0.1); color: #8B0000;">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                Key Achievements
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6" style="color: #374151;">Milestones of Excellence</h2>
            <p class="text-lg max-w-2xl mx-auto" style="color: #6B7280;">
                Our commitment to engineering excellence has resulted in remarkable achievements that continue to impact communities and industries.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12 lg:gap-16 max-w-6xl mx-auto">
            @foreach($achievements as $achievement)
                <div class="achievement-card group rounded-2xl p-8 relative overflow-hidden" style="background: linear-gradient(135deg, rgba(34, 139, 34, 0.1), rgba(34, 139, 34, 0.05)); border: 1px solid rgba(34, 139, 34, 0.2);">
                    <!-- Background Pattern -->
                    {{-- <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16" style="background-color: rgba(34, 139, 34, 0.1);"></div> --}}

                    <div class="relative z-10">
                        <div class="flex items-start mb-6">
                            <div class="p-4 rounded-2xl shadow-lg" style="background-color: #228B22;">
                                <svg class="h-8 w-8" style="color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $achievement->icon }}" />
                                </svg>
                            </div>
                            <div class="ml-6 flex-1" style="margin-left:10px;">
                                <h3 class="text-2xl font-bold mb-2" style="color: #374151;">
                                    {{ $achievement->title }}
                                </h3>
                                @if($achievement->statistic)
                                    <div class="text-4xl font-bold mb-2 counter" style="color: #228B22;" data-target="{{ $achievement->statistic }}">
                                        0{{ $achievement->statistic_unit ?? '' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <p class="leading-relaxed text-lg" style="color: #4B5563;">{{ $achievement->description }}</p>

                        <!-- Progress Bar for Statistics -->
                        @if($achievement->statistic)
                            <div class="mt-6">
                                <div class="rounded-full h-2 overflow-hidden" style="background-color: rgba(34, 139, 34, 0.2);">
                                    <div class="h-full rounded-full achievement-progress" style="background-color: #228B22; width: 85%;"
                                         data-width="85%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Enhanced Future Vision Section -->
<div class="p-12 md:py-20 md:px-12" style="background: linear-gradient(135deg, #f9fafb, rgba(34, 139, 34, 0.05));" id="vision">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(34, 139, 34, 0.1); color: #228B22;">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Future Vision
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6" style="color: #374151;">
                {{ $visionContent['title'] ?? 'Our Vision for the Future' }}
            </h2>
        </div>

        <div class="rounded-3xl shadow-xl p-8 md:p-16 lg:p-20 max-w-6xl mx-auto relative overflow-hidden" style="background-color: white;">
            <!-- Background Decoration -->
            <div class="relative z-10">
                @if(isset($visionContent['description']))
                    <p class="text-xl mb-12 leading-relaxed text-center max-w-4xl mx-auto" style="color: #4B5563;">
                        {{ $visionContent['description'] }}
                    </p>
                @endif

                <div class="grid md:grid-cols-2 gap-16 lg:gap-20">
                    <div class="space-y-10">
                        <div>
                            <div class="flex items-center mb-8">
                                <div class="p-3 rounded-xl shadow-lg" style="background-color: #228B22;">
                                    <svg class="h-6 w-6" style="color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold ml-4" style="color: #374151;">Strategic Initiatives</h3>
                            </div>
                            <div class="space-y-6">
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(34, 139, 34, 0.1);" onmouseover="this.style.backgroundColor='rgba(34, 139, 34, 0.2)'" onmouseout="this.style.backgroundColor='rgba(34, 139, 34, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #228B22;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Center for Agricultural Engineering Innovation</h4>
                                        <p class="text-sm" style="color: #6B7280;">Establishing a world-class facility for cutting-edge agricultural technology research</p>
                                    </div>
                                </div>
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(34, 139, 34, 0.1);" onmouseover="this.style.backgroundColor='rgba(34, 139, 34, 0.2)'" onmouseout="this.style.backgroundColor='rgba(34, 139, 34, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #228B22;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Entrepreneurship Program</h4>
                                        <p class="text-sm" style="color: #6B7280;">Launching comprehensive entrepreneurship training for engineering scholars</p>
                                    </div>
                                </div>
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(34, 139, 34, 0.1);" onmouseover="this.style.backgroundColor='rgba(34, 139, 34, 0.2)'" onmouseout="this.style.backgroundColor='rgba(34, 139, 34, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #228B22;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Digital Repository</h4>
                                        <p class="text-sm" style="color: #6B7280;">Creating a comprehensive digital archive of research outputs and innovations</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div>
                            <div class="flex items-center mb-8">
                                <div class="p-3 rounded-xl shadow-lg" style="background-color: #8B0000;">
                                    <svg class="h-6 w-6" style="color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold ml-4" style="color: #374151;">Research Focus Areas</h3>
                            </div>
                            <div class="space-y-6">
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(139, 0, 0, 0.1);" onmouseover="this.style.backgroundColor='rgba(139, 0, 0, 0.2)'" onmouseout="this.style.backgroundColor='rgba(139, 0, 0, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #8B0000;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Smart Agriculture</h4>
                                        <p class="text-sm" style="color: #6B7280;">Advanced precision farming technologies and IoT solutions</p>
                                    </div>
                                </div>
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(139, 0, 0, 0.1);" onmouseover="this.style.backgroundColor='rgba(139, 0, 0, 0.2)'" onmouseout="this.style.backgroundColor='rgba(139, 0, 0, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #8B0000;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Renewable Energy</h4>
                                        <p class="text-sm" style="color: #6B7280;">Sustainable energy systems for rural and agricultural applications</p>
                                    </div>
                                </div>
                                <div class="flex items-start group">
                                    <div class="p-2 rounded-lg mr-4 transition-colors duration-200" style="background-color: rgba(139, 0, 0, 0.1);" onmouseover="this.style.backgroundColor='rgba(139, 0, 0, 0.2)'" onmouseout="this.style.backgroundColor='rgba(139, 0, 0, 0.1)'">
                                        <svg class="h-5 w-5" style="color: #8B0000;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1" style="color: #374151;">Climate Resilience</h4>
                                        <p class="text-sm" style="color: #6B7280;">Infrastructure and water management solutions for climate adaptation</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('how-to-apply') }}" class="inline-flex items-center px-8 py-4 font-semibold rounded-full transition-all duration-300 shadow-lg hover:shadow-xl" style="background: linear-gradient(to right, #228B22, #8B0000); color: white;" onmouseover="this.style.background='linear-gradient(to right, #1e7e1e, #7a0000)'" onmouseout="this.style.background='linear-gradient(to right, #228B22, #8B0000)'">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Be Part of Our Future
                        <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Contact Section -->
<div class="p-12 relative overflow-hidden" style="background: linear-gradient(to right, #228B22, #1e7e1e, #8B0000);" id="contact">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
                 <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="4"/></g></svg></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $contactContent['title'] ?? 'Need Assistance?' }}
            </h2>
            <p class="text-xl text-white/90 max-w-2xl mx-auto">
                Get in touch with our team for inquiries about the ERDT program, applications, or research opportunities.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-12 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-10 hover:bg-white/20 transition-colors duration-300 border border-white/20">
                    <div class="bg-white/20 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <svg class="h-12 w-12 text-white mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Email</h3>
                    <p class="text-white/90 mb-4">Send us your inquiries</p>
                    <a href="mailto:{{ $contactContent['email'] ?? 'erdt@clsu.edu.ph' }}" class="text-white hover:text-green-200 font-medium transition-colors duration-200 underline decoration-2 underline-offset-4">
                        {{ $contactContent['email'] ?? 'erdt@clsu.edu.ph' }}
                    </a>
                </div>
            </div>

            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-10 hover:bg-white/20 transition-colors duration-300 border border-white/20">
                    <div class="bg-white/20 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <svg class="h-12 w-12 text-white mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Phone</h3>
                    <p class="text-white/90 mb-4">Call us directly</p>
                    <p class="text-white font-medium text-lg">{{ $contactContent['phone'] ?? '0920-9312126' }}</p>
                </div>
            </div>

            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-10 hover:bg-white/20 transition-colors duration-300 border border-white/20">
                    <div class="bg-white/20 rounded-full p-4 w-20 h-20 mx-auto mb-6">
                        <svg class="h-12 w-12 text-white mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Office</h3>
                    <p class="text-white/90 mb-4">Visit us at</p>
                    <p class="text-white leading-relaxed">
                        {!! $contactContent['office_address'] ?? 'CLSU-ERDT Office, Engineering Building<br>Central Luzon State University<br>Science City of Muñoz, Nueva Ecija' !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 max-w-md mx-auto border border-white/20">
                <svg class="h-8 w-8 text-white mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-white font-medium">Office Hours</p>
                <p class="text-white/90">{{ $contactContent['office_hours'] ?? 'Monday to Friday, 8:00 AM to 5:00 PM' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 opacity-0 invisible z-50" style="background-color: #228B22; color: white;" onmouseover="this.style.backgroundColor='#1e7e1e'" onmouseout="this.style.backgroundColor='#228B22'">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<!-- Enhanced JavaScript for Improved Interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Reading progress bar
        const progressBar = document.getElementById('reading-progress');
        function updateReadingProgress() {
            const scrollTop = window.pageYOffset;
            const docHeight = document.body.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            progressBar.style.width = scrollPercent + '%';
        }
        window.addEventListener('scroll', updateReadingProgress);

        // Hero section animations - removed as elements don't exist

        // Parallax effect removed for better performance

        // Static display for achievements (no animations)
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = counter.getAttribute('data-target');
            const unit = counter.textContent.match(/[a-zA-Z+%]/g) || [];
            counter.textContent = target + unit.join('');
        });

        // Static progress bars
        const progressBars = document.querySelectorAll('.achievement-progress');
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            bar.style.width = width;
        });

        // Back to top button
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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


    });
</script>

<!-- Enhanced Custom CSS -->
<style>
    /* Smooth transitions for all elements */
    * {
        transition: all 0.3s ease;
    }

    /* Enhanced timeline styles */
    .timeline-item:hover .bg-white {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transform: translateY(-4px);
    }

    .timeline-item .bg-green-600:hover,
    .timeline-item .bg-red-800:hover,
    .timeline-item .bg-yellow-600:hover,
    .timeline-item .bg-blue-600:hover {
        transform: scale(1.15);
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #228B22, #8B0000);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #1e7e1e, #7a0000);
    }

    /* Enhanced mobile responsiveness */
    @media (max-width: 768px) {
        .timeline-item {
            margin-bottom: 3rem;
        }

        .timeline-item .bg-white {
            max-width: 100%;
            margin: 0 auto;
        }

        .text-4xl {
            font-size: 2rem;
        }

        .text-5xl {
            font-size: 2.5rem;
        }
    }

    /* Loading animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }

    /* Gradient text effect */
    .gradient-text {
        background: linear-gradient(135deg, #16a34a, #991b1b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Enhanced button hover effects */
    .btn-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* Floating animation for decorative elements */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .float-animation {
        animation: float 3s ease-in-out infinite;
    }

    /* Achievement cards with gaps */
    .achievement-card {
        margin: 16px;
    }

    .achievement-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
