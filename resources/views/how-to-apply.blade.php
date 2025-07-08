@extends('layouts.app')

@section('content')
<!-- Include the navigation component -->
<x-navigation />

<!-- Hero Section -->
<div class="bg-gray-50 py-12 p-12">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Application Guide
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                <span class="block">Join the</span>
                <span class="block text-green-700">ERDT Program</span>
            </h1>
            <h2 class="text-xl md:text-2xl font-medium text-gray-600 mb-8">
                Your Gateway to Engineering Excellence
            </h2>
            <p class="text-lg text-gray-700 mb-12 max-w-3xl mx-auto">
                Discover how to become part of the Engineering Research & Development for Technology program at CLSU. Follow our comprehensive guide to start your scholarship journey.
            </p>

            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('scholar-login') }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Apply Now
                </a>
                <a href="#eligibility" class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                    <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Check Eligibility
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
                <div class="text-4xl font-bold text-green-700 mb-2">₱300K</div>
                <div class="text-gray-600 text-base">Research Grant</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-700 mb-2">100%</div>
                <div class="text-gray-600 text-base">Tuition Coverage</div>
            </div>
        </div>
    </div>
</div>

<!-- Eligibility Section -->
<div class="py-16 bg-gray-50" id="eligibility">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Eligibility Requirements
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Who Can Apply?</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Check if you meet our scholarship requirements before starting your application.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- General Requirements -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">General Requirements</h3>
                        <ul class="text-base text-gray-600 mt-3 space-y-2">
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
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Academic Requirements</h3>
                        <ul class="text-base text-gray-600 mt-3 space-y-2">
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
<div class="py-16 bg-white" id="application-process">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Step-by-Step Guide
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Application Process</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Follow these steps to complete your ERDT scholarship application successfully.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="space-y-8">
                <!-- Step 1 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-6">1</div>
                    <div class="flex-1 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Prepare Required Documents</h3>
                        <p class="text-gray-600 mb-3">Gather all necessary documents for your application:</p>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Transcript of Records (certified true copy)</li>
                            <li>• Diploma or Certificate of Graduation</li>
                            <li>• Birth Certificate (NSO issued)</li>
                            <li>• Research Proposal (3-5 pages)</li>
                            <li>• Recommendation Letters (2-3 letters)</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-6">2</div>
                    <div class="flex-1 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Submit Online Application</h3>
                        <p class="text-gray-600 mb-3">Complete the online application form through our portal:</p>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Create an account on the ERDT portal</li>
                            <li>• Fill out personal and academic information</li>
                            <li>• Upload required documents (PDF format)</li>
                            <li>• Submit research proposal</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-6">3</div>
                    <div class="flex-1 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Take Qualifying Examination</h3>
                        <p class="text-gray-600 mb-3">Pass the comprehensive qualifying examination:</p>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Written examination on engineering fundamentals</li>
                            <li>• Research methodology assessment</li>
                            <li>• English proficiency test</li>
                            <li>• Minimum passing score: 75%</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-6">4</div>
                    <div class="flex-1 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Attend Interview</h3>
                        <p class="text-gray-600 mb-3">Participate in the final interview process:</p>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Panel interview with faculty members</li>
                            <li>• Research proposal presentation</li>
                            <li>• Q&A session about academic goals</li>
                            <li>• Assessment of commitment and motivation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Downloadable Forms Section -->
<div class="py-16 bg-gray-50" id="downloadable-forms">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Required Forms
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Downloadable Forms</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Download and complete the required forms for your ERDT scholarship application.</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Application Form -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">ERDT Application Form</h3>
                            <p class="text-sm text-gray-600 mb-4">Main application form for ERDT scholarship program</p>
                            <a href="{{ asset('forms/ERDT_Application_Form.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Deed of Undertaking -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Deed of Undertaking</h3>
                            <p class="text-sm text-gray-600 mb-4">Commitment form for scholarship obligations and responsibilities</p>
                            <a href="{{ asset('forms/Deed-of-Undertaking-form.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Special Project Form -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Special Project Form</h3>
                            <p class="text-sm text-gray-600 mb-4">Form for special project applications and research proposals</p>
                            <a href="{{ asset('forms/ERDT-SP-form-2024.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Thesis Dissertation Grant -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Thesis Dissertation Grant</h3>
                            <p class="text-sm text-gray-600 mb-4">Application form for thesis and dissertation grants</p>
                            <a href="{{ asset('forms/ERDT-TDG-form.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Research Grant Form -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Research Grant Form</h3>
                            <p class="text-sm text-gray-600 mb-4">Application form for research grants with sample library</p>
                            <a href="{{ asset('forms/ERDT-RG-Form-with-sample-LIB.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Research Development Grant -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Research Development Grant</h3>
                            <p class="text-sm text-gray-600 mb-4">Form for research development grant applications</p>
                            <a href="{{ asset('forms/ERDT-RDG-form.pdf') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Liquidation Report -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Liquidation Report</h3>
                            <p class="text-sm text-gray-600 mb-4">Liquidation report form for outright thesis grant</p>
                            <a href="{{ asset('forms/ERDT-LIQUIDATION-REPORT-FOR-OUTRIGHT-THESIS-GRANT.docx') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download DOCX
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Monitoring Form -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Monitoring Form</h3>
                            <p class="text-sm text-gray-600 mb-4">Scholar progress monitoring and evaluation form</p>
                            <a href="{{ asset('forms/ERDT-Monitoring-FORM.docx') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download DOCX
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SFA Form -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 bg-green-100 text-green-700 p-3 rounded-lg mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">SFA Form for Scholar</h3>
                            <p class="text-sm text-gray-600 mb-4">Scholar Financial Assistance form and documentation</p>
                            <a href="{{ asset('forms/Form_SFA-for-scholar.xlsx') }}" download class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download XLSX
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download All Button -->
            <div class="text-center mt-12">
                <div class="bg-white rounded-lg shadow-md p-6 inline-block">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Need All Forms?</h3>
                    <p class="text-gray-600 mb-6">Download all forms at once for your convenience.</p>
                    <button onclick="downloadAllForms()" class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        </svg>
                        Download All Forms
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="py-16 bg-gray-50" id="timeline">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Academic Calendar
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Application Timeline</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Important dates and deadlines for the ERDT scholarship application process.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            @if($timelines->count() > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead style="background: linear-gradient(to right, #22c55e, #16a34a);" class="text-white">
                                <tr>
                                    <th class="py-4 px-6 text-left font-semibold">Activity</th>
                                    <th class="py-4 px-6 text-left font-semibold">First Semester</th>
                                    <th class="py-4 px-6 text-left font-semibold">Second Semester</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($timelines as $index => $timeline)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-green-50 transition-colors duration-200">
                                        <td class="py-4 px-6 text-gray-800 font-medium">{{ $timeline->activity }}</td>
                                        <td class="py-4 px-6 text-gray-600">{{ $timeline->first_semester }}</td>
                                        <td class="py-4 px-6 text-gray-600">{{ $timeline->second_semester }}</td>
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
<div class="py-16 bg-white" id="important-notes">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                Important Information
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Important Notes</h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Please read these important notes carefully before submitting your application.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            @if($importantNotes->count() > 0)
                <div class="space-y-6">
                    @foreach($importantNotes as $note)
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-green-100 text-green-700 p-2 rounded-lg mr-4">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $note->title }}</h3>
                                    <div class="text-gray-600 leading-relaxed">{!! $note->content !!}</div>
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
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Ready to Begin Your Journey?</h2>
            <p class="text-xl text-gray-700 mb-12 leading-relaxed">
                Take the first step towards becoming an ERDT scholar. Join a community of exceptional engineers and researchers who are shaping the future of technology in the Philippines.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('scholar-login') }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Start Application
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                    <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
@endsection
