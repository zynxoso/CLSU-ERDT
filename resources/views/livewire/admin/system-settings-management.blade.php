<div class="container mx-auto ">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        <div class="flex space-x-3">
            <button wire:click="clearCache"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Clear Cache
            </button>
            <a href="{{ route('super_admin.dashboard') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if($successMessage)
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $successMessage }}</span>
            <button wire:click="$set('successMessage', '')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', '')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="setActiveTab('general')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                </svg>
                General Settings
            </button>
            <button wire:click="setActiveTab('security')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'security' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Security Settings
            </button>
            <button wire:click="setActiveTab('password')"
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'password' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Change Password
            </button>
        </nav>
    </div>

    <!-- General Settings Tab -->
    @if($activeTab === 'general')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                    </svg>
                    General Settings
                </h2>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveGeneralSettings" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name *</label>
                            <input type="text" id="site_name" wire:model.defer="site_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description *</label>
                        <textarea id="site_description" wire:model.defer="site_description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('site_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="saveGeneralSettings"
                                class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium py-2 px-6 rounded text-sm inline-flex items-center">
                            <span wire:loading.remove wire:target="saveGeneralSettings">Save General Settings</span>
                            <span wire:loading wire:target="saveGeneralSettings" class="flex items-center">
                                <svg class="-ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Security Settings Tab -->
    @if($activeTab === 'security')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Security Settings
                </h2>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveSecuritySettings" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 mb-1">Password Expiry (days) *</label>
                            <input type="number" id="password_expiry_days" wire:model.defer="password_expiry_days" required min="1" max="365"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Range: 1-365 days</p>
                            @error('password_expiry_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-1">Max Login Attempts *</label>
                            <input type="number" id="max_login_attempts" wire:model.defer="max_login_attempts" required min="1" max="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Range: 1-20 attempts</p>
                            @error('max_login_attempts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="saveSecuritySettings"
                                class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium py-2 px-6 rounded text-sm inline-flex items-center">
                            <span wire:loading.remove wire:target="saveSecuritySettings">Save Security Settings</span>
                            <span wire:loading wire:target="saveSecuritySettings" class="flex items-center">
                                <svg class="-ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Change Password Tab -->
    @if($activeTab === 'password')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z" />
                    </svg>
                    Change Password
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update your account password for enhanced security</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="changePassword" class="space-y-4">
                    <div class="max-w-md">
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                            <input type="password" id="current_password" wire:model.defer="current_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Enter your current password">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                            <input type="password" id="new_password" wire:model.defer="new_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Enter your new password">
                            <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                            <input type="password" id="new_password_confirmation" wire:model.defer="new_password_confirmation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Confirm your new password">
                            @error('new_password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-start">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="changePassword"
                                    class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium py-2 px-6 rounded text-sm inline-flex items-center">
                                <span wire:loading.remove wire:target="changePassword">Change Password</span>
                                <span wire:loading wire:target="changePassword" class="flex items-center">
                                    <svg class="-ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Changing Password...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Password Security Tips -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Password Security Tips
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Use at least 8 characters with a mix of letters, numbers, and symbols</li>
                        <li>• Avoid using personal information or common words</li>
                        <li>• Don't reuse passwords from other accounts</li>
                        <li>• Consider using a password manager for better security</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
