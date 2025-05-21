<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSU-ERDT Scholarship Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: {{ asset('storage/bg/bgloginscholar.png') }}
        }
        h1, h2, h3, .logo-text {
            font-family: 'Montserrat', sans-serif;
        }
        .engineering-gradient {
            background: linear-gradient(135deg, #0369a1, #0c4a6e);
        }
        .blueprint-pattern {
            background-color: #0c4a6e;
            background-image: radial-gradient(#164e63 1px, transparent 1px),
                              radial-gradient(#164e63 1px, transparent 1px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.8;
        }
    </style>    
</head>
<body style="background-image: url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover;">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-10 mr-3">
                    <span class="logo-text font-bold text-gray-800 text-xl ml-2" >CLSU-ERDT</span>
                </div>
                <!-- <div class="hidden md:block h-6 w-px bg-gray-300"></div> -->
                <!-- <span class="hidden md:block text-sm text-gray-600">Engineering Scholarship Management System</span> -->
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-gray-600 hover:text-blue-800 transition">Home</a>
                <a href="#" class="text-gray-600 hover:text-blue-800 transition">How to Apply</a>
                <a href="#" class="text-gray-600 hover:text-blue-800 transition">About</a>
                <a href="#" class="text-gray-600 hover:text-blue-800 transition">History</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4">
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">Home</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">How to Apply</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">About</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-100">History</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-4 mt-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6 items-center justify-center">
            <!-- Left Side - Login Form -->
            <div class="w-full md:w-2/5 lg:w-1/3 bg-white p-6 rounded-xl shadow-lg">
                <div class="mb-5 text-center">
                    <div class="flex justify-center mb-3">                 
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Scholar Login</h2>
                    <p class="text-sm text-gray-600">Access your CLSU-ERDT scholarship account</p>
                </div>
                
                <form id="login-form" class="space-y-4">
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email address">
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                            <a href="#" class="text-xs text-blue-700 hover:text-blue-900">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password">
                        <div class="text-red-500 text-xs mt-1 hidden" id="error-message"></div>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-3 w-3 text-blue-600 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-xs text-gray-700">Remember me</label>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-blue-800 text-white py-2 text-sm rounded-lg hover:bg-blue-900 transition font-medium" style="background-color: #800000;">Sign In</button>
                    </div>
                </form>
                
                <div class="mt-5">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500">Important Links</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="#" class="flex justify-center items-center py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Application Guide
                        </a>
                        <a href="#" class="flex justify-center items-center py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            FAQ
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Content -->
            <div class="w-full md:w-3/5 lg:w-2/3 mb-6 ml-12">
                <div class=" rounded-xl overflow-hidden shadow-xl">
                    <div class=" w-full h-full p-6 text-white" >
                        <h1 class="text-3xl font-bold mb-4">CLSU-ERDT Scholarship</h1>
                        <p class="text-base mb-6 opacity-90">Empowering future engineers through academic excellence and innovation.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div class="bg-white bg-opacity-50 p-5 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full  mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold mb-1">Full Tuition Coverage</h3>
                                <p class="opacity-90 text-sm">Complete financial support for your engineering education journey.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 p-5 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full  mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold mb-1">Monthly Stipend</h3>
                                <p class="opacity-90 text-sm">Regular financial assistance to support your Academic expenses.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 p-5 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full  mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold mb-1">Research Opportunities</h3>
                                <p class="opacity-90 text-sm">Access to cutting-edge research projects and mentorship.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 p-5 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full  mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold mb-1">Industry Connections</h3>
                                <p class="opacity-90 text-sm">Network with leading engineering firms and professionals.</p>
                            </div>
                        </div>                                          
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white text-black py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center mb-4">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-10 mr-3">
                        <span class="logo-text font-bold text-xl ml-2 text-black">CLSU-ERDT</span>
                    </div>
                    <p class="text-gray-600 max-w-xs">Engineering Research and Development for Technology Scholarship Program at CLSU.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Home</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">How to Apply</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">About</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">History</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-white transition">FAQ</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Requirements</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Downloads</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span>Science City of Muñoz, Nueva Ecija, Philippines</span>
                            </li>
                            <li class="flex items-center " >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span>(044) 456-0123</span>
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span>erdt@clsu.edu.ph</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; 2023 CLSU-ERDT Scholarship Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Form validation
        const loginForm = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const errorMessage = document.getElementById('error-message');
        
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Simple validation
            if (!emailInput.value || !passwordInput.value) {
                errorMessage.textContent = 'Please enter both email and password';
                errorMessage.classList.remove('hidden');
                return;
            }
            
            // Email format validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                errorMessage.textContent = 'Please enter a valid email address';
                errorMessage.classList.remove('hidden');
                return;
            }
            
            // This is a demo login - in a real app, you would send this to a server
            errorMessage.classList.add('hidden');
            alert('Login successful! Welcome to the CLSU-ERDT Scholarship Management System.');
            
            // Reset form
            loginForm.reset();
        });
    </script>
</body>
</html>