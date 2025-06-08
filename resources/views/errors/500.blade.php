@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold text-error">500</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4">Server Error</h2>

            <p class="text-lg text-base-content mb-6">
                {{ $message ?? "We're experiencing some technical difficulties. Please try again later." }}
            </p>

            <div class="mt-8">
                <a href="{{ url()->previous() }}" class="btn btn-ghost mr-2">
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
@endsection
