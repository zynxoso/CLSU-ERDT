<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Base controller para sa lahat ng admin controllers
 * 
 * Ito ang parent class na ginagamit ng lahat ng admin controllers
 * Para hindi na paulit-ulit ang mga common functions sa bawat controller
 * Lahat ng admin controllers ay mag-extend dito para makuha ang mga shared methods
 */
abstract class BaseAdminController extends Controller
{
    /**
     * Ginagamit kapag may error sa admin operations
     * 
     * Kapag may mali sa pag-process ng admin actions (save, delete, update)
     * Ito ang tumatawag para ma-handle ang error nang maayos
     * Nag-log ng error details at nagbabalik ng user-friendly message
     */
    protected function handleException(Exception $e, string $action, ?string $redirectRoute = null)
    {
        // Nag-save ng error details sa log file para makita ng developers
        // Kasama dito ang error message, stack trace, user ID, at timestamp
        Log::error("Admin {$action} failed", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);

        // Kung development environment, ipapakita ang detailed error para sa debugging
        // Kung production environment, generic message lang para hindi makita ng users ang sensitive info
        $message = config('app.debug') 
            ? "Failed to {$action}: {$e->getMessage()}"
            : "An error occurred while trying to {$action}. Please try again.";

        // Kung may specific na page na dapat puntahan pagkatapos ng error, redirect doon
        // Kung walang specific route, babalik lang sa previous page na galing
        if ($redirectRoute) {
            return redirect()->route($redirectRoute)->with('error', $message);
        }

        return back()->with('error', $message);
    }

    /**
     * Para sa pag-validate ng file uploads
     * 
     * Ginagamit kapag may admin na nag-upload ng file (documents, images, etc.)
     * Nag-check kung valid ang file at sumusunod sa mga requirements
     * Pwedeng magdagdag ng additional rules depende sa pangangailangan
     */
    protected function validateFile(Request $request, string $field, array $rules = [])
    {
        // Default na rules na applicable sa lahat ng file uploads
        $defaultRules = [
            'required',        // Kailangan may file na na-upload
            'file',           // Dapat file talaga, hindi text o iba
            'max:10240'       // Maximum 10MB lang ang file size (10240 KB)
        ];

        // Pinagsasama ang default rules at yung additional rules na binigay
        // Halimbawa: kung image lang ang allowed, pwedeng magdagdag ng 'image' rule
        $rules = array_merge($defaultRules, $rules);
        
        // Ginagawa ang actual validation gamit ang Laravel validator
        // Kung hindi pumasa, automatic na magthrow ng validation error
        return $request->validate([
            $field => $rules
        ]);
    }

    /**
     * Mga standard na error messages para sa validation
     * 
     * Ginagamit para consistent ang mga error messages sa buong admin panel
     * Kapag may validation error, ito ang mga message na lalabas sa user
     * Pwedeng i-customize ang messages depende sa pangangailangan
     */
    protected function getValidationMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',                    // Kapag walang laman ang required field
            'string' => 'The :attribute must be a string.',                       // Kapag hindi text ang input
            'max' => 'The :attribute may not be greater than :max characters.',   // Kapag sobrang haba ang input
            'min' => 'The :attribute must be at least :min characters.',          // Kapag sobrang ikli ang input
            'email' => 'The :attribute must be a valid email address.',           // Kapag hindi valid email format
            'unique' => 'The :attribute has already been taken.',                 // Kapag may duplicate sa database
            'file' => 'The :attribute must be a file.',                           // Kapag hindi file ang na-upload
            'image' => 'The :attribute must be an image.',                        // Kapag hindi image ang na-upload
            'mimes' => 'The :attribute must be a file of type: :values.',         // Kapag hindi allowed ang file type
            'date' => 'The :attribute is not a valid date.',                      // Kapag hindi valid date format
            'after' => 'The :attribute must be a date after :date.',              // Kapag date ay mas maaga sa required
            'before' => 'The :attribute must be a date before :date.',            // Kapag date ay mas huli sa required
            'integer' => 'The :attribute must be an integer.',                    // Kapag hindi whole number
            'numeric' => 'The :attribute must be a number.',                      // Kapag hindi number
            'boolean' => 'The :attribute field must be true or false.',           // Kapag hindi true/false value
            'in' => 'The selected :attribute is invalid.'                         // Kapag hindi kasama sa allowed values
        ];
    }

    /**
     * Para sa success response
     * 
     * Ginagamit kapag successful ang admin operation (save, update, delete)
     * Nagdadala ng success message sa susunod na page
     * Pwedeng mag-redirect sa specific route o balik lang sa previous page
     */
    protected function successResponse(string $message, ?string $redirectRoute = null, array $data = [])
    {
        // Kung may specific route na pupuntahan, redirect doon kasama ang success message
        if ($redirectRoute) {
            return redirect()->route($redirectRoute)->with('success', $message);
        }

        // Kung walang specific route, balik lang sa previous page na may success message
        return back()->with('success', $message);
    }

    /**
     * Para sa error response
     * 
     * Ginagamit kapag may error sa admin operation
     * Nagdadala ng error message sa susunod na page
     * Pwedeng mag-redirect sa specific route o balik lang sa previous page
     */
    protected function errorResponse(string $message, ?string $redirectRoute = null)
    {
        // Kung may specific route na pupuntahan, redirect doon kasama ang error message
        if ($redirectRoute) {
            return redirect()->route($redirectRoute)->with('error', $message);
        }

        // Kung walang specific route, balik lang sa previous page na may error message
        return back()->with('error', $message);
    }

    /**
     * Check kung may permission ang user
     * 
     * Ginagamit para i-check kung pwedeng gawin ng current user ang isang action
     * Nagbabalik ng true kung may permission, false kung wala
     * Base ito sa role at permissions na naka-assign sa user
     */
    protected function checkPermission(string $permission): bool
    {
        return Auth::user()->can($permission);
    }

    /**
     * I-authorize ang action o mag-fail
     * 
     * Ginagamit kapag gusto nating siguruhing may permission ang user bago mag-proceed
     * Kung walang permission, automatic na mag-throw ng 403 Forbidden error
     * Mas convenient ito kaysa sa manual na pag-check ng permission
     */
    protected function authorizeAction(string $permission, string $action = 'perform this action')
    {
        // Kung walang permission ang user, i-stop ang execution at mag-show ng 403 error
        if (!$this->checkPermission($permission)) {
            abort(403, "You don't have permission to {$action}.");
        }
    }

    /**
     * Get paginated results with search and filters
     * 
     * Ginagamit sa mga listing pages na may search, filter, at pagination
     * Nag-aapply ng search terms, filters, sorting, at pagination sa query
     * Default na 15 items per page, pero pwedeng i-customize
     */
    protected function getPaginatedResults($query, Request $request, int $perPage = 15)
    {
        // I-apply ang search kung may search term na binigay sa request
        // Tinitignan kung may 'search' parameter at hindi empty
        if ($request->has('search') && !empty($request->search)) {
            $query = $this->applySearch($query, $request->search);
        }

        // I-apply ang mga filters kung may filters na binigay sa request
        // Tinitignan kung may 'filters' parameter at array siya
        if ($request->has('filters') && is_array($request->filters)) {
            $query = $this->applyFilters($query, $request->filters);
        }

        // I-apply ang sorting base sa request parameters
        // Default: sort by created_at descending (pinakabago una)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // I-paginate ang results base sa specified na items per page
        return $query->paginate($perPage);
    }

    /**
     * Apply search to query - dapat i-implement ng child classes
     * 
     * Ito ay placeholder method na dapat i-override ng mga specific admin controllers
     * Bawat controller ay may kanya-kanyang search logic depende sa data na hinahanap
     * Halimbawa: sa ScholarController, pwedeng mag-search sa name, email, student_id
     */
    protected function applySearch($query, string $search)
    {
        // Default implementation - walang ginagawa, babalik lang ang original query
        // Ang mga child classes ay mag-o-override nito para magkaroon ng actual search functionality
        return $query;
    }

    /**
     * Apply filters to query - dapat i-implement ng child classes
     * 
     * Ito ay placeholder method na dapat i-override ng mga specific admin controllers
     * Bawat controller ay may kanya-kanyang filter logic depende sa available filters
     * Halimbawa: filter by status, date range, category, etc.
     */
    protected function applyFilters($query, array $filters)
    {
        // Default implementation - walang ginagawa, babalik lang ang original query
        // Ang mga child classes ay mag-o-override nito para magkaroon ng actual filter functionality
        return $query;
    }

    /**
     * Get common view data
     * 
     * Nagbibigay ng common data na kailangan ng lahat ng admin views
     * Ginagamit para hindi na paulit-ulit ang pagkuha ng basic info sa bawat controller
     * Pwedeng i-extend ito ng mga child classes para magdagdag ng additional data
     */
    protected function getCommonViewData(): array
    {
        return [
            'user' => Auth::user(),        // Current logged-in user info
            'timestamp' => now(),          // Current timestamp para sa mga date displays
        ];
    }
}