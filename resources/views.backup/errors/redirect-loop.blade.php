@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold text-error">Redirect Loop Detected</h1>

            <div class="alert alert-warning mt-4">
                <strong>Warning:</strong> The application detected a redirect loop and has stopped it to prevent your browser from crashing.
            </div>

            <p class="text-lg text-base-content mb-6">
                The system has detected {{ $count }} redirects within {{ $timeWindow }} seconds at the following URL:
            </p>

            <pre class="bg-base-300 p-4 rounded-box overflow-auto text-base-content">{{ $url }}</pre>

            <div class="details mt-8 p-4 bg-base-100 rounded-box text-left text-base-content">
                <h3 class="text-xl font-semibold mb-2">Possible Causes:</h3>
                <ul class="list-disc list-inside">
                    <li>Session issues or authentication problems</li>
                    <li>Middleware conflicts</li>
                    <li>Browser cookies problems</li>
                    <li>Route configuration issues</li>
                </ul>

                <h3 class="text-xl font-semibold mt-4 mb-2">Suggested Solutions:</h3>
                <ul class="list-disc list-inside">
                    <li>Clear your browser cookies and cache</li>
                    <li>Try logging out and logging back in</li>
                    <li>Contact the site administrator if the problem persists</li>
                </ul>
            </div>

            <div class="actions mt-8">
                <a href="{{ url('/') }}" class="btn btn-primary mr-2">Go to Home Page</a>
                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="btn btn-secondary">Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
