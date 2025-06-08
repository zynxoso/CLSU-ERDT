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
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Welcome to CLSU-ERDT</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-gray-700 leading-relaxed">This is the home page of the CLSU-ERDT Engineering Scholarship Management System.</p>
        <!-- Add more content here -->
    </div>
</div>
@endsection
