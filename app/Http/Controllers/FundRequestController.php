<?php

namespace App\Http\Controllers;

use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;

use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FundRequestController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->auditService = $auditService;
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

        $query = FundRequest::where('scholar_profile_id', $scholarProfile->id);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by purpose if provided
        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $date = $request->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        $fundRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate totals for summary cards
        $totalRequested = $query->sum('amount');
        $totalApproved = clone $query;
        $totalApproved = $totalApproved->where('status', 'Approved')->sum('amount');
        $totalPending = clone $query;
        $totalPending = $totalPending->where('status', 'Pending')->sum('amount');
        $totalRejected = clone $query;
        $totalRejected = $totalRejected->where('status', 'Rejected')->sum('amount');

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'amount' => 'required|numeric|min:0',
            'admin_remarks' => 'nullable|string',
            'status' => 'sometimes|in:Draft,Submitted',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $user = Auth::user();
        $profile = $user->scholarProfile;

        if (!$profile) {
            return redirect()->route('home')->with('error', 'Scholar profile not found');
        }
        
        // Validate amount against limits based on request type and program level
        $requestTypeId = $validated['request_type_id'];
        $amount = $validated['amount'];
        $isDoctoralProgram = stripos($profile->program, 'doctoral') !== false || stripos($profile->program, 'phd') !== false;
        
        // Get the request type name
        $requestType = RequestType::find($requestTypeId);
        if (!$requestType) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['request_type_id' => 'Invalid request type.']);
        }
        
        // Define amount limits based on the entitlements table
        $requestTypeLimits = [
            1 => [ // Tuition Fee
                'name' => 'Tuition Fee',
                'masters' => null, // Actual as billed
                'doctoral' => null, // Actual as billed
            ],
            2 => [ // Living Allowance/Stipend
                'name' => 'Living Allowance/Stipend',
                'masters' => 30000,
                'doctoral' => 38000,
            ],
            3 => [ // Learning Materials
                'name' => 'Learning Materials',
                'masters' => 20000,
                'doctoral' => 20000,
            ],
            4 => [ // Thesis/Dissertation Grant
                'name' => 'Thesis/Dissertation Grant',
                'masters' => 60000,
                'doctoral' => 100000,
            ],
            5 => [ // Research Grant
                'name' => 'Research Grant',
                'masters' => 225000,
                'doctoral' => 475000,
            ],
            6 => [ // Research Dissemination
                'name' => 'Research Dissemination',
                'masters' => 75000,
                'doctoral' => 150000,
            ],
            7 => [ // Mentor's Fee
                'name' => "Mentor's Fee",
                'masters' => 36000,
                'doctoral' => 72000,
            ],
        ];
        
        // Check if the request type has a limit
        if (isset($requestTypeLimits[$requestTypeId])) {
            $programType = $isDoctoralProgram ? 'doctoral' : 'masters';
            $limit = $requestTypeLimits[$requestTypeId][$programType];
            $typeName = $requestTypeLimits[$requestTypeId]['name'];
            
            // If there's a limit (not null) and amount exceeds it
            if ($limit !== null && $amount > $limit) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['amount' => "The maximum amount allowed for {$typeName} is ₱" . number_format($limit, 2) . ".\nYour {$programType} program has a limit of ₱" . number_format($limit, 2) . "."]);
            }
        }
        
        try {
            // Create the fund request
            $fundRequest = new FundRequest();
            $fundRequest->scholar_profile_id = $profile->id;
            $fundRequest->request_type_id = $validated['request_type_id'];
            $fundRequest->amount = $validated['amount'];
            $fundRequest->purpose = $requestType->name; // Use the request type name as the purpose
            $fundRequest->admin_remarks = $validated['admin_remarks'] ?? null;
            $fundRequest->status = $validated['status'] ?? 'Draft';
            $fundRequest->save();
            
            // Handle document upload
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->store('documents/fund-requests/' . $fundRequest->id, 'public');
                
                // Create document record
                $document = new \App\Models\Document();
                $document->scholar_profile_id = $profile->id;
                $document->fund_request_id = $fundRequest->id;
                $document->file_name = $fileName;
                $document->file_path = $filePath;
                $document->file_type = $file->getClientMimeType();
                $document->file_size = $file->getSize();
                $document->category = 'Fund Request';
                $document->title = 'Fund Request Document - ' . $fundRequest->purpose;
                $document->save();
            }
            
            $this->auditService->logCreate('FundRequest', $fundRequest->id, $fundRequest->toArray());
            
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
    public function update(Request $request, FundRequest $fundRequest)
    {
        $user = Auth::user();

        // Check if user is authorized to update this request
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $fundRequest->scholar_profile_id) {
            return redirect()->route('fund-requests.index')
                ->with('error', 'You are not authorized to update this request');
        }

        // Only draft requests can be updated
        if ($fundRequest->status !== 'Draft') {
            return redirect()->route('fund-requests.show', $fundRequest->id)
                ->with('error', 'Only draft requests can be updated');
        }

        $validated = $request->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:1000',
            'status' => 'sometimes|in:Draft,Submitted'
        ]);

        $oldValues = $fundRequest->toArray();

        $fundRequest->request_type_id = $validated['request_type_id'];
        $fundRequest->amount = $validated['amount'];
        $fundRequest->purpose = $validated['purpose'];

        // If submitting the request
        if (isset($validated['status']) && $validated['status'] === 'Submitted') {
            $fundRequest->status = 'Submitted';
        }

        $fundRequest->save();

        $this->auditService->logUpdate('FundRequest', $fundRequest->id, $oldValues, $fundRequest->toArray());

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

        $oldValues = $fundRequest->toArray();

        $fundRequest->status = 'Submitted';
        $fundRequest->save();

        $this->auditService->logCustomAction('submitted', 'FundRequest', $fundRequest->id);

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

        $oldValues = $fundRequest->toArray();

        $fundRequest->status = 'Approved';
        $fundRequest->reviewed_by = Auth::id();
        $fundRequest->reviewed_at = now();
        $fundRequest->save();

        $this->auditService->logCustomAction('approved', 'FundRequest', $fundRequest->id);

        return redirect()->route('admin.fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request approved successfully');
    }

    /**
     * Reject a fund request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $fundRequest = FundRequest::findOrFail($id);

        // Only submitted or under review requests can be rejected
        if (!in_array($fundRequest->status, ['Submitted', 'Under Review'])) {
            return redirect()->route('admin.fund-requests.show', $fundRequest->id)
                ->with('error', 'Only submitted or under review requests can be rejected');
        }

        // Validate the request
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $oldValues = $fundRequest->toArray();

        $fundRequest->status = 'Rejected';
        $fundRequest->admin_notes = $validated['admin_notes'];
        $fundRequest->reviewed_by = Auth::id();
        $fundRequest->reviewed_at = now();
        $fundRequest->save();

        $this->auditService->logCustomAction('rejected', 'FundRequest', $fundRequest->id);

        return redirect()->route('admin.fund-requests.show', $fundRequest->id)
            ->with('success', 'Fund request rejected successfully');
    }
}
