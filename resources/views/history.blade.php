@extends('layouts.app')
@section('content')
<x-navigation />
<!-- Hero Section -->
<div class="relative w-full" style="aspect-ratio: 16/9; background-image: url('{{ asset('images/historyBG.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <!-- Dark overlay for better text contrast -->
    <div class="absolute inset-0 bg-black/40"></div>
    
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
             <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4">
                    <span class="block">{{ $heroContent['title'] ?? 'Our Rich' }}</span>
                    <span class="block text-yellow-300" >{{ $heroContent['subtitle'] ?? 'History & Legacy' }}</span>
                </h1>
                <h2 class="text-xl md:text-2xl lg:text-3xl font-medium text-white mb-6" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                    {{ $heroContent['heading'] ?? 'Decades of Engineering Excellence & Innovation' }}
                </h2>
                <p class="text-lg md:text-xl text-white mb-8 max-w-3xl leading-relaxed" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                    {{ $heroContent['description'] ?? 'Discover the remarkable journey of CLSU-ERDT, from its humble beginnings to becoming a premier center of excellence in engineering research and development. Explore our milestones, achievements, and the impact we\'ve made in shaping the future of engineering in the Philippines.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <div class="text-center bg-white rounded-lg p-8 shadow-md">
                <div class="text-5xl font-bold text-green-700 mb-4">1998</div>
                <div class="text-gray-700 text-lg font-medium">Program Established</div>
            </div>
            <div class="text-center bg-white rounded-lg p-8 shadow-md">
                <div class="text-5xl font-bold text-green-700 mb-4">25+</div>
                <div class="text-gray-700 text-lg font-medium">Years of Excellence</div>
            </div>
            <div class="text-center bg-white rounded-lg p-8 shadow-md">
                <div class="text-5xl font-bold text-green-700 mb-4">500+</div>
                <div class="text-gray-700 text-lg font-medium">Graduates Produced</div>
            </div>
            <div class="text-center bg-white rounded-lg p-8 shadow-md">
                <div class="text-5xl font-bold text-green-700 mb-4">100+</div>
                <div class="text-gray-700 text-lg font-medium">Research Projects</div>
            </div>
        </div>
    </div>
</div>

<!-- Introduction Section -->
<div class="py-24 bg-gray-50" id="introduction">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Our Story
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                {{ $introContent['title'] ?? 'Building Engineering Excellence' }}
            </h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-12">
                <div class="text-center max-w-4xl mx-auto">
                    <p class="text-xl text-gray-700 leading-relaxed mb-8">
                        The Engineering Research & Development for Technology (ERDT) program at Central Luzon State University represents more than two decades of commitment to excellence in engineering education and research. Since its establishment, ERDT has been at the forefront of developing world-class engineers and researchers who contribute significantly to national development and technological advancement.
                    </p>
                    <p class="text-xl text-gray-700 leading-relaxed">
                        Launched in April 2007, the ERDT program was established to build a critical mass of research scientists and engineers. CLSU joined the consortium to contribute expertise in agricultural engineering, with its College of Engineering playing a key role since 2009. Under the leadership of deans like Dr. Arnold R. Elepano and Dr. Rossana Marie C. Amongo, CLSU has advanced research in ABE, aligning with the Philippines' National Science and Technology Plan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ERDT Foundation Section -->
<div class="py-24 bg-white" id="foundation">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    The Foundation of ERDT
                </h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    A historic collaboration that transformed engineering education in the Philippines
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center mb-16">
                <div class="bg-green-50 rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v-6m0 0V7m0 6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">April 2007</h3>
                            <p class="text-green-600 font-medium">Historic Beginning</p>
                        </div>
                    </div>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Seven Deans of top caliber Engineering Schools of the Philippines came together to establish the Engineering Research and Development for Technology (ERDT) program, led by former Dean Dr. Rowena Cristina L. Guevara of U.P. Diliman College of Engineering.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Presidential Approval</h4>
                            <p class="text-gray-600">Endorsed by former DOST Secretary Estrella F. Alabastro and approved by former President Gloria Macapagal-Arroyo</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">₱3.5 Billion Initial Funding</h4>
                            <p class="text-gray-600">Three-year initial funding commitment, now continued with annual budget of around ₱300 Million</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Eight-University Consortium</h4>
                            <p class="text-gray-600">A consortium of eight universities offering mature engineering graduate programs with U.P. Diliman as lead university</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Program Components Section -->
<div class="py-24 bg-gray-50" id="components">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    ERDT Program Components
                </h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Comprehensive approach to building research capacity and engineering excellence
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- Human Resource Development -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Human Resource Development</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Graduate Program Scholarships
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Visiting Professor Program
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Visiting Researcher Program
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            PhD Sandwich Program
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Faculty Research Dissemination Grant
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Post-Doctoral Studies
                        </li>
                    </ul>
                </div>

                <!-- Research & Development -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Research & Development</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Energy Track Research
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Environment & Infrastructure Track
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            ICT Track
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Semiconductor & Electronics Track
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Manufacturing & Machinery Track
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Makabayan Project Integration
                        </li>
                    </ul>
                </div>

                <!-- Infrastructure Development -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Infrastructure Development</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Research Facility Enhancement
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Laboratory Modernization
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Equipment Acquisition
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Workshop Organization
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Conference Hosting
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Summit Coordination
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLSU's Role Section -->
<div class="py-24 bg-white" id="clsu-role">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    CLSU's Strategic Role in ERDT
                </h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Contributing expertise in agricultural engineering and advancing research in Agricultural and Biosystems Engineering
                </p>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-12 mb-12">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-6">Agricultural Engineering Excellence</h3>
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            CLSU joined the ERDT consortium to contribute its specialized expertise in agricultural engineering, with the College of Engineering playing a pivotal role since 2009. Our focus on Agricultural and Biosystems Engineering (ABE) aligns perfectly with the Philippines' National Science and Technology Plan.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Specialized Agricultural Engineering Focus</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">National S&T Plan Alignment</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Research Excellence in ABE</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-8 shadow-lg">
                        <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">Leadership Excellence</h4>
                        <div class="space-y-6">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h5 class="text-xl font-bold text-gray-800">Dr. Arnold R. Elepano</h5>
                                <p class="text-gray-600">Former Dean, College of Engineering</p>
                            </div>
                            <div class="text-center">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h5 class="text-xl font-bold text-gray-800">Dr. Rossana Marie C. Amongo</h5>
                                <p class="text-gray-600">Current Dean, College of Engineering</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Innovation & Entrepreneurship Section -->
<div class="py-24 bg-gray-50" id="innovation">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    Innovation & Technology Entrepreneurship
                </h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Bridging research and real-world applications through entrepreneurship
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Summer 2008 Initiative</h3>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        ERDT began emphasizing technology entrepreneurship, recognizing that for research to be truly relevant, it must be translated into usable products or processes that benefit society.
                    </p>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-orange-800 font-medium">
                            "Research without application is merely academic exercise. True impact comes from translating discoveries into solutions."
                        </p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Technoentrepreneurship Education</h4>
                            <p class="text-gray-600">Since 2008, ERDT scholars receive specialized training in technology entrepreneurship to prepare them for commercializing their research.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Research Translation</h4>
                            <p class="text-gray-600">Focus on converting academic research into practical applications that address real-world challenges and create economic value.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Startup Ecosystem</h4>
                            <p class="text-gray-600">ERDT graduates now contribute to various sectors including private industries, academia, government agencies, and innovative start-up companies.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Achievements & Impact Section -->
<div class="py-24 bg-white" id="achievements">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    Achievements & Impact
                </h2>
                <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Sixteen years of excellence in engineering research and human resource development
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 mb-16">
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Research Excellence</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Significant increase in engineering research activities</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Improved research quality and standards</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Numerous funded research projects</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Papers accepted in refereed journals and conferences</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Human Resource Development</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Critical mass of researchers, scientists, and engineers</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Faculty members sent abroad for doctoral studies</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Upgraded quality of engineering graduate programs</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Advanced courses and higher degree programs in SUCs</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl p-8 md:p-12 text-white text-center">
                <h3 class="text-3xl font-bold mb-6">Looking Forward: ASEAN Economic Community</h3>
                <p class="text-xl leading-relaxed mb-8">
                    With the ASEAN transformation into a single economic market and production base, there is urgency in harnessing the talent pool of Filipino engineers and focusing them on high value-added activities. ERDT, along with ASTHRDP, forms the foundation for our country's substantial participation in the ASEAN Economic Community.
                </p>
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white/10 rounded-lg p-6">
                        <h4 class="text-xl font-bold mb-3">Current Progress</h4>
                        <p class="text-white/90">One-third of the way toward achieving the goal of a critical mass of researchers, scientists, and engineers for a globally competitive Philippines.</p>
                    </div>
                    <div class="bg-white/10 rounded-lg p-6">
                        <h4 class="text-xl font-bold mb-3">Future Vision</h4>
                        <p class="text-white/90">Thirteen more years to realize the complete goal of building a critical mass for national competitiveness and sustainable development.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vision for the Future Section -->
<div class="py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Vision for the Future
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                As we look ahead, CLSU-ERDT continues to evolve and adapt to meet the challenges of tomorrow's engineering landscape.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
            <!-- Innovation Leadership -->
            <div class="bg-gray-50 rounded-lg p-10 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-8 mx-auto">
                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Innovation Leadership</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Pioneering cutting-edge research and development initiatives that address global challenges and drive technological advancement.
                </p>
            </div>

            <!-- Global Impact -->
            <div class="bg-gray-50 rounded-lg p-10 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-8 mx-auto">
                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Global Impact</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Expanding our reach and influence to create solutions that benefit communities worldwide while maintaining our commitment to excellence.
                </p>
            </div>

            <!-- Next Generation -->
            <div class="bg-gray-50 rounded-lg p-10 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-8 mx-auto">
                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Next Generation</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Nurturing and developing the next generation of engineers and researchers who will lead tomorrow's technological breakthroughs.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-24" style="background-image: url('{{ asset('images/bg1.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="text-left max-w-5xl mx-auto">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4">
                Ready to Be Part of Our Legacy?
            </h2>
            <p class="text-xl text-white/90 mb-12 leading-relaxed">
                Join the next generation of engineering leaders and researchers. Discover how CLSU-ERDT can help you achieve your academic and professional goals while contributing to meaningful research and innovation.
            </p>
            <div class="flex flex-col sm:flex-row gap-8 justify-center">
                <a href="{{ route('how-to-apply')}}" class="inline-flex items-center bg-white text-green-700 hover:bg-gray-100 font-bold py-5 px-10 rounded-lg text-lg transition-colors duration-300 shadow-lg">
                    <svg class="h-8 w-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Apply Now
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center border-2 border-white text-white font-bold py-5 px-10 rounded-lg text-lg transition-colors duration-300 hover:bg-white hover:text-green-700">
                    <svg class="h-8 w-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learn More
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 opacity-0 invisible z-50 bg-green-600 text-white hover:bg-green-700">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    const mobileMenuButton=document.getElementById('mobile-menu-button'),mobileMenu=document.getElementById('mobile-menu');mobileMenuButton.addEventListener('click',()=>{mobileMenu.classList.toggle('hidden');});
    window.addEventListener('scroll',()=>{const winScroll=document.body.scrollTop||document.documentElement.scrollTop,height=document.documentElement.scrollHeight-document.documentElement.clientHeight,scrolled=(winScroll/height)*100,progressBar=document.getElementById('reading-progress');if(progressBar){progressBar.style.width=scrolled+'%';}});
    document.querySelectorAll('a[href^="#"]').forEach(anchor=>{anchor.addEventListener('click',function(e){e.preventDefault();const target=document.querySelector(this.getAttribute('href'));if(target){target.scrollIntoView({behavior:'smooth',block:'start'});}});});
    const observerOptions={threshold:0.1,rootMargin:'0px 0px -50px 0px'},observer=new IntersectionObserver((entries)=>{entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('fade-in-up');}});},observerOptions);document.querySelectorAll('.transform').forEach(el=>{observer.observe(el);});
    const backToTopButton=document.getElementById('back-to-top');window.addEventListener('scroll',()=>{if(window.pageYOffset>300){backToTopButton.classList.remove('opacity-0','invisible');backToTopButton.classList.add('opacity-100','visible');}else{backToTopButton.classList.add('opacity-0','invisible');backToTopButton.classList.remove('opacity-100','visible');}});if(backToTopButton){backToTopButton.addEventListener('click',()=>{window.scrollTo({top:0,behavior:'smooth'});});}
</script>
<style>
    @keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .fade-in-up{animation:fadeInUp 0.6s ease forwards;}
</style>


<x-footer />
@endsection

