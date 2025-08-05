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

// Controller para sa pag-manage ng mga disbursement (pagbibigay ng pera sa mga scholar)
class DisbursementController extends Controller
{
    protected $auditService;
    protected $notificationService;

    /**
     * Constructor - ginagamit para sa pag-setup ng controller
     * Nire-require na naka-login at admin lang ang pwedeng mag-access
     */
    public function __construct(AuditService $auditService, NotificationService $notificationService)
    {
        $this->middleware('auth'); // Kailangan naka-login
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class); // Admin lang pwede
        $this->auditService = $auditService;
        $this->notificationService = $notificationService;
    }

    /**
     * Nagdidisplay ng listahan ng lahat ng disbursements
     * May mga filter para sa scholar, status, at date range
     */
    public function index(Request $request)
    {
        // Kumuha ng lahat ng disbursements kasama ang related data
        $query = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy']);

        // Filter ayon sa scholar kung may napili
        if ($request->has('scholar_id') && $request->scholar_id) {
            $query->whereHas('fundRequest', function($q) use ($request) {
                $q->where('scholar_profile_id', $request->scholar_id);
            });
        }

        // Filter ayon sa status kung may napili
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter ayon sa simula ng petsa
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('disbursement_date', '>=', $request->start_date);
        }

        // Filter ayon sa katapusan ng petsa
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('disbursement_date', '<=', $request->end_date);
        }

        // I-sort ayon sa petsa, pinakabago muna
        $query->orderBy('disbursement_date', 'desc');

        // Hatiin ang results sa mga pahina (15 per page)
        $disbursements = $query->paginate(15);

        // Kumuha ng data para sa mga filter options
        $statuses = Disbursement::distinct('status')->pluck('status');
        $scholars = ScholarProfile::orderBy('last_name')->orderBy('first_name')->get();

        return view('disbursements.index', compact('disbursements', 'statuses', 'scholars'));
    }

    /**
     * Nagpapakita ng form para sa paggawa ng bagong disbursement
     * Pwedeng may pre-selected na fund request
     */
    public function create($fundRequestId = null)
    {
        // Kung may specific fund request na gusto i-disburse
        $fundRequest = null;
        if ($fundRequestId) {
            $fundRequest = FundRequest::with('scholarProfile')->findOrFail($fundRequestId);

            // Tingnan kung approved na ba ang fund request
            if ($fundRequest->status !== 'Approved') {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'Only approved fund requests can be disbursed.');
            }

            // Tingnan kung may existing disbursement na ba
            if ($fundRequest->disbursements()->exists()) {
                return redirect()->route('fund-requests.show', $fundRequest->id)
                    ->with('error', 'This fund request already has a disbursement.');
            }
        }

        // Kumuha ng lahat ng approved requests na walang disbursement pa
        $approvedRequests = FundRequest::with('scholarProfile')
            ->where('status', 'Approved')
            ->whereDoesntHave('disbursements')
            ->get();

        return view('disbursements.create', compact('fundRequest', 'approvedRequests'));
    }

    /**
     * Nag-save ng bagong disbursement sa database
     * Ginagawa ang proseso ng pagbibigay ng pera sa scholar
     */
    public function store(Request $request)
    {
        // I-validate ang mga input na galing sa form
        $request->validate([
            'fund_request_id' => 'required|exists:fund_requests,id',
            'amount' => 'required|numeric|min:0',
            'disbursement_date' => 'required|date',
            'payment_method' => 'required|string|in:Bank Transfer,Check,Cash',
            'payment_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Kumuha ng fund request na gusto i-disburse
        $fundRequest = FundRequest::findOrFail($request->fund_request_id);

        // Double check kung approved pa rin ba
        if ($fundRequest->status !== 'Approved') {
            return redirect()->back()
                ->with('error', 'Only approved fund requests can be disbursed.')
                ->withInput();
        }

        // Tingnan kung may disbursement na ba
        if ($fundRequest->disbursements()->exists()) {
            return redirect()->back()
                ->with('error', 'This fund request already has a disbursement.')
                ->withInput();
        }

        // Simulan ang database transaction para sa security
        DB::beginTransaction();

        try {
            // Gumawa ng bagong disbursement record
            $disbursement = Disbursement::create([
                'fund_request_id' => $fundRequest->id,
                'amount' => $request->amount,
                'disbursement_date' => $request->disbursement_date,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
                'reference_number' => 'DISB-' . date('Ymd') . '-' . Str::random(6), // Unique reference number
                'status' => 'Processed',
                'notes' => $request->notes,
                'processed_by' => auth()->id(), // Sino ang nag-process
            ]);

            // I-log ang action para sa audit trail
            $this->auditService->logCreate('Disbursement', $disbursement->id, $disbursement->toArray());

            // Mag-send ng notification sa scholar
            $this->notificationService->notify(
                $fundRequest->scholarProfile->user_id,
                'Disbursement Processed',
                'Your fund request (' . $fundRequest->reference_number . ') has been disbursed. Amount: ₱' . number_format($request->amount, 2),
                'disbursement',
                route('fund-requests.show', $fundRequest->id),
                false // Hindi mag-send ng email
            );

            // I-commit ang transaction kung successful
            DB::commit();

            return redirect()->route('disbursements.show', $disbursement->id)
                ->with('success', 'Disbursement processed successfully.');
        } catch (\Exception $e) {
            // I-rollback kung may error
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to process disbursement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Nagpapakita ng detalye ng isang specific disbursement
     * Kasama ang lahat ng related information
     */
    public function show($id)
    {
        // Kumuha ng disbursement kasama ang lahat ng related data
        $disbursement = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy'])->findOrFail($id);

        return view('disbursements.show', compact('disbursement'));
    }

    /**
     * Nagpapakita ng form para sa pag-edit ng disbursement
     * Para sa pag-update ng mga detalye
     */
    public function edit($id)
    {
        // Kumuha ng disbursement na gusto i-edit
        $disbursement = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile'])->findOrFail($id);

        return view('disbursements.edit', compact('disbursement'));
    }

    /**
     * Nag-update ng existing disbursement
     * Pwedeng baguhin ang amount, date, payment method, at status
     */
    public function update(Request $request, $id)
    {
        // I-validate ang mga bagong input
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'disbursement_date' => 'required|date',
            'payment_method' => 'required|string|in:Bank Transfer,Check,Cash',
            'payment_details' => 'nullable|string',
            'status' => 'required|string|in:Processed,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $disbursement = Disbursement::findOrFail($id);

        // I-save ang mga lumang values para sa audit log
        $oldValues = $disbursement->toArray();

        // Simulan ang database transaction
        DB::beginTransaction();

        try {
            // I-update ang disbursement
            $disbursement->update([
                'amount' => $request->amount,
                'disbursement_date' => $request->disbursement_date,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // I-log ang changes para sa audit trail
            $this->auditService->logUpdate(
                'Disbursement',
                $disbursement->id,
                $oldValues,
                $disbursement->toArray()
            );

            // Mag-send ng notification kung nagbago ang status
            if ($oldValues['status'] !== $request->status) {
                $this->notificationService->notify(
                    $disbursement->fundRequest->scholarProfile->user_id,
                    'Disbursement Status Update',
                    'Your disbursement (' . $disbursement->reference_number . ') status has been updated to: ' . $request->status,
                    'disbursement',
                    route('fund-requests.show', $disbursement->fund_request_id),
                    true // Mag-send ng email
                );
            }

            // I-commit ang transaction
            DB::commit();

            return redirect()->route('disbursements.show', $disbursement->id)
                ->with('success', 'Disbursement updated successfully.');
        } catch (\Exception $e) {
            // I-rollback kung may error
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update disbursement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Nag-export ng mga disbursement sa CSV file
     * Para sa pag-download ng records para sa reporting
     */
    public function export(Request $request)
    {
        // Kumuha ng disbursements kasama ang related data
        $query = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType', 'processedBy']);

        // I-apply ang mga filter kung meron
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

        // I-setup ang CSV headers para sa download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="disbursements_' . date('Y-m-d') . '.csv"',
        ];

        // Function para sa pag-generate ng CSV content
        $callback = function() use ($disbursements) {
            $file = fopen('php://output', 'w');

            // I-add ang column headers sa CSV
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

            // I-add ang data rows
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
     * Gumagawa ng financial report para sa mga disbursement
     * May mga statistics at breakdown ng mga gastos
     */
    public function report(Request $request)
    {
        // Kumuha ng date range, default ay current month
        $startDate = $request->has('start_date') ? $request->start_date : date('Y-m-01');
        $endDate = $request->has('end_date') ? $request->end_date : date('Y-m-t');

        // Kumuha ng lahat ng disbursements sa date range
        $disbursements = Disbursement::with(['fundRequest', 'fundRequest.scholarProfile', 'fundRequest.requestType'])
            ->whereDate('disbursement_date', '>=', $startDate)
            ->whereDate('disbursement_date', '<=', $endDate)
            ->get();

        // I-calculate ang total amount
        $totalAmount = $disbursements->sum('amount');

        // I-group ayon sa request type (thesis, allowance, etc.)
        $byRequestType = $disbursements->groupBy(function($item) {
            return $item->fundRequest->requestType->name;
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        // I-group ayon sa scholar (para makita kung sino ang nakakuha ng pinakamarami)
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

        // I-group ayon sa status (processed, completed, cancelled)
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
