@extends('layouts.app')

@section('title', 'Login - CLSU-ERDT')

@section('content')
<div x-data="tabNavigation()" class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-600 flex flex-col">
    <!-- Main Content -->
    <div class="flex-grow flex flex-col md:flex-row p-4 md:p-8 container mx-auto">
        <!-- Login Form Section -->
        <div class="w-full md:w-4/5 md:mb-0 flex justify-center mt-7">
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg w-full max-w-md mt-6">
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

                        <!-- Password visibility toggle -->
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-500 focus:outline-none transition-colors duration-200"
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

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox"
                               x-model="remember"
                               class="h-4 w-4 text-clsu-green focus:ring-clsu-green border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}"
                           class="font-medium text-clsu-green hover:text-green-700 transition-colors duration-200">
                            Forgot password?
                        </a>
                    </div>
                    @endif
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-clsu-maroon hover:bg-maroon-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-clsu-green transition-colors duration-200"
                            :class="{ 'opacity-75 cursor-not-allowed': loading }">
                        <svg x-show="!loading"
                             class="h-5 w-5 mr-1"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span x-show="!loading">Sign in</span>
                        <svg x-show="loading"
                             class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="loading">Signing in...</span>
                    </button>
                </div>
            </form>
            </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-white/10 backdrop-blur-sm p-4 text-center text-white text-sm">
        <p>&copy; {{ date('Y') }} Central Luzon State University - Engineering Research and Development for Technology</p>
    </footer>
</div>
@endsection
