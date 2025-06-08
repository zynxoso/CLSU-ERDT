@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold text-error">Error {{ $statusCode ?? 'Unknown' }}</h1>

            <p class="text-lg text-base-content mb-6">{{ $message ?? 'An unexpected error occurred.' }}</p>

            @if(isset($errorCode) && !app()->environment('production'))
                <div class="mb-4 p-4 bg-base-300 rounded-box text-base-content">
                    <p class="text-sm font-mono">Error Code: {{ $errorCode }}</p>
                </div>
            @endif

            @if(isset($exception) && !app()->environment('production'))
                <div class="mt-4 p-4 bg-base-300 rounded-box text-left overflow-auto text-base-content">
                    <p class="text-sm font-mono">{{ get_class($exception) }}</p>
                    <p class="text-sm font-mono mt-2">{{ $exception->getMessage() }}</p>
                    @if(method_exists($exception, 'getFile'))
                        <p class="text-sm font-mono mt-2">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                    @endif
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
