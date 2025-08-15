@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 text-error animate-bounce" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="2" fill="#fff"/>
                    <path d="M16 16l16 16m0-16L16 32" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-error">422</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4">Validation Error</h2>

            <p class="text-lg text-base-content mb-6">
                {{ $message ?? "The data you submitted contains validation errors. Please check your input and try again." }}
            </p>

            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-error-50 border border-error-300 rounded-lg text-left">
                    <h3 class="text-lg font-semibold text-error-600 mb-2">Validation Errors:</h3>
                    <ul class="list-disc list-inside text-error-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Go Back
                </a>
                <a href="{{ url('/') }}" class="btn btn-ghost">
                    Return to Home
                </a>
            </div>

            @if(isset($exception) && !app()->environment('production'))
                <div class="mt-8 p-4 bg-base-300 rounded-box text-left overflow-auto text-base-content">
                    <p class="text-sm font-mono">{{ get_class($exception) }}</p>
                    <p class="text-sm font-mono mt-2">{{ $exception->getMessage() }}</p>
                    @if(method_exists($exception, 'getFile'))
                        <p class="text-sm font-mono mt-2">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection