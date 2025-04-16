@extends('layouts.app')

@section('title', 'Login - CLSU-ERDT')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-900 px-4 py-12 sm:px-6 lg:px-8">
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" class="w-full max-w-md space-y-8">
        <div 
            class="text-center transform transition-all duration-500 ease-out" 
            :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-8'"
        >
            <h2 class="mt-6 text-3xl font-bold tracking-tight text-white">
                Sign in to your account
            </h2>
            @if (session('status'))
                <div class="mt-2 text-sm text-blue-400">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <div 
            class="mt-8 bg-gray-800 px-6 py-8 shadow-lg rounded-lg transform transition-all duration-500 ease-out" 
            :class="show ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-12 scale-95'"
        >
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

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 transition-all duration-200" :class="{'text-blue-400': focusedInput === 'email'}">
                        Email address
                    </label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               x-model="email"
                               @focus="focusedInput = 'email'"
                               @blur="focusedInput = null; validateEmail()"
                               class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-blue-500 text-white sm:text-sm transition-all duration-200 ease-in-out"
                               :class="{ 
                                   'border-red-500 bg-red-900/20': emailError || {{ $errors->has('email') ? 'true' : 'false' }}, 
                                   'border-gray-700 bg-gray-700 focus:border-blue-500': !emailError && !{{ $errors->has('email') ? 'true' : 'false' }},
                                   'border-green-500 bg-green-900/20': email && !emailError && !{{ $errors->has('email') ? 'true' : 'false' }},
                                   'transform scale-105': focusedInput === 'email'
                               }"
                               placeholder="you@example.com"
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
                    <label for="password" class="block text-sm font-medium text-gray-300 transition-all duration-200" :class="{'text-blue-400': focusedInput === 'password'}">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" 
                               :type="showPassword ? 'text' : 'password'" 
                               autocomplete="current-password" required
                               x-model="password"
                               @focus="focusedInput = 'password'"
                               @blur="focusedInput = null; validatePassword()"
                               class="block w-full appearance-none rounded-md border pl-3 pr-10 py-2 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-blue-500 text-white sm:text-sm transition-all duration-200 ease-in-out"
                               :class="{ 
                                   'border-red-500 bg-red-900/20': passwordError || {{ $errors->has('password') ? 'true' : 'false' }}, 
                                   'border-gray-700 bg-gray-700 focus:border-blue-500': !passwordError && !{{ $errors->has('password') ? 'true' : 'false' }},
                                   'border-green-500 bg-green-900/20': password && !passwordError && !{{ $errors->has('password') ? 'true' : 'false' }},
                                   'transform scale-105': focusedInput === 'password'
                               }">
                        
                        <!-- Password visibility toggle -->
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 mr-2 text-gray-400 hover:text-gray-300 focus:outline-none transition-colors duration-200"
                                tabindex="-1">
                            <svg x-show="!showPassword" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 rotate-90"
                                 x-transition:enter-end="opacity-100 rotate-0"
                                 xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            <svg x-show="showPassword" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 rotate-90"
                                 x-transition:enter-end="opacity-100 rotate-0"
                                 xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                   x-model="remember"
                                   class="opacity-0 absolute h-4 w-4 cursor-pointer"
                                   aria-labelledby="remember-label">
                            <div class="bg-gray-700 border-2 rounded border-gray-600 w-4 h-4 flex flex-shrink-0 justify-center items-center mr-2 transition-colors duration-200"
                                 :class="{'bg-blue-600 border-blue-500': remember}">
                                <svg class="fill-current w-2 h-2 text-white pointer-events-none transition-transform duration-200 ease-in-out"
                                     :class="{'opacity-100 scale-100': remember, 'opacity-0 scale-0': !remember}"
                                     viewBox="0 0 20 20">
                                    <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/>
                                </svg>
                            </div>
                            <label id="remember-label" for="remember" class="text-sm text-gray-300 select-none cursor-pointer">
                                Remember me
                            </label>
                        </div>
                    </div>

                    @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" 
                           class="font-medium text-blue-400 hover:text-blue-300 transition-all duration-200 hover:scale-105 inline-block transform">
                            Forgot your password?
                        </a>
                    </div>
                    @endif
                </div>

                <div>
                    <button type="submit" 
                            class="group relative flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out transform hover:scale-[1.02] active:scale-95"
                            :class="{ 'opacity-75 cursor-not-allowed': loading }">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 transition-all duration-200 transform"
                              :class="{'group-hover:scale-110': !loading}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 group-hover:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span x-show="!loading"
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100">Sign in</span>
                        <span x-show="loading" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- <div class="mt-6 text-center">
            <p class="text-sm text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-blue-400 hover:text-blue-300">
                    Register now
                </a>
            </p>
        </div> --}}
    </div>
</div>
@endsection

@section('styles')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
    }
    
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    
    /* Checkbox focus ring */
    input[type="checkbox"]:focus + div {
        @apply ring-2 ring-blue-500 ring-offset-1 ring-offset-gray-800;
    }
</style>
@endsection
 