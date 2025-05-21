@extends('layouts.app')

@section('title', 'Login - CLSU ERDT')

@push('styles')
<style>
      body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8fafc;
      }

      h1,
      h2,
      h3,
      .logo-text {
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

@endpush
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSU-ERDT Scholarship Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
  </head>
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
      <div class="flex flex-col md:flex-row gap-8 items-center">
        <!-- Left Side - Login Form -->
        <div class="w-full md:w-2/5 mb-4 md:mb-0 flex justify-center mt-3"> 
             <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg w-full max-w-md"> 
                 <div class="bg-white p-6 rounded-lg shadow-lg"> 
                     @if (session('status')) 
                         <div class="mb-4 p-3 bg-clsu-green text-white rounded-md text-sm"> 
                             {{ session('status') }} 
                         </div> 
                     @endif 
             <form x-data="{  
                         loading: false,  
                         email: '{{ old('email') }}',  
                         password: '',    
                         showPassword: false, 
                         remember: {{ old('remember') ? 'true' : 'false' }}, 
                         emailError: '', 
                         passwordError: '', 
                         focusedInput: null, 
                         validateEmail() { 
                             if (!this.email) { 
                                 this.emailError = 'Email is required'; 
                                 return false; 
                             } else if (!/^\S+@\S+\.\S+$/.test(this.email)) { 
                                 this.emailError = 'Invalid email format'; 
                                 return false; 
                             } 
                             this.emailError = ''; 
                             return true; 
                         }, 
                         validatePassword() { 
                             if (!this.password) { 
                                 this.passwordError = 'Password is required'; 
                                 return false; 
                             } 
                             this.passwordError = ''; 
                             return true; 
                         }, 
                         submit() { 
                             if (this.validateEmail() && this.validatePassword()) { 
                                 this.loading = true; 
                                 $el.submit(); 
                             } else { 
                                 // Shake animation on error 
                                 $el.classList.add('animate-shake'); 
                                 setTimeout(() => { 
                                     $el.classList.remove('animate-shake'); 
                                 }, 500); 
                             } 
                         } 
                     }" class="space-y-6" method="POST" action="{{ route('login') }}" @submit.prevent="submit"> 
                 @csrf 
  
                 <div class="text-center mb-6"> 
                     <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2> 
                     <p class="text-gray-600">Sign in to your CLSU-ERDT account</p> 
                 </div> 
  
                 <div class="mb-5"> 
                     <label for="email" class="block text-sm font-medium text-gray-700 mb-1"> 
                         Email address 
                     </label> 
                     <div class="mt-1 relative"> 
                         <input id="email" name="email" type="email" autocomplete="email" required 
                                x-model="email" 
                                @focus="focusedInput = 'email'" 
                                @blur="focusedInput = null; validateEmail()" 
                                class="w-full px-3 py-2 rounded-md border border-gray-300 focus:border-clsu-green focus:ring-2 focus:ring-green-200 transition duration-200 text-sm" 
                                :class="{  
                                    'border-red-500 bg-red-50': emailError || {{ $errors->has('email') ? 'true' : 'false' }},  
                                    'border-gray-300': !emailError && !{{ $errors->has('email') ? 'true' : 'false' }}, 
                                    'border-clsu-green bg-green-50': email && !emailError && !{{ $errors->has('email') ? 'true' : 'false' }} 
                                }" 
                                placeholder="Enter your email" 
                                autofocus> 
                     </div> 
                     <p x-show="emailError"  
                        x-text="emailError"  
                        x-transition:enter="transition ease-out duration-300" 
                        x-transition:enter-start="opacity-0 -translate-y-2" 
                        x-transition:enter-end="opacity-100 translate-y-0" 
                        class="mt-2 text-sm text-red-400"></p> 
                     @error('email') 
                         <p class="mt-2 text-sm text-red-400">{{ $message }}</p> 
                     @enderror 
                 </div> 
  
                 <div> 
                     <label for="password" class="block text-sm font-medium text-gray-700 mb-1"> 
                         Password 
                     </label> 
                     <div class="mt-1 relative"> 
                         <input id="password" name="password"  
                                :type="showPassword ? 'text' : 'password'"  
                                autocomplete="current-password" required 
                                x-model="password" 
                                @focus="focusedInput = 'password'" 
                                @blur="focusedInput = null; validatePassword()" 
                                class="w-full px-3 py-2 rounded-md border border-gray-300 focus:border-clsu-green focus:ring-2 focus:ring-green-200 transition duration-200 pr-10 text-sm" 
                                :class="{  
                                    'border-red-500 bg-red-50': passwordError || {{ $errors->has('password') ? 'true' : 'false' }},  
                                    'border-gray-300': !passwordError && !{{ $errors->has('password') ? 'true' : 'false' }}, 
                                    'border-clsu-green bg-green-50': password && !passwordError && !{{ $errors->has('password') ? 'true' : 'false' }} 
                                }" 
                                placeholder="Enter your password"> 
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"> 
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor"> 
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /> 
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /> 
                                </svg> 
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor"> 
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" /> 
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" /> 
                                </svg> 
                            </button> 
                        </div> 
                        <p x-show="passwordError"  
                           x-text="passwordError"  
                           x-transition:enter="transition ease-out duration-300" 
                           x-transition:enter-start="opacity-0 -translate-y-2" 
                           x-transition:enter-end="opacity-100 translate-y-0" 
                           class="mt-2 text-sm text-red-400"></p> 
                        @error('password') 
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p> 
                        @enderror 
                    </div> 
 
                    <div class="flex items-center justify-between mt-6"> 
                        <div class="flex items-center"> 
                            <input id="remember" name="remember" type="checkbox" 
                                   x-model="remember" 
                                   class="h-4 w-4 text-clsu-green focus:ring-green-200 border-gray-300 rounded"> 
                            <label for="remember" class="ml-2 block text-sm text-gray-700"> 
                                Remember me 
                            </label> 
                        </div> 
                        <div class="text-sm"> 
                            <a href="{{ route('password.request') }}" class="text-clsu-green hover:text-green-700 font-medium"> 
                                Forgot password? 
                            </a> 
                        </div> 
                    </div> 
 
                    <div class="mt-6"> 
                        <button type="submit" 
                                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-clsu-green hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200" 
                                :class="{'opacity-75 cursor-not-allowed': loading}" 
                                :disabled="loading"> 
                            <span x-show="!loading">Sign in</span> 
                            <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> 
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> 
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path> 
                            </svg> 
                            <span x-show="loading">Processing...</span> 
                        </button> 
                    </div> 
                </form> 
            </div> 
        <div class="mt-8">
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500">Important Links</span>
              </div>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-3">
              <a href="#" class="flex justify-center items-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg> Application Guide </a>
              <a href="#" class="flex justify-center items-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg> FAQ </a>
            </div>
          </div>
        </div>
        <!-- Right Side - Content -->
        <div class="w-full md:w-1/2 lg:w-3/5">
          <div class="engineering-gradient rounded-xl overflow-hidden shadow-xl">
            <div class="blueprint-pattern w-full h-full p-8 text-white">
              <h1 class="text-4xl font-bold mb-6">CLSU-ERDT Engineering Scholarship</h1>
              <p class="text-lg mb-8 opacity-90">Empowering future engineers through academic excellence and innovation.</p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-700 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                  </div>
                  <h3 class="text-xl font-semibold mb-2">Full Tuition Coverage</h3>
                  <p class="opacity-90">Complete financial support for your engineering education journey.</p>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-700 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                  </div>
                  <h3 class="text-xl font-semibold mb-2">Monthly Stipend</h3>
                  <p class="opacity-90">Regular financial assistance to support your living expenses.</p>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-700 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                  </div>
                  <h3 class="text-xl font-semibold mb-2">Research Opportunities</h3>
                  <p class="opacity-90">Access to cutting-edge research projects and mentorship.</p>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-700 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="8.5" cy="7" r="4"></circle>
                      <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                  </div>
                  <h3 class="text-xl font-semibold mb-2">Industry Connections</h3>
                  <p class="opacity-90">Network with leading engineering firms and professionals.</p>
                </div>
              </div>
              <div class="bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm border border-white border-opacity-20">
                <h3 class="text-xl font-semibold mb-3">Application Timeline</h3>
                <div class="space-y-3">
                  <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-300 mr-3"></div>
                    <p>
                      <span class="font-semibold">January 15:</span> Application Opens
                    </p>
                  </div>
                  <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-300 mr-3"></div>
                    <p>
                      <span class="font-semibold">March 30:</span> Submission Deadline
                    </p>
                  </div>
                  <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-300 mr-3"></div>
                    <p>
                      <span class="font-semibold">April 15-30:</span> Interview Process
                    </p>
                  </div>
                  <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-300 mr-3"></div>
                    <p>
                      <span class="font-semibold">May 15:</span> Results Announcement
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
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
@endpush