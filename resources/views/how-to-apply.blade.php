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
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('how-to-apply') }}" class="text-green-600 font-semibold relative py-2">
                    How to Apply
                    <span class="absolute bottom-0 left-0 w-full h-0.5" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-green-600 font-medium relative group py-2">
                    About
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
                </a>
                <a href="{{ route('history') }}" class="text-gray-600 hover:text-green-600 font-medium relative group py-2">
                    History
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></span>
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
            <a href="{{ route('how-to-apply') }}" class="block py-3 text-green-600 font-semibold bg-green-50 rounded-lg px-2">How to Apply</a>
            <a href="{{ route('about') }}" class="block py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg px-2 transition-colors duration-200">About</a>
            <a href="{{ route('history') }}" class="block py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg px-2 transition-colors duration-200">History</a>
        </div>
    </div>
    <div class="h-1" style="background-color: rgba(34, 139, 34, 0.2);">
        <div id="reading-progress" class="h-full transition-all duration-300" style="width: 0%; background: linear-gradient(to right, #228B22, #8B0000);"></div>
    </div>
</nav>

<!-- Hero Section -->
<div class="relative p-12 overflow-hidden" style="background: linear-gradient(to bottom right, #ff7474, #ff5f5fa8, #fff, #166534), url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover; background-blend-mode: overlay;">
    <!-- Dark overlay for better text visibility -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="container mx-auto p-12 relative z-10"  >
        <div class="text-center max-w-4xl mx-auto p-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                <span class="inline-block">ERDT Scholarship Program</span>
            </h1>
                                <div class="text-xl md:text-2xl text-white mb-4 font-semibold drop-shadow-md" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                Engineering Research and Development for Technology
            </div>

            <!-- Statistics Cards -->
            <div class="flex flex-wrap justify-center gap-6 mb-8 max-w-6xl mx-auto">
                <!-- Left Column: Partner Universities -->
                <div class="flex-1 min-w-[250px] backdrop-blur-sm rounded-xl p-6 border border-white/30 shadow-lg text-center" style="background: rgba(255, 255, 255, 0.2);">
                    <div class="text-3xl font-bold text-white mb-2 drop-shadow-lg">8</div>
                    <div class="text-white drop-shadow-md">Partner Universities</div>
                </div>

                <!-- Middle Column: Monthly Stipend -->
                <div class="flex-1 min-w-[250px] backdrop-blur-sm rounded-xl p-6 border border-white/30 shadow-lg text-center" style="background: rgba(255, 255, 255, 0.2);">
                    <div class="text-3xl font-bold text-white mb-2 drop-shadow-lg">₱38,000</div>
                    <div class="text-white drop-shadow-md">Monthly Stipend (PhD)</div>
                </div>

                <!-- Right Column: Research Grant -->
                <div class="flex-1 min-w-[250px] backdrop-blur-sm rounded-xl p-6 border border-white/30 shadow-lg text-center" style="background: rgba(255, 255, 255, 0.2);">
                    <div class="text-3xl font-bold text-white mb-2 drop-shadow-lg">₱300,000</div>
                    <div class="text-white drop-shadow-md">Research Grant (PhD)</div>
                </div>
            </div>
        </div>

        <!-- Quick Navigation Buttons -->
        <div class="flex flex-wrap justify-center gap-3 md:gap-4 max-w-5xl mx-auto">
            <a href="#eligibility" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Eligibility
            </a>
            <a href="#programs" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Degree Programs
            </a>
            <a href="#benefits" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Benefits
            </a>
            <a href="#application-process" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                How to Apply
            </a>
            <a href="#timeline" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Timeline
            </a>
            <a href="#downloadable-forms" class="hover-btn backdrop-blur-sm text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl border border-white/20" style="background: rgba(255, 255, 255, 0.9); color: #22c55e;">
                <svg class="h-5 w-5 mr-2 inline text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Forms
            </a>
        </div>
    </div>
</div>

<!-- Scholarship Terms and Conditions Section -->
<div class="py-16 mt-12" id="terms" style="background: linear-gradient(to bottom right, #f0fdf4, #ffffff, #fef2f2);">
    <div class="container mx-auto px-4"  >
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Terms of the Scholarship</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
        </div>

        <div class="max-w-6xl mx-auto space-y-8">
            <!-- Main Terms Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border-l-4 border-green-500">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Key Requirements
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Full-time Study:</span>
                                    <p class="text-gray-600 text-sm">Scholarship is for full-time graduate studies only to ensure on-time completion of MS/PhD degree</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">No Employment:</span>
                                    <p class="text-gray-600 text-sm">Scholar must NOT be employed or practicing profession while on scholarship</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Scholarship Agreement:</span>
                                    <p class="text-gray-600 text-sm">Must execute a Scholarship Agreement with performance evaluated every school term</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-red-700 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Service Obligations
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-red-700 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Leave of Absence:</span>
                                    <p class="text-gray-600 text-sm">Must have leave of absence from other scholarships</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-red-700 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Refund Obligation:</span>
                                    <p class="text-gray-600 text-sm">Must refund scholarship grant with interest for non-completion or failure to comply with service obligation</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-red-700 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Service Term:</span>
                                    <p class="text-gray-600 text-sm">Render service obligation to the Philippines after completing graduate program, one year of service for every year of scholarship</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comprehensive Eligibility Section -->
<div class="py-12 bg-white mt-12" id="eligibility">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6 mt-12">Who Are Eligible to Apply?</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- Primary Eligibility -->
            <div class="rounded-2xl p-8 mb-8 border-l-4 border-green-500" style="background: linear-gradient(to right, #f0fdf4, #dcfce7);">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Primary Eligibility Criteria
                </h3>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-green-700 mb-4">An applicant must:</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Be a Filipino citizen</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Not be more than <strong>50 years old</strong> at the time of application</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Have passed the admission requirements of the graduate program in the host ERDT member-university</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Have BS/MS degree in engineering or related field</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-700 mb-4">Additional Requirements:</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Be in good health condition as certified by a licensed physician</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Be physically and mentally fit to study from a licensed physician</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Have no pending administrative case</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                <span class="text-gray-700">Pass the interview and other screening procedures</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Special Cases -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Who Else May Apply -->
                <div class="rounded-2xl p-6 border-l-4 border-green-500" style="background: linear-gradient(to right, #f0fdf4, #dcfce7);">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-4.297a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        Who Else May Apply?
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 mt-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700">PhD graduate in a priority S&T program (scholar or non-scholar) who intends to pursue a post-doctoral or dissertation work in an acceptable foreign university</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 mt-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700">MS/PhD non-scholar graduate (not S&T related) who intends to pursue a Master's/PhD degree</span>
                        </li>
                    </ul>
                </div>

                <!-- Who May NOT Apply -->
                <div class="rounded-2xl p-6 border-l-4" style="background: linear-gradient(to right, #fef2f2, #fee2e2); border-left-color: #dc2626;">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2" style="color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                        </svg>
                        Who May NO Longer Apply?
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-2 mt-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700">PhD graduate (scholar or non-scholar) who intends to pursue another MS degree</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-2 mt-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700">MS graduate in a priority S&T program (scholar or non-scholar) who intends to pursue a MS degree</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Career Incentive Program -->
            <div class="rounded-2xl p-8 border-l-4" style="background: linear-gradient(to right, #fef2f2, #fee2e2); border-left-color: #dc2626;">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="h-8 w-8 mr-3" style="color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Career Incentive Program (CIP)
                </h3>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-700 mb-4">
                            The Career Incentive Program (CIP) is a short-term program to address the administration's call to
                            strengthen the country's S&T capability and avert unemployment of graduates of DOST-SEI graduate
                            scholarship programs.
                        </p>
                        <p class="text-gray-700 mb-4">
                            <strong>This is open to MS and PhD graduates of the ERDT.</strong> Interested scholar-graduates may apply to the CIP and
                            they will be matched with a DOST agency-institution where their specialization is needed in research projects/activities.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4" style="color: #b91c1c;">Program Benefits:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <span class="w-2 h-2 rounded-full mr-2 mt-1.5 flex-shrink-0" style="background-color: #dc2626;"></span>
                                <span class="text-gray-700 text-sm">Position of Graduate Fellow with the corresponding compensation</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 rounded-full mr-2 mt-1.5 flex-shrink-0" style="background-color: #dc2626;"></span>
                                <span class="text-gray-700 text-sm">Research activities where they can contribute their knowledge and expertise</span>
                            </li>
                        </ul>
                        <div class="mt-4 p-3 rounded-lg" style="background-color: #fee2e2;">
                            <p class="text-sm" style="color: #991b1b;">
                                <strong>Note:</strong> A scholar graduate shall assume the position of Graduate Fellow with the corresponding
                                compensation to be provided by the Science Education Institute.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Requirements Section -->
<div class="py-12 bg-white mt-16" id="application-requirements">
    <div class="container mx-auto px-4"  >
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Application Requirements</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
            <p class="text-xl text-gray-600">Complete these requirements for your ERDT application</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Basic Requirements -->
                <div class="rounded-2xl p-8 shadow-lg border-l-4 border-green-500" style="background: linear-gradient(to bottom right, #f0fdf4, #dcfce7);">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Required Documents
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Accomplished ERDT application form</span>
                                <p class="text-gray-600 text-sm">Narrative essay (research and career plans)</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Recommendation letters from 3 former professors or supervisors</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Certified true copy of the official transcript of records</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Birth certificate (photocopy)</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Valid ID clearance</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Additional Requirements -->
                <div class="rounded-2xl p-8 shadow-lg border-l-4" style="background: linear-gradient(to bottom right, #fef2f2, #fee2e2); border-left-color: #dc2626;">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="h-8 w-8 mr-3" style="color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-4.297a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        Additional Requirements
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Medical certificate</span>
                                <p class="text-gray-600 text-sm">Stating that the applicant is physically and mentally fit to study from a licensed physician</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Certificate of no pending administrative case</span>
                                <p class="text-gray-600 text-sm">If employed, recommendation and permission from head of agency to take a leave and enter into scholarship or proof of resignation or termination of contract</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                            <div>
                                <span class="font-semibold text-gray-800">Certificate of no pending administrative case</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Downloadable Forms Section -->
<div class="p-12 bg-gradient-to-br from-blue-50 to-green-50" id="downloadable-forms" x-data="{ activeTab: 'application' }">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Download Required Forms</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
            <p class="text-xl text-gray-600">Access all the forms you need for your ERDT application</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="flex flex-wrap border-b border-gray-200">
                    <button @click="activeTab = 'application'"
                            :class="activeTab === 'application' ? 'bg-blue-500 text-white border-blue-500' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="flex-1 min-w-[200px] px-6 py-4 text-sm font-medium border-b-2 transition-all duration-200">
                        <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Application Forms
                    </button>

                    <button @click="activeTab = 'grants'"
                            :class="activeTab === 'grants' ? 'bg-green-500 text-white border-green-500' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="flex-1 min-w-[200px] px-6 py-4 text-sm font-medium border-b-2 transition-all duration-200">
                        <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Grant Request Forms
                    </button>

                    <button @click="activeTab = 'monitoring'"
                            :class="activeTab === 'monitoring' ? 'bg-purple-500 text-white border-purple-500' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="flex-1 min-w-[200px] px-6 py-4 text-sm font-medium border-b-2 transition-all duration-200">
                        <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Monitoring & Reports
                    </button>

                    <button @click="activeTab = 'scholarship'"
                            :class="activeTab === 'scholarship' ? 'bg-orange-500 text-white border-orange-500' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="flex-1 min-w-[200px] px-6 py-4 text-sm font-medium border-b-2 transition-all duration-200">
                        <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Scholarship Programs
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-8">
                    <!-- Application Forms Content -->
                    <div x-show="activeTab === 'application'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Application Forms
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ asset('storage/forms/ERDT_Application_Form.pdf') }}"
                               class="flex items-center justify-between p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 group border border-blue-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-blue-600">ERDT Application Form</h4>
                                        <p class="text-sm text-gray-600">Main application form for ERDT scholarship</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <a href="{{ asset('storage/forms/Deed-of-Undertaking-form.pdf') }}"
                               class="flex items-center justify-between p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 group border border-blue-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-blue-600">Deed of Undertaking Form</h4>
                                        <p class="text-sm text-gray-600">Legal commitment document for scholarship</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Grant Request Forms Content -->
                    <div x-show="activeTab === 'grants'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Grant Request Forms
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ asset('storage/forms/ERDT-RG-Form-with-sample-LIB.pdf') }}"
                               class="flex items-center justify-between p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group border border-green-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-green-600">Research Grant Form</h4>
                                        <p class="text-sm text-gray-600">For research funding requests</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <a href="{{ asset('storage/forms/ERDT-RDG-form.pdf') }}"
                               class="flex items-center justify-between p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group border border-green-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-green-600">Research Dissemination Grant Form</h4>
                                        <p class="text-sm text-gray-600">For research dissemination funding</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <a href="{{ asset('storage/forms/ERDT-TDG-form.pdf') }}"
                               class="flex items-center justify-between p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group border border-green-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-green-600">Thesis/Dissertation Grant Form</h4>
                                        <p class="text-sm text-gray-600">For thesis and dissertation support</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Monitoring & Reports Content -->
                    <div x-show="activeTab === 'monitoring'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Monitoring & Reports
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ asset('storage/forms/ERDT-Monitoring-FORM.docx') }}"
                               class="flex items-center justify-between p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group border border-purple-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-blue-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-purple-600">ERDT Monitoring Form</h4>
                                        <p class="text-sm text-gray-600">Progress monitoring document</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <a href="{{ asset('storage/forms/ERDT-LIQUIDATION-REPORT-FOR-OUTRIGHT-THESIS-GRANT.docx') }}"
                               class="flex items-center justify-between p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group border border-purple-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-blue-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-purple-600">Liquidation Report Form</h4>
                                        <p class="text-sm text-gray-600">Financial reporting for thesis grants</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Scholarship Programs Content -->
                    <div x-show="activeTab === 'scholarship'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="h-8 w-8 text-orange-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Scholarship Programs
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ asset('storage/forms/ERDT-SP-form-2024.pdf') }}"
                               class="flex items-center justify-between p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors duration-200 group border border-orange-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-orange-600">ERDT Scholarship Program 2024</h4>
                                        <p class="text-sm text-gray-600">Current year scholarship program details</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <a href="{{ asset('storage/forms/Form_SFA-for-scholar.xlsx') }}"
                               class="flex items-center justify-between p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors duration-200 group border border-orange-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2M13 7a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2" />
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-orange-600">Scholar Financial Assistance Form</h4>
                                        <p class="text-sm text-gray-600">Financial assistance request form</p>
                                    </div>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-400">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-yellow-500 mr-3 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Important Notes</h3>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• All forms must be completely filled out before submission</li>
                            <li>• Please ensure you download the latest version of each form</li>
                            <li>• Some forms may require additional supporting documents</li>
                            <li>• Contact the ERDT office if you have questions about any form</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Process Section -->
    <div class="py-12 bg-gray-50 mt-16" id="application-process" x-data="{ currentStep: 1 }">
        <div class="container mx-auto p-12"  >
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Application Process</h2>

            <!-- Progress Tracker -->
            <div class="relative mb-12 max-w-3xl mx-auto">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-green-600 transition-all duration-500 ease-in-out"
                     :style="`width: ${((currentStep - 1) / 4) * 100}%`"></div>

                <div class="relative flex justify-between">
                    <!-- Step 1 Indicator -->
                    <div class="step-indicator" data-step="1" @click="currentStep = 1">
                        <div class="relative">
                            <div class="rounded-full h-10 w-10 flex items-center justify-center z-10 relative cursor-pointer transition-colors duration-200"
                                :class="currentStep >= 1 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-green-500'">
                                <span class="font-bold" :class="currentStep >= 1 ? 'text-white' : 'text-gray-700 group-hover:text-white'">1</span>
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-sm font-medium text-gray-700">Check Eligibility</span>
                        </div>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="step-indicator" data-step="2" @click="currentStep = 2">
                        <div class="relative">
                            <div class="rounded-full h-10 w-10 flex items-center justify-center z-10 relative cursor-pointer transition-colors duration-200"
                                :class="currentStep >= 2 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-green-500'">
                                <span class="font-bold" :class="currentStep >= 2 ? 'text-white' : 'text-gray-700 group-hover:text-white'">2</span>
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-sm font-medium text-gray-700">Submit Application</span>
                        </div>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="step-indicator" data-step="3" @click="currentStep = 3">
                        <div class="relative">
                            <div class="rounded-full h-10 w-10 flex items-center justify-center z-10 relative cursor-pointer transition-colors duration-200"
                                :class="currentStep >= 3 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-green-500'">
                                <span class="font-bold" :class="currentStep >= 3 ? 'text-white' : 'text-gray-700 group-hover:text-white'">3</span>
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-sm font-medium text-gray-700">Screening</span>
                        </div>
                    </div>

                    <!-- Step 4 Indicator -->
                    <div class="step-indicator" data-step="4" @click="currentStep = 4">
                        <div class="relative">
                            <div class="rounded-full h-10 w-10 flex items-center justify-center z-10 relative cursor-pointer transition-colors duration-200"
                                :class="currentStep >= 4 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-green-500'">
                                <span class="font-bold" :class="currentStep >= 4 ? 'text-white' : 'text-gray-700 group-hover:text-white'">4</span>
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-sm font-medium text-gray-700">Selection</span>
                        </div>
                    </div>

                    <!-- Step 5 Indicator -->
                    <div class="step-indicator" data-step="5" @click="currentStep = 5">
                        <div class="relative">
                            <div class="rounded-full h-10 w-10 flex items-center justify-center z-10 relative cursor-pointer transition-colors duration-200"
                                :class="currentStep >= 5 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-green-500'">
                                <span class="font-bold" :class="currentStep >= 5 ? 'text-white' : 'text-gray-700 group-hover:text-white'">5</span>
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-sm font-medium text-gray-700">Contract Signing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Content Container -->
            <div class="step-content-container">
                <!-- Step 1 Content -->
                <div class="step-content" id="step-content-1" x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
                    <div class="relative mb-12">
                        <div class="flex flex-col md:flex-row items-start">
                            <div class="flex-shrink-0 bg-green-600 rounded-full h-12 w-12 flex items-center justify-center text-white font-bold text-lg mb-4 md:mb-0 md:mr-6">
                                1
                            </div>
                            <div class="flex-grow ml-0 md:ml-6 mt-4 md:mt-0 pl-8 pt-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Prepare Required Documents</h3>
                                <p class="text-gray-700 mb-4">Gather all necessary documents for your application:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Completed application form (download below)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Official transcript of records</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Recommendation letters from two (2) past professors/references</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Research proposal (for PhD applicants)</span>
                            </li>
                            <li class="flex items-start">
                                                        <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Curriculum Vitae</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Copy of diploma</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Valid ID</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Medical Certificate from licensed physician</span>
                    </li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ asset('storage/forms/ERDT_Application_Form.pdf') }}" class="inline-flex items-center text-green-600 hover:text-green-800">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download Application Form
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between mt-8">
                        <div></div>
                        <button @click="currentStep = 2" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200 flex items-center">
                            Next Step
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 2 Content -->
                <div class="step-content" id="step-content-2" x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 bg-green-600 rounded-full h-12 w-12 flex items-center justify-center text-white font-bold text-lg mb-4 md:mb-0 md:mr-6">
                            2
                        </div>
                        <div class="flex-grow ml-0 md:ml-6 mt-4 md:mt-0">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Submit Application</h3>
                            <p class="text-gray-700 mb-4">Submit your complete application package through one of these methods:</p>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-white p-6 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 mb-2">Online Submission</h4>
                                    <p class="text-gray-700 mb-3">Email your complete application to <a href="mailto:erdt@clsu.edu.ph" class="text-green-600 hover:underline">erdt@clsu.edu.ph</a> with the subject line "ERDT Scholarship Application - [Your Name]".</p>
                                </div>

                                <div class="bg-white p-6 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 mb-2">In-Person Submission</h4>
                                    <p class="text-gray-700 mb-3">Submit to the CLSU-ERDT Office, Engineering Building, Central Luzon State University, Science City of Muñoz, Nueva Ecija.</p>
                                </div>
                            </div>

                            <p class="text-gray-600 italic mt-4">Ensure all documents are complete and properly labeled; incomplete applications will not be processed.</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between mt-8">
                        <button @click="currentStep = 1" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors duration-200 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous Step
                        </button>
                        <button @click="currentStep = 3" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200 flex items-center">
                            Next Step
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 3 Content -->
                <div class="step-content" id="step-content-3" x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 bg-green-600 rounded-full h-12 w-12 flex items-center justify-center text-white font-bold text-lg mb-4 md:mb-0 md:mr-6">
                            3
                        </div>
                        <div class="flex-grow ml-0 md:ml-6 mt-4 md:mt-0">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Screening and Evaluation</h3>
                            <p class="text-gray-700 mb-4">Your application will undergo a thorough evaluation process:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Initial screening of documents</span>
                            </li>                        <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Written examination</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Panel interview</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Research proposal presentation (for PhD applicants)</span>
                            </li>
                            </ul>
                            <p class="text-gray-700 mt-4">You will be notified via email about the schedule for the examination and interview.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 Content -->
                <div class="step-content" id="step-content-4" x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 bg-green-600 rounded-full h-12 w-12 flex items-center justify-center text-white font-bold text-lg mb-4 md:mb-0 md:mr-6">
                            4
                        </div>
                        <div class="flex-grow ml-0 md:ml-6 mt-4 md:mt-0">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Selection and Notification</h3>
                            <p class="text-gray-700 mb-4">After the evaluation process:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>The selection committee will deliberate on all applications</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Successful applicants will be notified via email and official letter</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>The list of accepted scholars will be posted on the CLSU-ERDT website</span>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 5 Content -->
                <div class="step-content" id="step-content-5" x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 bg-green-600 rounded-full h-12 w-12 flex items-center justify-center text-white font-bold text-lg mb-4 md:mb-0 md:mr-6">
                            5
                        </div>
                        <div class="flex-grow ml-0 md:ml-6 mt-4 md:mt-0">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Scholarship Contract Signing</h3>
                            <p class="text-gray-700 mb-4">If selected:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>You will be invited to a scholarship orientation</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sign the scholarship contract and terms of agreement</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Begin your academic journey as an ERDT scholar</span>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Comprehensive Benefits Section -->
<div class="p-12 mt-16" id="benefits" style="background: linear-gradient(to bottom right, #f9fafb, #f0fdf4);">
    <div class="container mx-auto px-4"  >
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Scholarship Privileges & Benefits</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
            <p class="text-xl text-gray-600">Comprehensive support for your academic and research journey</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- Comparison Table -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12">
                <div class="text-white p-6" style="background: linear-gradient(to right, #16a34a, #991b1b);">
                    <h3 class="text-2xl font-bold text-center text-white">Benefits Comparison: MS vs PhD</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Entitlements</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">MS</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">PhD</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Tuition and other school fees</td>
                                <td class="px-6 py-4 text-center text-gray-700">Actual as billed</td>
                                <td class="px-6 py-4 text-center text-gray-700">Actual as billed</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Stipend</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱30,000.00 / month</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱38,000.00 / month</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Learning Materials and/or Connectivity Allowance</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱20,000.00/Academic Year</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱20,000.00/Academic Year</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Transportation Allowance</td>
                                <td class="px-6 py-4 text-center text-gray-700">One (1) round trip economy class roundtrip per academic year</td>
                                <td class="px-6 py-4 text-center text-gray-700">One (1) round trip economy class roundtrip per academic year</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Group Accident and Health Insurance</td>
                                <td class="px-6 py-4 text-center text-gray-700">Premium</td>
                                <td class="px-6 py-4 text-center text-gray-700">Premium</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Thesis/Dissertation Outright Grant</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱60,000.00</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱100,000.00</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Research Support Grant</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱114,000.00</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱253,000.00</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Research Dissemination Grant</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱76,000.00</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱150,000.00</td>
                            </tr>
                            <tr class="hover:bg-green-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-gray-800 font-medium">Mentor's Fee</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱36,000.00</td>
                                <td class="px-6 py-4 text-center text-gray-700">₱72,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 p-4">
                    <p class="text-sm text-gray-600 italic text-center">
                        <strong>Important Note:</strong> All MS program costs are given in a 2-year period at the first year;
                        while PhD program costs at 3 years period at the first year. Must be approved by the scholars and subject to evaluation and approval.
                        Grant approval by DOST is subject to submission and approval. All allowances marked with * are subject to evaluation and
                        approval by the scholarship committee.
                    </p>
                </div>
            </div>

            <!-- Research Enrichment Program -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-l-4 border-red-700">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="h-8 w-8 text-red-700 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Research Enrichment ('Sandwich') Program
                </h3>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-700 mb-4">
                            The Sandwich Program is open to ongoing ERDT MS and PhD scholars, who have (1) completed their coursework,
                            (2) have approved research proposals, and (3) intend to conduct part of their research, or dissertation work in an
                            acceptable foreign university.
                        </p>
                        <p class="text-gray-700 mb-4">
                            The research work abroad must be part of the degree program area of study, and shall span a minimum of 3 months to a maximum of 1 year.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4" style="color: #b91c1c;">Financial Assistance:</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <span class="w-2 h-2 rounded-full mr-2 mt-1.5 flex-shrink-0" style="background-color: #b91c1c;"></span>
                                <span class="text-gray-700 text-sm">Maximum of One Million Two Hundred Thousand Pesos (₱1,200,000) or less, depending on the place and duration of study</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 rounded-full mr-2 mt-1.5 flex-shrink-0" style="background-color: #b91c1c;"></span>
                                <span class="text-gray-700 text-sm">Privileges include roundtrip economy airfare, monthly/daily allowance, book allowance, accommodation, medical insurance, tuition fee and other school fees, clothing allowance, and subject to evaluation and approval</span>
                            </li>
                        </ul>
                        <div class="mt-4 p-3 rounded-lg" style="background-color: #fee2e2;">
                            <p class="text-sm" style="color: #991b1b;">
                                <strong>Note:</strong> Allowances will depend on the place and duration of study
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Timeline Section -->
<div class="py-12 bg-gray-50 mt-16" id="timeline">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Application Timeline</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Activity</th>
                        <th class="py-3 px-4 text-left">First Semester</th>
                        <th class="py-3 px-4 text-left">Second Semester</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($timelines as $index => $timeline)
                        <tr class="{{ $index % 2 == 0 ? '' : 'bg-gray-50' }}">
                            <td class="py-3 px-4 text-gray-700">{{ $timeline->activity }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $timeline->first_semester }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $timeline->second_semester }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 px-4 text-center text-gray-500">
                                No timeline information available. Please check back later.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="text-gray-600 italic text-sm mt-4">Note: Dates may vary each year. Please check the official announcements for the most current schedule.</p>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-12 bg-white mt-16" id="faq">
    <div class="container mx-auto px-4"  >
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Frequently Asked Questions</h2>

        <div class="max-w-3xl mx-auto space-y-4">
            <!-- FAQ Item 1 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 focus:outline-none">
                    <span class="font-medium text-gray-800">What financial support does the ERDT scholarship provide?</span>
                    <svg class="faq-icon h-5 w-5 text-green-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden p-4 pt-0 bg-white">
                    <p class="text-gray-700">
                        The ERDT scholarship provides full tuition and other school fees, monthly stipend (₱25,000 for MS, ₱33,000 for PhD), book allowance, research/dissertation grant, and group accident insurance. PhD scholars may also receive conference travel grants for presenting research papers.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 focus:outline-none">
                    <span class="font-medium text-gray-800">Can I work while on the ERDT scholarship?</span>
                    <svg class="faq-icon h-5 w-5 text-green-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden p-4 pt-0 bg-white">
                    <p class="text-gray-700">
                        Scholars must have a full-time commitment to the scholarship and must not be engaged in any form of employment during the scholarship period. This is to ensure scholars can focus entirely on their academic and research work.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 focus:outline-none">
                    <span class="font-medium text-gray-800">What are the service obligations after completing the scholarship?</span>
                    <svg class="faq-icon h-5 w-5 text-green-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden p-4 pt-0 bg-white">
                    <p class="text-gray-700">
                        All ERDT scholars must serve in the Philippines for a period equivalent to the length of time that the scholar enjoyed the scholarship on a full-time basis. This service can be rendered through employment in academia, industry, or government agencies related to the scholar's field of study.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 focus:outline-none">
                    <span class="font-medium text-gray-800">Can international students apply for the ERDT scholarship?</span>
                    <svg class="faq-icon h-5 w-5 text-green-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden p-4 pt-0 bg-white">
                    <p class="text-gray-700">
                        While the ERDT scholarship primarily targets Filipino citizens, international students with relevant qualifications may be considered on a case-by-case basis, particularly if their research interests align with the priority areas of the program and if they commit to contributing to Philippine development.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 focus:outline-none">
                    <span class="font-medium text-gray-800">What happens if I don't complete my degree program?</span>
                    <svg class="faq-icon h-5 w-5 text-green-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden p-4 pt-0 bg-white">
                    <p class="text-gray-700">
                        Scholars who fail to complete their degree program without valid reasons may be required to refund the total amount received plus interest. Each case is evaluated individually, and scholars facing challenges are encouraged to communicate with their program coordinators for possible solutions before considering withdrawal.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Degree Programs Section -->
<div class="py-12 bg-white mt-16" id="programs">
    <div class="container mx-auto px-4"  >
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Degree Programs</h2>
            <div class="w-24 h-1 mx-auto mb-8 rounded" style="background: linear-gradient(to right, #22c55e, #7f1d1d);"></div>
            <p class="text-xl text-gray-600">Choose from various engineering and allied fields</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto mb-12">
            <!-- Master's Programs -->
            <div class="rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(to bottom right, #f0fdf4, #dcfce7);">
                <div class="flex items-center mb-6">
                    <div class="bg-green-600 p-3 rounded-full mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Master's Degree</h3>
                        <p class="text-gray-600">Master of Science (MS) / Master of Engineering (MEng)</p>
                    </div>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Typically 2 years duration</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Research-oriented or coursework-based</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Thesis or dissertation required</span>
                    </li>
                </ul>
            </div>

            <!-- Doctoral Programs -->
            <div class="rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" style="background: linear-gradient(to bottom right, #fef2f2, #fee2e2);">
                <div class="flex items-center mb-6">
                    <div class=" p-3 rounded-full mr-4" style="background-color:#800000">
                        <svg class="h-8 w-8 text-white"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Doctoral Degree</h3>
                        <p class="text-gray-600">Doctor of Philosophy (PhD)</p>
                    </div>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 mr-3 mt-1 flex-shrink-0" style="color: #b91c1c;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Typically 3-4 years duration</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 mr-3 mt-1 flex-shrink-0" style="color: #b91c1c;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Advanced research focus</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 mr-3 mt-1 flex-shrink-0" style="color: #b91c1c;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Original research contribution required</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
@include('components.footer')

<!-- JavaScript for Mobile Menu, FAQ Toggles, Application Steps Navigation, and Scholarship Privileges -->
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

        // Step navigation is handled by Alpine.js - no additional JavaScript needed

        // Smooth scrolling for navigation links
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
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe all major sections
        document.querySelectorAll('section, .container > div').forEach(el => {
            observer.observe(el);
        });

        // FAQ Toggle functionality
        const faqToggles = document.querySelectorAll('.faq-toggle');

        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');

                // Toggle content visibility
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    content.classList.add('hidden');
                    content.style.maxHeight = '0px';
                    icon.style.transform = 'rotate(0deg)';
                }

                // Close other open FAQs (accordion behavior)
                faqToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherContent = otherToggle.nextElementSibling;
                        const otherIcon = otherToggle.querySelector('.faq-icon');

                        if (!otherContent.classList.contains('hidden')) {
                            otherContent.classList.add('hidden');
                            otherContent.style.maxHeight = '0px';
                            otherIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            });
        });
    });

    // Enhanced hover effects for navigation buttons
    function addHoverEffect(button) {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
    }

    // Apply hover effects to all buttons with hover-btn class
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.hover-btn').forEach(addHoverEffect);
    });
</script>

<style>
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

    .hover-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-btn:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 10px 25px rgba(34, 139, 34, 0.2);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #22c55e;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #16a34a;
    }

    /* FAQ styles */
    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .faq-content.hidden {
        max-height: 0;
    }

    .faq-icon {
        transition: transform 0.3s ease-out;
    }

    /* Table responsiveness */
    @media (max-width: 768px) {
        .overflow-x-auto table {
            font-size: 0.875rem;
        }

        .overflow-x-auto td,
        .overflow-x-auto th {
            padding: 0.5rem 0.75rem;
        }
    }
</style>

@endsection
