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
<div class="relative bg-cover bg-center py-20" style="background-image: url('{{ asset('storage/bg/bgloginscholar.png') }}');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto relative text-white text-center py-12">
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">About the CLSU-ERDT Program</h1>
        <p class="text-lg md:text-xl mb-8">Fostering advanced education and research in engineering and technology for national development.</p>
        <a href="{{ route('how-to-apply') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
            Learn How to Apply
        </a>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="bg-white shadow-md rounded-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Our Vision and Mission</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 responsive-grid-about-vision">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3 flex items-center leading-tight">
                    <svg class="h-7 w-7 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Our Vision
                </h3>
                <p class="text-gray-700 text-lg leading-relaxed">
                    To be a leading center for engineering research and development, producing globally competitive engineers and innovators who drive sustainable progress and address societal challenges.
                </p>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3 flex items-center leading-tight">
                    <svg class="h-7 w-7 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Our Mission
                </h3>
                <p class="text-gray-700 text-lg leading-relaxed">
                    To provide comprehensive financial assistance, access to cutting-edge research facilities, and mentorship from distinguished faculty members. We emphasize interdisciplinary research, innovation, and the application of engineering solutions to real-world challenges, contributing significantly to national development and global competitiveness.
                </p>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Why Choose CLSU-ERDT?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center responsive-grid-about-why-choose">
            <div class="p-6 bg-gray-50 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 leading-tight">Academic Excellence</h3>
                <p class="text-gray-700 text-base">Benefit from robust engineering programs and a wide range of expertise within a nationwide consortium of top universities.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 leading-tight">State-of-the-Art Facilities</h3>
                <p class="text-gray-700 text-base">Access cutting-edge research facilities and diverse opportunities to advance your studies and research.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:shadow-lg hover:scale-105">
                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-9a2 2 0 00-2-2h-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v3H5a2 2 0 00-2 2v8m14-8a2 2 0 00-2-2H9a2 2 0 00-2 2m7-8h-3m3 0h3" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 leading-tight">Expert Mentorship</h3>
                <p class="text-gray-700 text-base">Receive guidance from distinguished faculty members and contribute to significant advancements in engineering.</p>
            </div>
        </div>
    </div>
</div>
@endsection