@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 text-info animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="2" fill="#fff"/>
                    <path d="M24 16v8m0 4h.01" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
                    <path d="M16 32h16" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-info">503</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4">Service Unavailable</h2>

            <p class="text-lg text-base-content mb-6">
                {{ $message ?? "The service is temporarily unavailable due to maintenance or high load. Please try again later." }}
            </p>

            <div class="mb-6 p-4 bg-info-50 border border-info-300 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-tools text-info-600 mr-2"></i>
                    <div class="text-left">
                        <p class="text-info-700 font-semibold">Maintenance Mode</p>
                        <p class="text-info-600 text-sm">We're working to improve your experience. Thank you for your patience.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-2">
                <button onclick="location.reload()" class="btn btn-primary">
                    <i class="fas fa-refresh mr-2"></i>
                    Try Again
                </button>
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

<script>
// Auto-refresh every 60 seconds during maintenance
setInterval(function() {
    fetch(window.location.href, { method: 'HEAD' })
        .then(response => {
            if (response.ok) {
                location.reload();
            }
        })
        .catch(() => {
            // Service still unavailable, continue waiting
        });
}, 60000);
</script>
@endsection