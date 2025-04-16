<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Only debug POST requests
        if ($request->isMethod('post')) {
            $logPath = storage_path('logs/csrf_debug.log');
            $logMessage = "==== CSRF Check at " . now() . " ====\n";
            $logMessage .= "URL: " . $request->fullUrl() . "\n";

            // Check if token exists in request
            $hasToken = $request->has('_token');
            $tokenValue = $request->input('_token', 'NO TOKEN PRESENT');
            $logMessage .= "Has Token: " . ($hasToken ? 'Yes' : 'No') . "\n";
            $logMessage .= "Token Value: " . $tokenValue . "\n";

            // Check if the token is valid
            $isValid = $this->tokensMatch($request);
            $logMessage .= "Token Valid: " . ($isValid ? 'Yes' : 'No') . "\n";

            // Additional request details
            $logMessage .= "Form Data: " . json_encode($request->except(['password', '_token']), JSON_PRETTY_PRINT) . "\n";

            file_put_contents($logPath, $logMessage . "\n", FILE_APPEND);
        }

        return parent::handle($request, $next);
    }
}
