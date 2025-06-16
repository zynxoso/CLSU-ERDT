<!-- Footer Component for CLSU-ERDT Templates -->
<footer class="bg-gray-100 pt-8 md:pt-12 pb-4 md:pb-6">
    <div class="container mx-auto px-4">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-7 md:gap-8 mb-8 md:mb-12">
            <!-- Column 1: About -->
            <!-- <div class="text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start mb-4">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-8 mr-2">
                    <span class="font-bold text-gray-800 text-lg">CLSU-ERDT</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Fostering advanced education and research in engineering and technology for national development.
                </p>
            </div> -->
            
            <!-- Column 2: About Us -->
            <div class="text-center sm:text-left">
                <h3 class="font-semibold text-gray-800 mb-4">About Us</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Our Vision & Mission</a></li>
                    <li><a href="{{ route('about') }}#leadership" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Leadership</a></li>
                    <li><a href="{{ route('history') }}" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Our History</a></li>
                    <li><a href="{{ route('about') }}#faculty" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Faculty & Expertise</a></li>
                </ul>
            </div>
            
            <!-- Column 3: Resources -->
            <div class="text-center sm:text-left">
                <h3 class="font-semibold text-gray-800 mb-4">Resources</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('how-to-apply') }}" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Application Guide</a></li>
                    <li><a href="{{ asset('storage/forms/ERDT_Application_Form.pdf') }}" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Application Form</a></li>
                    <li><a href="{{ route('how-to-apply') }}#faq" class="text-gray-600 hover:text-blue-600 text-sm inline-block">FAQs</a></li>
                    <li><a href="https://sei.dost.gov.ph" target="_blank" class="text-gray-600 hover:text-blue-600 text-sm inline-block">DOST-SEI Website</a></li>
                </ul>
            </div>
            
            <!-- Column 4: Programs -->
            <div class="text-center sm:text-left">
                <h3 class="font-semibold text-gray-800 mb-4">Programs</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm inline-block">MS Scholarship</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm inline-block">PhD Scholarship</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Straight PhD Program</a></li>
                    <li><a href="https://sei.dost.gov.ph/index.php/programs-and-projects/scholarships/career-incentive-program" target="_blank" class="text-gray-600 hover:text-blue-600 text-sm inline-block">Career Incentive Program</a></li>
                </ul>
            </div>
            
            <!-- Column 5: Contact Us -->
            <div class="text-center sm:text-left">
                <h3 class="font-semibold text-gray-800 mb-4">Contact Us</h3>
                <ul class="space-y-3">
                    <li class="flex items-start justify-center ">
                        <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:erdt@clsu.edu.ph" class="text-gray-600 hover:text-blue-600 text-sm">erdt@clsu.edu.ph</a>
                    </li>
                    <li class="flex items-start justify-center  ">
                        <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-gray-600 text-sm">0920-9312126</span>
                    </li>
                   
                </ul>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <p class="text-gray-600 text-sm">Copyright © {{ date('Y') }} CLSU-ERDT. All rights reserved.</p>
                </div>
                
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">
                        <span class="sr-only">Terms and Conditions</span>
                        <span>Terms and Conditions</span>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm">
                        <span class="sr-only">Privacy Policy</span>
                        <span>Privacy Policy</span>
                    </a>
                </div>
                
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600" aria-label="Facebook">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600" aria-label="Twitter">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600" aria-label="YouTube">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600" aria-label="LinkedIn">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
