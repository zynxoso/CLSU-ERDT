@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="mb-4">
                    <svg class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold mb-2">Error {{ $statusCode ?? 'Unknown' }}</h1>

                <p class="text-lg mb-6">{{ $message ?? 'An unexpected error occurred.' }}</p>

                @if(isset($errorCode) && !app()->environment('production'))
                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm font-mono">Error Code: {{ $errorCode }}</p>
                    </div>
                @endif

                @if(isset($exception) && !app()->environment('production'))
                    <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-left overflow-auto">
                        <p class="text-sm font-mono">{{ get_class($exception) }}</p>
                        <p class="text-sm font-mono mt-2">{{ $exception->getMessage() }}</p>
                        @if(method_exists($exception, 'getFile'))
                            <p class="text-sm font-mono mt-2">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                        @endif
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ url('/') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
