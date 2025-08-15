@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 text-warning animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="2" fill="#fff"/>
                    <path d="M24 8v8m0 8v8" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="24" cy="36" r="2" fill="#f59e0b"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-warning">401</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4">Authentication Required</h2>

            <p class="text-lg text-base-content mb-6">
                {{ $message ?? "You need to be logged in to access this resource. Please sign in to continue." }}
            </p>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
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