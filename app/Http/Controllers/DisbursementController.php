<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disbursement;
use App\Models\FundRequest;
use App\Models\ScholarProfile;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DisbursementController extends Controller
{
    protected $auditService;
    protected $notificationService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AuditService  $auditService
     * @param  \App\Services\NotificationService  $notificationService
     * @return void
     */
    public function __construct(AuditService $auditService, NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class); // Only admins can access disbursements
        $this->auditService = $auditService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the disbursements.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy']);

        // Filter by scholar
        if ($request->has('scholar_id') && $request->scholar_id) {
            $query->whereHas('fundRequest', function($q) use ($request) {
                $q->where('scholar_profile_id', $request->scholar_id);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('disbursement_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('disbursement_date', '<=', $request->end_date);
        }

        // Order by disbursement_date in descending order
        $query->orderBy('disbursement_date', 'desc');

        // Paginate the results
        $disbursements = $query->paginate(15);

        // Get data for filters
        $statuses = Disbursement::distinct('status')->pluck('status');
        $scholars = ScholarProfile::orderBy('last_name')->orderBy('first_name')->get();

        return view('disbursements.index', compact('disbursements', 'statuses', 'scholars'));
    }

    /**
     * Show the form for creating a new disbursement.
     *
     * @param  int  $fundRequestId
     * @return \Illuminate\Http\Response
     */
    public function create($fundRequestId = null)
    {
        // If fund request ID is provided, pre-fill the form
        $fundRequest = null;
        if ($fundRequestId) {
            $fundRequest = FundRequest::with('scholarProfile')->findOrFail($fundRequestId);

            // Check if the fund request is approved
            if ($fundRequest->status !== 'Approved') {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only approved fund requests can be disbursed.');
            }

            // Check if the fund request already has a disbursement
            if ($fundRequest->disbursements()->exists()) {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'This fund request already has a disbursement.');
            }
        }

        // Get approved fund requests without disbursements
        $approvedRequests = FundRequest::with('scholarProfile')
            ->where('status', 'Approved')
            ->whereDoesntHave('disbursements')
            ->get();

        return view('disbursements.create', compact('fundRequest', 'approvedRequests'));
    }

    /**
     * Store a newly created disbursement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'fund_request_id' => 'required|exists:fund_requests,id',
            'amount' => 'required|numeric|min:0',
            'disbursement_date' => 'required|date',
            'payment_method' => 'required|string|in:Bank Transfer,Check,Cash',
            'payment_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Get the fund request
        $fundRequest = FundRequest::findOrFail($request->fund_request_id);

        // Check if the fund request is approved
        if ($fundRequest->status !== 'Approved') {
            return redirect()->back()
                ->with('error', 'Only approved fund requests can be disbursed.')
                ->withInput();
        }

        // Check if the fund request already has a disbursement
        if ($fundRequest->disbursements()->exists()) {
            return redirect()->back()
                ->with('error', 'This fund request already has a disbursement.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Create disbursement
            $disbursement = Disbursement::create([
                'fund_request_id' => $fundRequest->id,
                'amount' => $request->amount,
                'disbursement_date' => $request->disbursement_date,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
                'reference_number' => 'DISB-' . date('Ymd') . '-' . Str::random(6),
                'status' => 'Processed',
                'notes' => $request->notes,
                'processed_by' => auth()->id(),
            ]);

            // Log the action
            $this->auditService->logCreate('Disbursement', $disbursement->id, $disbursement->toArray());

            // Send notification to scholar
            $this->notificationService->notify(
                $fundRequest->scholarProfile->user_id,
                'Disbursement Processed',
                'Your fund request (' . $fundRequest->reference_number . ') has been disbursed. Amount: ₱' . number_format($request->amount, 2),
                'disbursement',
                route('fund-requests.show', $fundRequest->id),
                true // Send email
            );

            DB::commit();

            return redirect()->route('disbursements.show', $disbursement->id)
                ->with('success', 'Disbursement processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to process disbursement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified disbursement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $disbursement = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy'])->findOrFail($id);

        return view('disbursements.show', compact('disbursement'));
    }

    /**
     * Show the form for editing the specified disbursement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $disbursement = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile'])->findOrFail($id);

        return view('disbursements.edit', compact('disbursement'));
    }

    /**
     * Update the specified disbursement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'disbursement_date' => 'required|date',
            'payment_method' => 'required|string|in:Bank Transfer,Check,Cash',
            'payment_details' => 'nullable|string',
            'status' => 'required|string|in:Processed,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $disbursement = Disbursement::findOrFail($id);

        // Store old values for audit log
        $oldValues = $disbursement->toArray();

        DB::beginTransaction();

        try {
            // Update disbursement
            $disbursement->update([
                'amount' => $request->amount,
                'disbursement_date' => $request->disbursement_date,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Log the action
            $this->auditService->logUpdate(
                'Disbursement',
                $disbursement->id,
                $oldValues,
                $disbursement->toArray()
            );

            // Send notification to scholar if status changed
            if ($oldValues['status'] !== $request->status) {
                $this->notificationService->notify(
                    $disbursement->fundRequest->scholarProfile->user_id,
                    'Disbursement Status Update',
                    'Your disbursement (' . $disbursement->reference_number . ') status has been updated to: ' . $request->status,
                    'disbursement',
                    route('fund-requests.show', $disbursement->fund_request_id),
                    true // Send email
                );
            }

            DB::commit();

            return redirect()->route('disbursements.show', $disbursement->id)
                ->with('success', 'Disbursement updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update disbursement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export disbursements to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy']);

        // Apply filters
        if ($request->has('scholar_id') && $request->scholar_id) {
            $query->whereHas('fundRequest', function($q) use ($request) {
                $q->where('scholar_profile_id', $request->scholar_id);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('disbursement_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('disbursement_date', '<=', $request->end_date);
        }

        $disbursements = $query->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="disbursements_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($disbursements) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Reference Number',
                'Scholar',
                'Request Type',
                'Amount',
                'Disbursement Date',
                'Payment Method',
                'Status',
                'Processed By'
            ]);

            // Add data rows
            foreach ($disbursements as $disbursement) {
                fputcsv($file, [
                    $disbursement->reference_number,
                    $disbursement->fundRequest->scholarProfile->first_name . ' ' . $disbursement->fundRequest->scholarProfile->last_name,
                    $disbursement->fundRequest->requestType->name,
                    $disbursement->amount,
                    $disbursement->disbursement_date,
                    $disbursement->payment_method,
                    $disbursement->status,
                    $disbursement->processedBy ? $disbursement->processedBy->name : 'System'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate a financial report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        // Get date range
        $startDate = $request->has('start_date') ? $request->start_date : date('Y-m-01');
        $endDate = $request->has('end_date') ? $request->end_date : date('Y-m-t');

        // Get disbursements in date range
        $disbursements = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType'])
            ->whereDate('disbursement_date', '>=', $startDate)
            ->whereDate('disbursement_date', '<=', $endDate)
            ->get();

        // Calculate totals
        $totalAmount = $disbursements->sum('amount');

        // Group by request type
        $byRequestType = $disbursements->groupBy(function($item) {
            return $item->fundRequest->requestType->name;
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        // Group by scholar
        $byScholar = $disbursements->groupBy(function($item) {
            return $item->fundRequest->scholarProfile->id;
        })->map(function($group) {
            $scholar = $group->first()->fundRequest->scholarProfile;
            return [
                'name' => $scholar->first_name . ' ' . $scholar->last_name,
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        // Group by status
        $byStatus = $disbursements->groupBy('status')->map(function($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        return view('disbursements.report', compact(
            'disbursements',
            'startDate',
            'endDate',
            'totalAmount',
            'byRequestType',
            'byScholar',
            'byStatus'
        ));
    }
}
