@extends('layouts.app')

@section('content')
    <nav class="bg-white shadow-md px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-10 mr-3">
                    <span class="logo-text font-bold text-gray-800 text-xl ml-2" >CLSU-ERDT</span>
                </div>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('scholar-login') }}" class="text-gray-600 hover:text-blue-800 transition">Home</a>
                <a href="{{ route('how-to-apply') }}" class="text-gray-600 hover:text-blue-800 transition">How to Apply</a>
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-800 transition">About</a>
                <a href="{{ route('history') }}" class="text-gray-600 hover:text-blue-800 transition">History</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4">
            <a href="{{ route('scholar-login') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">Home</a>
            <a href="{{ route('how-to-apply') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">How to Apply</a>
            <a href="{{ route('about') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">About</a>
            <a href="{{ route('history') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">History</a>
        </div>
    </nav>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">How to Apply</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-gray-700 leading-relaxed mb-4">Applying for the CLSU-ERDT Engineering Scholarship is a straightforward process designed to identify promising individuals dedicated to advancing in engineering and technology. Follow the steps below to ensure a successful application:</p>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Application Steps:</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6 responsive-grid-how-to-apply">
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">1. Review Eligibility</h3>
                    <p class="text-gray-700 text-base">Before applying, ensure you meet all the eligibility criteria outlined on our official website. This includes academic requirements, citizenship, and any specific program prerequisites.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">2. Prepare Documents</h3>
                    <p class="text-gray-700 text-base">Gather all necessary documents, such as academic transcripts, recommendation letters, a personal statement, and any other supporting materials. Make sure all documents are up-to-date and accurately reflect your qualifications.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">3. Complete Online Application</h3>
                    <p class="text-gray-700 text-base">Fill out the online application form thoroughly. Provide accurate information and double-check all entries before submission.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">4. Submit Supporting Documents</h3>
                    <p class="text-gray-700 text-base">Upload your prepared documents through the designated portal in the online application system. Ensure that all files are in the required format (e.g., PDF) and are clearly labeled.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">5. Attend Interview (if required)</h3>
                    <p class="text-gray-700 text-base">Shortlisted applicants may be invited for an interview. This is an opportunity to discuss your academic background, research interests, and career aspirations in more detail.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V5m6 10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">6. Await Notification</h3>
                    <p class="text-gray-700 text-base">After the evaluation process, successful applicants will be notified via email. Please ensure your contact information is correct and regularly check your inbox.</p>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Important Reminders:</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
            <li>All applications must be submitted by the specified deadline. Late submissions will not be considered.</li>
            <li>Ensure all information provided is truthful and accurate. Any misrepresentation may lead to disqualification.</li>
            <li>For any inquiries, please refer to our FAQ section or contact our support team through the provided channels.</li>
        </ul>

        <div class="text-center mt-8">
            <a href="{{ route('scholar-login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                Start Your Application Today!
            </a>
        </div>
    </div>
</div>
@endsection