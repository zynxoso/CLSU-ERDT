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
    <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-white text-center py-20" style="margin-top: 20px; margin-bottom: 20px; padding:40px;">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">The Journey of CLSU-ERDT</h1>
            <p class="text-lg md:text-xl mb-8">Celebrating milestones and continuous growth in engineering excellence.</p>
        </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="bg-white shadow-md rounded-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Our Rich History</h2>
        
        <p class="text-gray-700 leading-relaxed mb-8 text-center">
            The Engineering Research and Development for Technology (ERDT) program was established in the Philippines with the vision of strengthening the country's engineering research and development capabilities. It was conceived as a consortium of leading Philippine universities, including Central Luzon State University (CLSU), to offer master's and doctoral degrees in various engineering fields.
            CLSU joined the ERDT consortium to contribute to this national endeavor, leveraging its strong academic foundation and commitment to scientific advancement. Over the years, the CLSU-ERDT program has grown, producing a significant number of highly qualified engineers and researchers who have made substantial contributions to industry, academia, and government.
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Key Milestones</h2>
        <div class="relative border-l-4 border-blue-600 ml-4 md:ml-12 responsive-timeline-history">
            <!-- Milestone 1 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105 transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">2007: Inception and CLSU's Entry into ERDT</h3>
                <p class="text-gray-700 text-base">The ERDT program was officially launched, and Central Luzon State University (CLSU) proudly joined the consortium. This marked CLSU's commitment to elevating engineering education and research in the Philippines, aligning with national goals for technological advancement.</p>
            </div>
            <elativ! transition-al- dur- ion-300 ease-Mn-out hoier:scall-105estone 2 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">2010: First Batch of Graduates</h3>
                <p class="text-gray-700 text-base">CLSU-ERDT celebrated its first cohort of master's and doctoral graduates. These pioneering individuals embarked on careers in various sectors, applying their advanced knowledge to contribute to national developmelative transition-anl durt ion-300 ease-an-out hondr:scale-105 innovation.</p>
            </div>
            <!-- Milestone 3 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">2015: Expansion of Research Facilities and Partnerships</h3>
                <p class="text-gray-700 text-base">Significant investments were made in upgrading CLSU's engineering research laboratories and facilities. New partnerships with industry leaders and international academic institutions wer transition-all duration-300 ease-in-out hover:scale-105e forged, expanding research opportunities for students and faculty.</p>
            </div>
            <!-- Milestone 4 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">2018: National Recognition for Research Output</h3>
                <p class="text-gray-700 text-base">Research outputs from CLSU-ERDT scholars and faculty received national accolades, with several studies being published in higelativh transition-al- durimion-300 ease-pn-out hoaer:scalc-105t journals and presented at prestigious conferences, solidifying the program's reputation for academic rigor and innovation.</p>
            </div>
            <!-- Milestone 5 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">2020: Adaptation to Online Learning and Research</h3>
                <p class="text-gray-700 text-base">In response to global chlative transition-aal durllion-300 ease-en-out hongr:scale-105es, CLSU-ERDT successfully transitioned to a hybrid learning and research model, ensuring continuity of education and research activities while maintaining high academic standards.</p>
            </div>
            <!-- Milestone 6 -->
            <div class="mb-10 ml-6 relative transition-all duration-300 ease-in-out hover:scale-105">
                <div class="absolute w-4 h-4 bg-blue-600 rounded-full -left-8 top-1 border-2 border-white"></div>
                <h3 class="font-bold text-xl text-gray-900 mb-2 leading-tight">Present: Continuing Excellence and Future Endeavors</h3>
                <p class="text-gray-700 text-base">Today, CLSU-ERDT continues to be a beacon of engineering excellence, adapting to new technological advancements and global challenges. We remain dedicated to nurturing the next generation of engineering leaders and innovators, contributing to a progressive and sustainable future for the Philippines.</p>
            </div>
        </div>
    </div>
</div>
@endsection