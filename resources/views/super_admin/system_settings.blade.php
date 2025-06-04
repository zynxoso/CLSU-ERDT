@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">General Settings</h2>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                        <input type="text" id="site_name" name="site_name" value="CLSU-ERDT Scholar Management" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                        <textarea id="site_description" name="site_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">CLSU-ERDT Scholar Management System for Agricultural and Biosystems Engineering</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="maintenance_mode" class="flex items-center">
                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Maintenance Mode</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm">
                        Save General Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Email Settings</h2>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">Mail Driver</label>
                        <select id="mail_driver" name="mail_driver" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="smtp">SMTP</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="mailgun">Mailgun</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                        <input type="text" id="mail_host" name="mail_host" value="smtp.mailtrap.io" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">Mail Port</label>
                        <input type="text" id="mail_port" name="mail_port" value="2525" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">Mail Username</label>
                        <input type="text" id="mail_username" name="mail_username" value="" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">Mail Password</label>
                        <input type="password" id="mail_password" name="mail_password" value="" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm">
                        Save Email Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Security Settings</h2>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-1">Session Lifetime (minutes)</label>
                        <input type="number" id="session_lifetime" name="session_lifetime" value="120" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="password_expiry" class="block text-sm font-medium text-gray-700 mb-1">Password Expiry (days)</label>
                        <input type="number" id="password_expiry" name="password_expiry" value="90" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="login_attempts" class="block text-sm font-medium text-gray-700 mb-1">Max Login Attempts</label>
                        <input type="number" id="login_attempts" name="login_attempts" value="5" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="two_factor_auth" class="flex items-center">
                            <input type="checkbox" id="two_factor_auth" name="two_factor_auth" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Enable Two-Factor Authentication</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm">
                        Save Security Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
