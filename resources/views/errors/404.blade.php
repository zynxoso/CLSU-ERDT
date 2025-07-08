@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 text-error animate-bounce" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="2" fill="#fff"/>
                    <path d="M16 20c0-4 8-4 8 0m0 0c0-4 8-4 8 0" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="18" r="2" fill="#ef4444"/>
                    <circle cx="30" cy="18" r="2" fill="#ef4444"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-error animate-fade-in">404</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4 animate-fade-in">Oops! Page Not Found</h2>

            <p class="text-lg text-base-content mb-6 animate-fade-in delay-100">
                {{ $message ?? "Sorry, we couldn't find the page you were looking for. It might have been moved, deleted, or never existed." }}
            </p>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-2 animate-fade-in delay-200">
                <a href="{{ url()->previous() }}" class="btn btn-ghost mr-0 sm:mr-2">
                    Go Back
                </a>
                <a href="{{ url('/') }}" class="btn btn-primary">
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
