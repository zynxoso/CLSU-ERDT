@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="mb-4">
                    <svg class="h-24 w-24 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h1 class="text-4xl font-bold mb-2">500</h1>
                <h2 class="text-2xl font-semibold mb-4">Server Error</h2>

                <p class="text-lg mb-6">
                    {{ $message ?? "We're experiencing some technical difficulties. Please try again later." }}
                </p>

                <div class="mt-8">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 mr-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Go Back
                    </a>
                    <a href="{{ url('/') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Return to Home
                    </a>
                </div>

                @if(isset($exception) && !app()->environment('production'))
                    <div class="mt-8 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-left overflow-auto">
                        <p class="text-sm font-mono">{{ get_class($exception) }}</p>
                        <p class="text-sm font-mono mt-2">{{ $exception->getMessage() }}</p>
                        @if(method_exists($exception, 'getFile'))
                            <p class="text-sm font-mono mt-2">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                        @endif

                        @if(method_exists($exception, 'getTrace') && !app()->environment('production'))
                            <div class="mt-4">
                                <p class="text-sm font-semibold">Stack Trace:</p>
                                <pre class="text-xs mt-2 overflow-auto max-h-64">{{ json_encode($exception->getTrace(), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
