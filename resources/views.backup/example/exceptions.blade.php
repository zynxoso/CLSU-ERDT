@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h1 class="text-2xl font-bold mb-6">Exception Handling Examples</h1>

            <p class="mb-4">
                This page demonstrates the custom exception handling capabilities of the application.
                Click on any of the buttons below to trigger different types of exceptions.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Bad Request (400)</h3>
                    <p class="text-sm mb-4">Demonstrates handling invalid request data</p>
                    <a href="{{ route('example.bad-request') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>

                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Validation Error (422)</h3>
                    <p class="text-sm mb-4">Demonstrates handling form validation errors</p>
                    <a href="{{ route('example.validation-error') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>

                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Unauthorized (401)</h3>
                    <p class="text-sm mb-4">Demonstrates handling authentication errors</p>
                    <a href="{{ route('example.unauthorized') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>

                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Forbidden (403)</h3>
                    <p class="text-sm mb-4">Demonstrates handling permission errors</p>
                    <a href="{{ route('example.forbidden') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>

                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Not Found (404)</h3>
                    <p class="text-sm mb-4">Demonstrates handling resource not found errors</p>
                    <a href="{{ route('example.not-found') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>

                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Server Error (500)</h3>
                    <p class="text-sm mb-4">Demonstrates handling internal server errors</p>
                    <a href="{{ route('example.server-error') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Trigger Exception
                    </a>
                </div>
            </div>

            <div class="mt-12">
                <h2 class="text-xl font-bold mb-4">API Exception Examples</h2>

                <p class="mb-4">
                    To test API exceptions, use the following endpoints with a tool like Postman or curl:
                </p>

                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg overflow-auto">
                    <pre class="text-sm">
# Bad Request Example
GET {{ url('/api/example/bad-request') }}

# Validation Error Example
GET {{ url('/api/example/validation-error') }}

# Unauthorized Example
GET {{ url('/api/example/unauthorized') }}

# Forbidden Example
GET {{ url('/api/example/forbidden') }}

# Not Found Example
GET {{ url('/api/example/not-found') }}

# Server Error Example
GET {{ url('/api/example/server-error') }}

# Try-Catch Example (requires user_id parameter)
GET {{ url('/api/example/try-catch?user_id=123') }}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
