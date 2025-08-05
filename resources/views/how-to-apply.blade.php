@extends('layouts.app')

@section('content')
<!-- Include the navigation component -->
<x-navigation />

<!-- Hero Section -->
<div class="relative w-full" style="aspect-ratio: 16/9; background-image: url('{{ asset('images/how-to-apply-heroBG.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"" >
     <div class="absolute inset-0 flex items-center justify-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
             <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4">
                    <span class="block">Join the</span>
                    <span class="block">ERDT Program</span>
                </h1>
                <h2 class="text-xl md:text-2xl lg:text-3xl font-medium text-green-100 mb-6" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                    Your Gateway to Engineering Excellence
                </h2>
                <p class="text-lg md:text-xl text-green-100 mb-8 max-w-4xl leading-relaxed" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                    Discover how to become part of the Engineering Research & Development for Technology program at CLSU. 
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="py-12 sm:py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-10 max-w-6xl mx-auto">
            <div class="text-center bg-gray-50 rounded-lg p-8">
                <div class="text-5xl font-bold text-green-700 mb-4">8</div>
                <div class="text-gray-700 text-lg font-medium">Partner Universities</div>
            </div>
            <div class="text-center bg-gray-50 rounded-lg p-8">
                <div class="text-5xl font-bold text-green-700 mb-4">₱38K</div>
                <div class="text-gray-700 text-lg font-medium">Monthly Stipend</div>
            </div>
            <div class="text-center bg-gray-50 rounded-lg p-8">
                <div class="text-5xl font-bold text-green-700 mb-4">₱300K</div>
                <div class="text-gray-700 text-lg font-medium">Research Grant</div>
            </div>
            <div class="text-center bg-gray-50 rounded-lg p-8">
                <div class="text-5xl font-bold text-green-700 mb-4">100%</div>
                <div class="text-gray-700 text-lg font-medium">Tuition Coverage</div>
            </div>
        </div>
    </div>
</div>

<!-- Eligibility Section -->
<div class="py-12 sm:py-16 lg:py-20 bg-gray-50" id="eligibility">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="max-w-5xl mx-auto text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-full text-base sm:text-lg font-medium mb-4 sm:mb-6 bg-green-100 text-green-700">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 mr-2 sm:mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Eligibility Requirements
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Who Can Apply?</h2>
            <div class="w-24 h-2 mx-auto mb-8 rounded bg-green-600"></div>
            <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed">Check if you meet our scholarship requirements before starting your application.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 max-w-6xl mx-auto">
            <!-- General Requirements -->
            <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex items-start mb-4 sm:mb-6">
                    <div class="flex-shrink-0 bg-green-100 text-green-700 p-2 sm:p-3 lg:p-4 rounded-lg mr-3 sm:mr-4 lg:mr-6">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">General Requirements</h3>
                        <ul class="text-lg text-gray-700 space-y-3">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Filipino citizen
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Engineering or related field graduate
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Must not be employed during scholarship
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Full-time study commitment
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Academic Requirements -->
            <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex items-start mb-4 sm:mb-6">
                    <div class="flex-shrink-0 bg-green-100 text-green-700 p-2 sm:p-3 lg:p-4 rounded-lg mr-3 sm:mr-4 lg:mr-6">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Academic Requirements</h3>
                        <ul class="text-lg text-gray-700 space-y-3">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Minimum GPA of 2.5 (undergraduate)
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Pass qualifying examination
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Research proposal submission
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-600 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Interview evaluation
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Process Section -->
<div class="py-12 sm:py-16 lg:py-24 bg-gray-50" id="application-process">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="max-w-6xl mx-auto text-center mb-12 sm:mb-16 lg:mb-20">
            <div class="inline-flex items-center px-8 py-4 rounded-full text-xl font-medium mb-8 bg-green-100 text-green-700">
                <svg class="w-8 h-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Step-by-Step Guide
            </div>
            <h2 class="text-5xl md:text-6xl font-bold text-gray-800 mb-8">Application Process</h2>
            <p class="text-2xl text-gray-700 max-w-5xl mx-auto leading-relaxed">Follow our streamlined four-step process to complete your ERDT scholarship application successfully.</p>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Timeline Container -->
            <div class="relative">
                <!-- Vertical Timeline Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-green-200 hidden lg:block"></div>
                
                <!-- Step 1 -->
                <div class="relative mb-8 sm:mb-12 lg:mb-20">
                    <div class="flex flex-col lg:flex-row items-center">
                        <!-- Left Content (Desktop) / Full Width (Mobile) -->
                        <div class="w-full lg:w-5/12 lg:pr-12">
                            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 lg:p-10 shadow-lg border border-gray-100">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 sm:mb-6">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg sm:text-xl lg:text-2xl mb-3 sm:mb-0 sm:mr-4 lg:mr-6 flex-shrink-0">1</div>
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Prepare Documents</h3>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-700 mb-4 sm:mb-6 leading-relaxed">Gather all necessary documents for your application:</p>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Transcript of Records (certified true copy)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Diploma or Certificate of Graduation</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Birth Certificate (NSO issued)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Research Proposal (3-5 pages)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Recommendation Letters (2-3 letters)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Center Circle -->
                        <div class="hidden lg:flex w-2/12 justify-center">
                            <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-3xl shadow-lg border-4 border-white z-10">
                                1
                            </div>
                        </div>
                        
                        <!-- Right Content (Desktop) / Hidden (Mobile) -->
                        <div class="hidden lg:block w-5/12">
                            <div class="bg-green-50 rounded-2xl p-10">
                                <div class="text-center">
                                    <svg class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-green-600 mx-auto mb-4 sm:mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h4 class="text-2xl font-bold text-green-800 mb-4">Document Preparation</h4>
                                    <p class="text-lg text-green-700">Ensure all documents are properly certified and in the required format before proceeding to the next step.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  

                <!-- Step 2 -->
                <div class="relative mb-8 sm:mb-12 lg:mb-20">
                    <div class="flex flex-col lg:flex-row-reverse items-start lg:items-center">
                        <!-- Right Content (Desktop) / Full Width (Mobile) -->
                        <div class="w-full lg:w-5/12 lg:pl-12">
                            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 lg:p-10 shadow-lg border border-gray-100">
                                <div class="flex items-center mb-4 sm:mb-6">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg sm:text-xl lg:text-2xl mr-4 sm:mr-6 flex-shrink-0">2</div>
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 leading-tight">Submit Application</h3>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-700 mb-4 sm:mb-6 leading-relaxed">Complete the online application through our portal:</p>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Create an account on the ERDT portal</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Fill out personal and academic information</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Upload required documents (PDF format)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Submit research proposal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Center Circle -->
                        <div class="hidden lg:flex w-2/12 justify-center">
                            <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-3xl shadow-lg border-4 border-white z-10">
                                2
                            </div>
                        </div>
                        
                        <!-- Left Content (Desktop) / Hidden (Mobile) -->
                        <div class="hidden lg:block w-5/12">
                            <div class="bg-blue-50 rounded-2xl p-10">
                                <div class="text-center">
                                    <svg class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-blue-600 mx-auto mb-4 sm:mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                    <h4 class="text-2xl font-bold text-blue-800 mb-4">Online Submission</h4>
                                    <p class="text-lg text-blue-700">Complete your application online with our user-friendly portal system designed for easy navigation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative mb-8 sm:mb-12 lg:mb-20">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center">
                        <!-- Left Content (Desktop) / Full Width (Mobile) -->
                        <div class="w-full lg:w-5/12 lg:pr-12">
                            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 lg:p-10 shadow-lg border border-gray-100">
                                <div class="flex items-center mb-4 sm:mb-6">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg sm:text-xl lg:text-2xl mr-4 sm:mr-6 flex-shrink-0">3</div>
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 leading-tight">Take Examination</h3>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-700 mb-4 sm:mb-6 leading-relaxed">Pass the comprehensive qualifying examination:</p>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Written examination on engineering fundamentals</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Research methodology assessment</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">English proficiency test</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Minimum passing score: 75%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Center Circle -->
                        <div class="hidden lg:flex w-2/12 justify-center">
                            <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-3xl shadow-lg border-4 border-white z-10">
                                3
                            </div>
                        </div>
                        
                        <!-- Right Content (Desktop) / Hidden (Mobile) -->
                        <div class="hidden lg:block w-5/12">
                            <div class="bg-purple-50 rounded-2xl p-10">
                                <div class="text-center">
                                    <svg class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-purple-600 mx-auto mb-4 sm:mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <h4 class="text-2xl font-bold text-purple-800 mb-4">Qualifying Exam</h4>
                                    <p class="text-lg text-purple-700">Demonstrate your knowledge and readiness for advanced engineering studies through our comprehensive assessment.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative mb-8 sm:mb-12 lg:mb-16">
                    <div class="flex flex-col lg:flex-row-reverse items-center">
                        <!-- Right Content (Desktop) / Full Width (Mobile) -->
                        <div class="w-full lg:w-5/12 lg:pl-12">
                            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 lg:p-10 shadow-lg border border-gray-100">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 sm:mb-6">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg sm:text-xl lg:text-2xl mb-3 sm:mb-0 sm:mr-4 lg:mr-6 flex-shrink-0">4</div>
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Attend Interview</h3>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-700 mb-4 sm:mb-6 leading-relaxed">Participate in the final interview process:</p>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Panel interview with faculty members</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Research proposal presentation</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Q&A session about academic goals</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 mr-3 sm:mr-4 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm sm:text-base lg:text-lg text-gray-700">Assessment of commitment and motivation</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Center Circle -->
                        <div class="hidden lg:flex w-2/12 justify-center">
                            <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-3xl shadow-lg border-4 border-white z-10">
                                4
                            </div>
                        </div>
                        
                        <!-- Left Content (Desktop) / Hidden (Mobile) -->
                        <div class="hidden lg:block w-5/12">
                            <div class="bg-orange-50 rounded-2xl p-10">
                                <div class="text-center">
                                    <svg class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-orange-600 mx-auto mb-4 sm:mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h4 class="text-2xl font-bold text-orange-800 mb-4">Final Interview</h4>
                                    <p class="text-lg text-orange-700">Showcase your passion, research interests, and commitment to excellence in our comprehensive interview process.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Downloadable Forms Section -->
<div class="py-12 sm:py-16 lg:py-24 bg-white" id="downloadable-forms">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center mb-12 sm:mb-16 lg:mb-20">
            <div class="inline-flex items-center px-8 py-4 rounded-full text-xl font-medium mb-8 bg-green-100 text-green-700">
                <svg class="w-8 h-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Required Forms
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6 sm:mb-8">Downloadable Forms</h2>
            <p class="text-lg sm:text-xl lg:text-2xl text-gray-700 max-w-5xl mx-auto leading-relaxed px-4">Access all essential forms for your ERDT scholarship application. Each form is categorized for easy navigation and understanding.</p>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Essential Application Forms -->
            <div class="mb-12 sm:mb-16">
                <div class="flex items-center mb-6 sm:mb-8 px-4 sm:px-0">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mr-4 sm:mr-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Essential Application Forms</h3>
                        <p class="text-xl text-gray-600">Primary forms required for all ERDT scholarship applications</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                    @if(isset($downloadableForms['Essential Application Forms']) && $downloadableForms['Essential Application Forms']->count() > 0)
                        @foreach($downloadableForms['Essential Application Forms'] as $index => $form)
                            @php
                                $colors = [
                                    ['from' => 'blue-50', 'to' => 'blue-100', 'border' => 'blue-200', 'bg' => 'blue-600', 'text' => 'blue-700', 'hover' => 'blue-700'],
                                    ['from' => 'purple-50', 'to' => 'purple-100', 'border' => 'purple-200', 'bg' => 'purple-600', 'text' => 'purple-700', 'hover' => 'purple-700'],
                                    ['from' => 'indigo-50', 'to' => 'indigo-100', 'border' => 'indigo-200', 'bg' => 'indigo-600', 'text' => 'indigo-700', 'hover' => 'indigo-700'],
                                    ['from' => 'teal-50', 'to' => 'teal-100', 'border' => 'teal-200', 'bg' => 'teal-600', 'text' => 'teal-700', 'hover' => 'teal-700']
                                ];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div class="bg-gradient-to-br from-{{ $color['from'] }} to-{{ $color['to'] }} rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-{{ $color['border'] }} hover:shadow-xl transition-all duration-300 mx-2 sm:mx-0">
                                <div class="flex flex-col sm:flex-row items-start mb-4 sm:mb-6">
                                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 xl:w-16 xl:h-16 bg-{{ $color['bg'] }} text-white rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-0 sm:mr-4 lg:mr-6 mx-auto sm:mx-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 text-center sm:text-left">
                                        <h4 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $form->title }}</h4>
                                        <p class="text-sm sm:text-base lg:text-lg text-gray-700 mb-4 sm:mb-6 leading-relaxed">{{ $form->description }}</p>
                                        <div class="flex items-center justify-center sm:justify-start text-xs sm:text-sm text-{{ $color['text'] }} mb-3 sm:mb-4">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                            <span>{{ $form->getFileSize() }} • {{ $form->download_count }} downloads</span>
                                        </div>
                                        <a href="{{ route('downloadable-forms.download', $form) }}" class="inline-flex items-center bg-{{ $color['bg'] }} text-white hover:bg-{{ $color['hover'] }} active:bg-{{ $color['active'] ?? $color['hover'] }} focus:outline-none focus:ring-4 focus:ring-{{ $color['bg'] }}/30 font-semibold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl transition-all duration-300 text-sm sm:text-base lg:text-lg shadow-lg hover:shadow-xl w-full sm:w-auto justify-center min-h-[44px] touch-manipulation">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 lg:w-5 lg:h-5 mr-2 sm:mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Download {{ strtoupper($form->getFileExtension()) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-2 text-center py-12">
                            <div class="w-16 h-16 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">No essential application forms available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Research & Grant Forms -->
            <div class="mb-12 sm:mb-16">
                <div class="flex items-center mb-6 sm:mb-8 px-4 sm:px-0">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-green-600 text-white rounded-full flex items-center justify-center mr-4 sm:mr-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Research & Grant Forms</h3>
                        <p class="text-xl text-gray-600">Specialized forms for research projects and grant applications</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                    @if(isset($downloadableForms['Research & Grant Forms']) && $downloadableForms['Research & Grant Forms']->count() > 0)
                        @foreach($downloadableForms['Research & Grant Forms'] as $form)
                            @php
                                $icons = [
                                    'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                    'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                    'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                                    'M13 10V3L4 14h7v7l9-11h-7z',
                                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                ];
                                $icon = $icons[($loop->index) % count($icons)];
                            @endphp
                            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border-2 border-green-200 hover:border-green-400 hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-green-100 text-green-600 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto sm:mx-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                    </svg>
                                </div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3 text-center sm:text-left">{{ $form->title }}</h4>
                                <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4 leading-relaxed text-center sm:text-left">{{ Str::limit($form->description, 80) }}</p>
                                <div class="flex items-center justify-center sm:justify-start text-xs text-gray-500 mb-3">
                                    <span>{{ $form->getFileSize() }} • {{ $form->download_count }} downloads</span>
                                </div>
                                <a href="{{ route('downloadable-forms.download', $form) }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium py-3 sm:py-4 px-4 sm:px-6 rounded-lg transition-all duration-300 text-sm sm:text-base w-full sm:w-auto justify-center min-h-[44px] touch-manipulation">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download {{ strtoupper($form->getFileExtension()) }}
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-3 text-center py-12">
                            <div class="w-16 h-16 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">No research & grant forms available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Administrative & Monitoring Forms -->
            <div class="mb-12 sm:mb-16">
                <div class="flex items-center mb-6 sm:mb-8 px-4 sm:px-0">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-orange-600 text-white rounded-full flex items-center justify-center mr-4 sm:mr-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Administrative & Monitoring Forms</h3>
                        <p class="text-xl text-gray-600">Forms for financial reporting, progress tracking, and administrative purposes</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                    @if(isset($downloadableForms['Administrative & Monitoring Forms']) && $downloadableForms['Administrative & Monitoring Forms']->count() > 0)
                        @foreach($downloadableForms['Administrative & Monitoring Forms'] as $form)
                            @php
                                $icons = [
                                    'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                    'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                ];
                                $icon = $icons[($loop->index) % count($icons)];
                            @endphp
                            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border-2 border-orange-200 hover:border-orange-400 hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-100 text-orange-600 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto sm:mx-0">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                    </svg>
                                </div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3 text-center sm:text-left">{{ $form->title }}</h4>
                                <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4 leading-relaxed text-center sm:text-left">{{ Str::limit($form->description, 80) }}</p>
                                <div class="flex items-center justify-center sm:justify-start text-xs text-gray-500 mb-3">
                                    <span>{{ $form->getFileSize() }} • {{ $form->download_count }} downloads</span>
                                </div>
                                <a href="{{ route('downloadable-forms.download', $form) }}" class="inline-flex items-center bg-orange-600 text-white hover:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-4 focus:ring-orange-300 font-medium py-3 sm:py-4 px-4 sm:px-6 rounded-lg transition-all duration-300 text-sm sm:text-base w-full sm:w-auto justify-center min-h-[44px] touch-manipulation">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download {{ strtoupper($form->getFileExtension()) }}
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-3 text-center py-12">
                            <div class="w-16 h-16 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">No administrative & monitoring forms available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Download All Section -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-12 text-center border border-gray-200 mx-4 sm:mx-0">
                <div class="max-w-3xl mx-auto">
                    <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Download All Forms</h3>
                    <p class="text-lg sm:text-xl text-gray-600 mb-6 sm:mb-8 leading-relaxed px-4">Save time by downloading all required forms at once. Perfect for offline completion and comprehensive application preparation.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <button onclick="downloadAllForms()" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl text-lg min-h-[44px] touch-manipulation">
                            <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                            </svg>
                            Download All Forms
                        </button>
                        <div class="text-sm text-gray-500">
                            <span class="font-medium">10 forms</span> • Multiple formats (PDF, DOCX, XLSX)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="py-12 sm:py-16 bg-gray-50" id="timeline">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center px-5 py-3 rounded-full text-base font-medium mb-5 bg-green-100 text-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Academic Calendar
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-5">Application Timeline</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed px-2">Important dates and deadlines for the ERDT scholarship application process.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            @if($timelines->count() > 0)
                <!-- View Toggle Component (Mobile Only) -->
                <div class="flex justify-end mb-5 md:hidden px-4">
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <button type="button" id="card-view-btn" class="px-5 py-3 text-base font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-green-500 min-h-[48px] touch-manipulation">
                            <svg class="w-5 h-5 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"></path>
                                <path d="M11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Cards
                        </button>
                        <button type="button" id="table-view-btn" class="px-5 py-3 text-base font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-green-500 min-h-[48px] touch-manipulation">
                            <svg class="w-5 h-5 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
                            </svg>
                            Table
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="md:hidden timeline-cards px-4 space-y-5">
                    @foreach($timelines as $index => $timeline)
                        <div class="bg-white rounded-lg shadow p-5 border border-gray-200">
                            <h3 class="font-medium text-gray-900 text-base sm:text-lg mb-4 leading-tight break-words">{{ $timeline->activity }}</h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 p-4 rounded">
                                    <span class="text-xs font-medium text-gray-500 uppercase block mb-2">First Semester</span>
                                    <p class="text-base text-gray-700 break-words leading-relaxed">{{ $timeline->first_semester }}</p>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded">
                                    <span class="text-xs font-medium text-gray-500 uppercase block mb-2">Second Semester</span>
                                    <p class="text-base text-gray-700 break-words leading-relaxed">{{ $timeline->second_semester }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block timeline-table bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto relative">
                        <table class="min-w-full">
                            <thead style="background: linear-gradient(to right, #22c55e, #16a34a);" class="text-white">
                                <tr>
                                    <th class="sticky left-0 z-20 py-5 px-6 text-left font-semibold text-base md:text-lg" style="background: linear-gradient(to right, #22c55e, #16a34a);">Activity</th>
                                    <th class="py-5 px-6 text-left font-semibold text-base md:text-lg">First Semester</th>
                                    <th class="py-5 px-6 text-left font-semibold text-base md:text-lg">Second Semester</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($timelines as $index => $timeline)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-green-50 transition-colors duration-200">
                                        <td class="sticky left-0 z-10 py-5 px-6 text-gray-800 font-medium text-base break-words leading-relaxed {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-green-50">{{ $timeline->activity }}</td>
                                        <td class="py-5 px-6 text-gray-600 text-base break-words leading-relaxed">{{ $timeline->first_semester }}</td>
                                        <td class="py-5 px-6 text-gray-600 text-base break-words leading-relaxed">{{ $timeline->second_semester }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="bg-gray-100 p-4 rounded-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Timeline Information Coming Soon</h3>
                    <p class="text-gray-500">Academic calendar details will be updated shortly.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Important Notes Section -->
<div class="py-12 sm:py-16 bg-white" id="important-notes">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                Important Information
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4">Important Notes</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Please read these important notes carefully before submitting your application.</p>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-0">
            @if($importantNotes->count() > 0)
                <div class="space-y-4 sm:space-y-6">
                    @foreach($importantNotes as $note)
                        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-300 mx-2 sm:mx-0">
                            <div class="flex flex-col sm:flex-row items-start">
                                <div class="flex-shrink-0 bg-green-100 text-green-700 p-2 sm:p-3 rounded-lg mb-3 sm:mb-0 sm:mr-4 mx-auto sm:mx-0">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="text-center sm:text-left w-full">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $note->title }}</h3>
                                    <div class="text-sm sm:text-base text-gray-600 leading-relaxed">{!! $note->content !!}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="bg-gray-100 p-4 rounded-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Important Notes</h3>
                    <p class="text-gray-500">All important information is included in the application process above.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Call to Action Section -->
 <div class="py-12 sm:py-16 lg:py-20" style="background-image: url('{{ asset('images/bg1.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
           <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 sm:mb-6">Ready to Begin Your Journey?</h2>
           <p class="text-base sm:text-lg lg:text-xl text-white mb-8 sm:mb-10 lg:mb-12 leading-relaxed px-4">
                Take the first step towards becoming an ERDT scholar. Join a community of exceptional engineers and researchers who are shaping the future of technology in the Philippines.
            </p>

            <div class="flex flex-col sm:flex-row gap-8 justify-center">
                <a href="{{ route('scholar-login') }}"  class="inline-flex items-center bg-white text-green-700 hover:bg-gray-100 font-bold py-5 px-10 rounded-lg text-lg transition-colors duration-300 shadow-lg">
                    <svg class="h-8 w-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Start Application
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

    // Download all forms function
    function downloadAllForms() {
        const forms = [
            '{{ asset("forms/ERDT_Application_Form.pdf") }}',
            '{{ asset("forms/Deed-of-Undertaking-form.pdf") }}',
            '{{ asset("forms/ERDT-SP-form-2024.pdf") }}',
            '{{ asset("forms/ERDT-TDG-form.pdf") }}',
            '{{ asset("forms/ERDT-RG-Form-with-sample-LIB.pdf") }}',
            '{{ asset("forms/ERDT-RDG-form.pdf") }}',
            '{{ asset("forms/ERDT-LIQUIDATION-REPORT-FOR-OUTRIGHT-THESIS-GRANT.docx") }}',
            '{{ asset("forms/ERDT-Monitoring-FORM.docx") }}',
            '{{ asset("forms/Form_SFA-for-scholar.xlsx") }}'
        ];

        // Show downloading message
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Downloading...';
        button.disabled = true;

        // Download each form with a small delay
        forms.forEach((formUrl, index) => {
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = formUrl;
                link.download = '';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, index * 500); // 500ms delay between downloads
        });

        // Reset button after all downloads
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, forms.length * 500 + 1000);
    }
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
<x-footer />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardViewBtn = document.getElementById('card-view-btn');
        const tableViewBtn = document.getElementById('table-view-btn');
        const cardView = document.querySelector('.timeline-cards');
        const tableView = document.querySelector('.timeline-table');
        
        // Check for saved preference
        const viewPreference = localStorage.getItem('timelineViewPreference') || 'card';
        
        // Set initial view based on preference
        if (viewPreference === 'card') {
            if (cardView) cardView.classList.remove('hidden');
            if (tableView) tableView.classList.add('hidden');
            if (cardViewBtn) {
                cardViewBtn.classList.add('bg-green-100');
                cardViewBtn.setAttribute('aria-pressed', 'true');
            }
            if (tableViewBtn) {
                tableViewBtn.setAttribute('aria-pressed', 'false');
            }
        } else {
            if (tableView) tableView.classList.remove('hidden');
            if (cardView) cardView.classList.add('hidden');
            if (tableViewBtn) {
                tableViewBtn.classList.add('bg-green-100');
                tableViewBtn.setAttribute('aria-pressed', 'true');
            }
            if (cardViewBtn) {
                cardViewBtn.setAttribute('aria-pressed', 'false');
            }
        }
        
        // Toggle to card view
        if (cardViewBtn) {
            cardViewBtn.addEventListener('click', function() {
                if (cardView) {
                    cardView.classList.remove('hidden');
                    // Add smooth transition
                    cardView.classList.add('fade-in-up');
                    setTimeout(() => {
                        cardView.classList.remove('fade-in-up');
                    }, 600);
                }
                if (tableView) tableView.classList.add('hidden');
                cardViewBtn.classList.add('bg-green-100');
                cardViewBtn.setAttribute('aria-pressed', 'true');
                if (tableViewBtn) {
                    tableViewBtn.classList.remove('bg-green-100');
                    tableViewBtn.setAttribute('aria-pressed', 'false');
                }
                localStorage.setItem('timelineViewPreference', 'card');
            });
        }
        
        // Toggle to table view
        if (tableViewBtn) {
            tableViewBtn.addEventListener('click', function() {
                if (tableView) {
                    tableView.classList.remove('hidden');
                    // Add smooth transition
                    tableView.classList.add('fade-in-up');
                    setTimeout(() => {
                        tableView.classList.remove('fade-in-up');
                    }, 600);
                }
                if (cardView) cardView.classList.add('hidden');
                tableViewBtn.classList.add('bg-green-100');
                tableViewBtn.setAttribute('aria-pressed', 'true');
                if (cardViewBtn) {
                    cardViewBtn.classList.remove('bg-green-100');
                    cardViewBtn.setAttribute('aria-pressed', 'false');
                }
                localStorage.setItem('timelineViewPreference', 'table');
            });
        }
    });
</script>
@endsection

