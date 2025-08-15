<?php

namespace App\Http\Controllers;

use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;
use App\Notifications\FundRequestStatusChanged;

use App\Services\AuditService;
use App\Services\FundRequestValidationService;
use App\Services\Interfaces\FundRequestServiceInterface;
use App\Http\Requests\StoreFundRequestRequest;
use App\Http\Requests\UpdateFundRequestRequest;
use App\Http\Requests\RejectFundRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FundRequestController extends Controller
{
    protected $auditService;
    protected $fundRequestService;
    protected $validationService;

    public function __construct(
        AuditService $auditService,
        FundRequestServiceInterface $fundRequestService,
        FundRequestValidationService $validationService
    ) {
        $this->middleware('auth');
        $this->auditService = $auditService;
        $this->fundRequestService = $fundRequestService;
        $this->validationService = $validationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = FundRequest::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by request type if provided
        if ($request->has('type') && $request->type) {
            $query->where('request_type_id', $request->type);
        }

        // If scholar, only show their requests
        if ($user->role === 'scholar') {
            $profile = $user->scholarProfile;
            if (!$profile) {
                return redirect()->route('home')->with('error', 'Scholar profile not found');
            }
            $query->where('scholar_profile_id', $profile->id);
        }

        $fundRequests = $query->withBasicRelations()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $requestTypes = RequestType::where('is_active', true)->get();

        return view('fund-requests.index', compact('fundRequests', 'requestTypes'));
    }

    /**
     * Display a listing of fund requests for admin.
     */
    public function adminIndex(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Return the view with Livewire component
        return view('admin.fund-requests.index');
    }



    /**
     * Get documents for a fund request (AJAX)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocuments($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $fundRequest = FundRequest::findOrFail($id);

        // Load documents with their details
        $documents = $fundRequest->documents()->get();

        return response()->json([
            'success' => true,
            'documents' => $documents
        ]);
    }

    /**
     * Display a listing of fund requests for scholar.
     */
    public function scholarIndex(Request $request)
    {
        // Check if user is a scholar
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get scholar profile
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        // Return the view with Livewire component
        return view('scholar.fund-requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'scholar') {
            // Get scholar profile
            $scholarProfile = $user->scholarProfile;
            if (!$scholarProfile) {
                return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
            }
            
            // For scholars, only show requestable types (excludes stipends)
            $requestTypes = RequestType::getRequestableTypes();
            return view('scholar.fund-requests.create', compact('requestTypes'));
        }
        
        // For admin, show all active types
        $requestTypes = RequestType::getActiveTypes();
        return view('fund-requests.create', compact('requestTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFundRequestRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $profile = $user->scholarProfile;

        if (!$profile) {
            return redirect()->route('home')->with('error', 'Scholar profile not found');
        }

        // Validate amount against limits
        $validationError = $this->fundRequestService->validateFundRequestAmount(
            $validated['request_type_id'],
            $validated['amount'],
            $profile->id
        );

        if ($validationError) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validationError);
        }

        try {
            // Create the fund request using the service
            $fundRequest = $this->fundRequestService->createFundRequest($validated, $profile->id, $request);

            if ($user->role === 'scholar') {
                return redirect()->route('scholar.fund-requests.show', $fundRequest->id)
                    ->with('success', 'Fund request created successfully. Your request will be reviewed by an administrator.');
            }

            return redirect()->route('fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create fund request: ' . $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $fundRequest = FundRequest::withFullRelations()->findOrFail($id);

        // Check if user is authorized to view this request
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $fundRequest->scholar_profile_id) {
            if ($user->role === 'scholar') {
                return redirect()->route('scholar.fund-requests')
                    ->with('error', 'You are not authorized to view this request');
            } else {
                return redirect()->route('fund-requests.index')
                    ->with('error', 'You are not authorized to view this request');
            }
        }

        if ($user->role === 'scholar') {
            return view('scholar.fund-requests.show', compact('fundRequest'));
        } else {
            return view('admin.fund-requests.show', compact('fundRequest'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FundRequest $fundRequest)
    {
        $user = Auth::user();

        // Check if user is authorized to edit this request
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $fundRequest->scholar_profile_id) {
            return redirect()->route('scholar.fund-requests.index')
                ->with('error', 'You are not authorized to edit this request');
        }

        // Only submitted requests can be edited by scholars
        if ($fundRequest->status !== 'submitted') {
            if ($user->role === 'scholar') {
                return redirect()->route('scholar.fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only draft requests can be edited');
            } else {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only draft requests can be edited');
            }
        }

        $requestTypes = RequestType::where('is_active', true)->get();

        return view('fund-requests.edit', compact('fundRequest', 'requestTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFundRequestRequest $request, FundRequest $fundRequest)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Update the fund request using the service
        $fundRequest = $this->fundRequestService->updateFundRequest($fundRequest, $validated);

        if ($user->role === 'scholar') {
            return redirect()->route('scholar.fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request updated successfully');
        } else {
            return redirect()->route('fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request updated successfully');
        }
    }

    /**
     * Submit the fund request for review.
     */
    public function submit(FundRequest $fundRequest)
    {
        $user = Auth::user();

        // Check if user is authorized to submit this request
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $fundRequest->scholar_profile_id) {
            if ($user->role === 'scholar') {
                return redirect()->route('scholar.fund-requests')
                    ->with('error', 'You are not authorized to submit this request');
            } else {
                return redirect()->route('fund-requests.index')
                    ->with('error', 'You are not authorized to submit this request');
            }
        }

        // Only submitted requests can be resubmitted
        if ($fundRequest->status !== 'submitted') {
            if ($user->role === 'scholar') {
                return redirect()->route('scholar.fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only draft requests can be submitted');
            } else {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only draft requests can be submitted');
            }
        }

        // Submit the fund request using the service
        $fundRequest = $this->fundRequestService->submitFundRequest($fundRequest);

        if ($user->role === 'scholar') {
            return redirect()->route('scholar.fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request submitted successfully');
        } else {
            return redirect()->route('fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request submitted successfully');
        }
    }

    /**
     * Approve a fund request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $fundRequest = FundRequest::findOrFail($id);

        // Only submitted or under review requests can be approved
        if (!in_array($fundRequest->status, [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])) {
            return redirect()->route('admin.fund-requests.show', $fundRequest->id)
                ->with('error', 'Only submitted or under review requests can be approved');
        }

        // Approve the fund request using the service
        $fundRequest = $this->fundRequestService->approveFundRequest($fundRequest, Auth::id());

        return redirect()->route('admin.fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request approved successfully');
    }

    /**
     * Reject a fund request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(RejectFundRequestRequest $request, $id)
    {
        $fundRequest = FundRequest::findOrFail($id);
        $validated = $request->validated();

        // Reject the fund request using the service
        $fundRequest = $this->fundRequestService->rejectFundRequest(
            $fundRequest,
            $validated['rejection_reason'],
            Auth::id()
        );

        return redirect()->route('admin.fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request rejected successfully');
    }

    /**
     * Mark a fund request as under review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsUnderReview($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $fundRequest = FundRequest::findOrFail($id);

        // Only submitted requests can be marked as under review
        if ($fundRequest->status !== FundRequest::STATUS_SUBMITTED) {
            return redirect()->route('admin.fund-requests.show', $fundRequest->id)
                ->with('error', 'Only submitted requests can be marked as under review');
        }

        // Mark the fund request as under review using the service
        $fundRequest = $this->fundRequestService->markFundRequestAsUnderReview($fundRequest);

        return redirect()->route('admin.fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request marked as under review successfully');
    }

    /**
     * Get status updates for fund requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusUpdates(Request $request)
    {
        // Check if user is a scholar
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'integer|exists:fund_requests,id',
        ]);

        // Get scholar profile
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json(['error' => 'Scholar profile not found'], 404);
        }

        // Get status updates using the service
        $updates = $this->fundRequestService->getFundRequestStatusUpdates(
            $validated['request_ids'],
            $scholarProfile->id
        );

        return response()->json([
            'success' => true,
            'updates' => $updates,
        ]);
    }

    /**
     * Get existing request types for duplicate validation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExistingRequestTypes()
    {
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json(['error' => 'Scholar profile not found'], 404);
        }

        // Get active request types for this scholar
        $activeRequestTypes = FundRequest::withBasicRelations()
            ->where('scholar_profile_id', $scholarProfile->id)
            ->whereIn('status', [
                FundRequest::STATUS_SUBMITTED,
                FundRequest::STATUS_UNDER_REVIEW,
                FundRequest::STATUS_APPROVED
            ])
            ->pluck('request_type_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'activeTypes' => $activeRequestTypes,
        ]);
    }

    /**
     * Validate amount against request type limits
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateAmount(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json(['error' => 'Scholar profile not found'], 404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'request_type_id' => 'required|exists:request_types,id'
        ]);

        // Use the service to validate amount
        $validationError = $this->fundRequestService->validateFundRequestAmount(
            $validated['request_type_id'],
            $validated['amount'],
            $scholarProfile->intended_degree
        );

        if ($validationError) {
            return response()->json([
                'success' => false,
                'error' => $validationError['amount'] ?? 'Amount validation failed'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Amount is valid'
        ]);
    }

    /**
     * Pre-validate fund request before submission
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function preValidate(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return response()->json([
                'valid' => false,
                'message' => 'Unauthorized access. Only scholars can validate fund requests.'
            ], 403);
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json([
                'valid' => false,
                'message' => 'Scholar profile not found. Please complete your profile setup first.'
            ], 404);
        }

        try {
            // Use comprehensive validation service
            $validationResult = $this->validationService->validateFundRequestCreation(
                $request->all(),
                $scholarProfile->id
            );

            if (!$validationResult['valid']) {
                return response()->json([
                    'valid' => false,
                    'message' => $this->validationService->formatErrorMessages($validationResult['errors']),
                    'errors' => $validationResult['errors']
                ]);
            }

            $responseData = [
                'valid' => true,
                'message' => 'Request validation passed successfully.'
            ];

            // Include warnings if any
            if (!empty($validationResult['warnings'])) {
                $responseData['warnings'] = $validationResult['warnings'];
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation failed due to an unexpected error. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Check for duplicate requests (admin endpoint)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDuplicates(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $validated = $request->validate([
            'scholar_profile_id' => 'required|exists:scholar_profiles,id',
            'request_type_id' => 'required|exists:request_types,id'
        ]);

        // Check for active duplicate requests
        $duplicateCheck = $this->fundRequestService->checkForActiveDuplicateRequest(
            $validated['scholar_profile_id'],
            $validated['request_type_id']
        );

        return response()->json([
            'success' => true,
            'hasDuplicate' => $duplicateCheck !== null,
            'message' => $duplicateCheck ? $duplicateCheck['message'] : 'No duplicates found'
        ]);
    }

    /**
     * Pre-validate admin fund request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminPreValidate(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'valid' => false,
                'message' => 'Unauthorized access. Only administrators can validate admin requests.'
            ], 403);
        }

        try {
            // Get scholar profile ID from request
            $scholarProfileId = $request->input('scholar_profile_id');
            if (!$scholarProfileId) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Scholar profile ID is required for admin requests.'
                ]);
            }

            // Use comprehensive validation service
            $validationResult = $this->validationService->validateFundRequestCreation(
                $request->all(),
                $scholarProfileId
            );

            if (!$validationResult['valid']) {
                return response()->json([
                    'valid' => false,
                    'message' => $this->validationService->formatErrorMessages($validationResult['errors']),
                    'errors' => $validationResult['errors']
                ]);
            }

            $responseData = [
                'valid' => true,
                'message' => 'Admin request validation passed successfully.'
            ];

            // Include warnings if any
            if (!empty($validationResult['warnings'])) {
                $responseData['warnings'] = $validationResult['warnings'];
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation failed due to an unexpected error. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show the form for editing a fund request (Admin).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminEdit($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $fundRequest = FundRequest::with(['requestType', 'scholarProfile', 'documents'])->findOrFail($id);

        // Admins can edit requests in Submitted or Under Review status
        if (!in_array($fundRequest->status, [
            'submitted',
            'under_review'
        ])) {
            return redirect()->route('admin.fund-requests.show', $fundRequest->id)
                ->with('error', 'Only draft, submitted, or under review requests can be edited');
        }

        $requestTypes = RequestType::where('is_active', true)->get();
        $scholars = ScholarProfile::with('user')->get();

        return view('admin.fund-requests.edit', compact('fundRequest', 'requestTypes', 'scholars'));
    }

    /**
     * Update a fund request (Admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $fundRequest = FundRequest::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'scholar_profile_id' => 'required|exists:scholar_profiles,id',
            'request_type_id' => 'required|exists:request_types,id',
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:1000',
            'justification' => 'nullable|string|max:2000',
            'expected_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Update the fund request
            $fundRequest->update([
                'scholar_profile_id' => $validated['scholar_profile_id'],
                'request_type_id' => $validated['request_type_id'],
                'amount' => $validated['amount'],
                'purpose' => $validated['purpose'],
                'justification' => $validated['justification'] ?? null,
                'expected_date' => $validated['expected_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'updated_at' => now()
            ]);

            // Log the update
        $this->auditService->log(
            'fund_request_updated',
            'FundRequest',
            $fundRequest->id,
            'Fund request updated by admin',
            [
                'updated_by' => Auth::id(),
                'updated_fields' => array_keys($validated),
                'previous_values' => $fundRequest->getOriginal()
            ]
        );

            DB::commit();

            return redirect()->route('admin.fund-requests.show', $fundRequest->id)
                ->with('success', 'Fund request updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update fund request: ' . $e->getMessage())
                ->withInput();
        }
    }
}
