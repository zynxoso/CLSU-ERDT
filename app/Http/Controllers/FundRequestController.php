<?php

namespace App\Http\Controllers;

use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;
use App\Notifications\FundRequestStatusChanged;

use App\Services\AuditService;
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

    public function __construct(
        AuditService $auditService,
        FundRequestServiceInterface $fundRequestService
    ) {
        $this->middleware('auth');
        $this->auditService = $auditService;
        $this->fundRequestService = $fundRequestService;
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

        $fundRequests = $query->with(['requestType', 'scholarProfile'])
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

        $fundRequests = $this->fundRequestService->getAdminFundRequests($request);

        return view('admin.fund-requests.index', compact('fundRequests'));
    }

    /**
     * Filter fund requests with AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxFilter(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = FundRequest::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by purpose if provided
        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', 'like', '%' . $request->purpose . '%');
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $date = $request->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        $fundRequests = $query->with(['scholarProfile.user'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        // Render the requests HTML
        $html = view('admin.fund-requests._requests_list', compact('fundRequests'))->render();

        // Render pagination links
        $pagination = $fundRequests->links()->toHtml();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
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

        $fundRequests = $this->fundRequestService->getScholarFundRequests($request, $scholarProfile->id);

        // Get statistics for summary cards
        $statistics = $this->fundRequestService->getScholarFundRequestStatistics($scholarProfile->id);
        $totalRequested = $statistics['totalRequested'];
        $totalApproved = $statistics['totalApproved'];
        $totalPending = $statistics['totalPending'];
        $totalRejected = $statistics['totalRejected'];

        return view('scholar.fund-requests.index', compact(
            'fundRequests',
            'totalRequested',
            'totalApproved',
            'totalPending',
            'totalRejected'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $requestTypes = RequestType::where('is_active', true)->get();

        if ($user->role === 'scholar') {
            // Get scholar profile
            $scholarProfile = $user->scholarProfile;
            if (!$scholarProfile) {
                return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
            }

            return view('scholar.fund-requests.create', compact('requestTypes'));
        }

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
            $profile->program
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
        $fundRequest = FundRequest::findOrFail($id);

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
            return redirect()->route('fund-requests.index')
                ->with('error', 'You are not authorized to edit this request');
        }

        // Only draft requests can be edited
        if ($fundRequest->status !== 'Draft') {
            return redirect()->route('fund-requests.show', $fundRequest->id)
                ->with('error', 'Only draft requests can be edited');
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

        // Update the fund request using the service
        $fundRequest = $this->fundRequestService->updateFundRequest($fundRequest, $validated);

        return redirect()->route('fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request updated successfully');
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

        // Only draft requests can be submitted
        if ($fundRequest->status !== 'Draft') {
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
        if (!in_array($fundRequest->status, ['Submitted', 'Under Review'])) {
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
            $validated['admin_notes'],
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
        if ($fundRequest->status !== 'Submitted') {
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
}
