<?php

namespace App\Http\Controllers;

use App\Traits\AlertsMessages;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Base controller na ginagamit ng lahat ng controllers sa system
// Nagbibigay ng common functionality para sa lahat ng controllers
class Controller extends BaseController
{
    // Ginagamit ang mga traits para sa:
    // - AuthorizesRequests: Para sa pag-check ng permissions
    // - ValidatesRequests: Para sa pag-validate ng user input
    // - AlertsMessages: Para sa pag-display ng success/error messages
    use AuthorizesRequests, ValidatesRequests, AlertsMessages;
}
