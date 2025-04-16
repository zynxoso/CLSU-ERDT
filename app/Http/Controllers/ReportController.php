<?php

namespace App\Http\Controllers;

use App\Models\FundRequest;
use App\Models\Document;
use App\Models\Manuscript;
use App\Models\ScholarProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get overall statistics
        $stats = [
            'scholars' => [
                'total' => ScholarProfile::count(),
                'active' => ScholarProfile::where('status', 'Active')->count(),
                'graduated' => ScholarProfile::where('status', 'Graduated')->count(),
                'discontinued' => ScholarProfile::where('status', 'Discontinued')->count(),
            ],
            'funds' => [
                'total' => FundRequest::count(),
                'pending' => FundRequest::where('status', 'Pending')->count(),
                'approved' => FundRequest::where('status', 'Approved')->count(),
                'rejected' => FundRequest::where('status', 'Rejected')->count(),
                'total_amount' => FundRequest::where('status', 'Approved')->sum('amount'),
            ],
            'documents' => [
                'total' => Document::count(),
                'pending' => Document::where('status', 'Pending')->count(),
                'verified' => Document::where('status', 'Verified')->count(),
                'rejected' => Document::where('status', 'Rejected')->count(),
            ],
            'manuscripts' => [
                'total' => Manuscript::count(),
                'draft' => Manuscript::where('status', 'Outline Submitted')->count(),
                'under_review' => Manuscript::where('status', 'Under Review')->count(),
                'completed' => Manuscript::where('status', 'Published')->count(),
                'rejected' => Manuscript::where('status', 'Rejected')->count(),
            ],
        ];

        // Get monthly fund request data for the last 12 months
        $monthlyFunds = FundRequest::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN amount ELSE 0 END) as amount')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format data for charts
        $chartData = [
            'months' => $monthlyFunds->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            }),
            'fundCounts' => [
                'approved' => $monthlyFunds->pluck('approved'),
                'rejected' => $monthlyFunds->pluck('rejected'),
                'pending' => $monthlyFunds->pluck('pending'),
            ],
            'fundAmounts' => $monthlyFunds->pluck('amount'),
        ];

        // Get recent fund requests
        $recentFundRequests = FundRequest::with(['user', 'scholarProfile'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact('stats', 'chartData', 'recentFundRequests'));
    }

    /**
     * Generate a specific report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Validate request
        $request->validate([
            'report_type' => 'required|string|in:scholars,funds,documents,manuscripts',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'required|string|in:html,pdf,csv',
        ]);

        $reportType = $request->input('report_type');
        $format = $request->input('format');
        $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Get report data based on type
        switch ($reportType) {
            case 'scholars':
                $data = $this->getScholarReportData($startDate, $endDate);
                $title = 'Scholar Report';
                $view = 'admin.reports.scholars';
                break;
            case 'funds':
                $data = $this->getFundReportData($startDate, $endDate);
                $title = 'Financial Report';
                $view = 'admin.reports.funds';
                break;
            case 'documents':
                $data = $this->getDocumentReportData($startDate, $endDate);
                $title = 'Document Report';
                $view = 'admin.reports.documents';
                break;
            case 'manuscripts':
                $data = $this->getManuscriptReportData($startDate, $endDate);
                $title = 'Manuscript Report';
                $view = 'admin.reports.manuscripts';
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }

        // Generate report based on format
        switch ($format) {
            case 'html':
                return view($view, [
                    'data' => $data,
                    'title' => $title,
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]);

            case 'pdf':
                try {
                    // Use dedicated PDF templates for the reports
                    $pdfView = $reportType === 'scholars' ? 'admin.reports.scholars-pdf' :
                              ($reportType === 'manuscripts' ? 'admin.reports.manuscripts-pdf' : $view);

                    $pdf = PDF::loadView($pdfView, [
                        'data' => $data,
                        'title' => $title,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'isPdf' => true
                    ]);

                    // Set landscape orientation for certain reports
                    if ($reportType === 'manuscripts' || $reportType === 'scholars') {
                        $pdf->setPaper('a4', 'landscape');
                    }

                    $fileName = strtolower(str_replace(' ', '_', $title)) . '_' . now()->format('Y-m-d') . '.pdf';
                    return $pdf->download($fileName);
                } catch (\Exception $e) {
                    Log::error('PDF generation error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'PDF generation failed: ' . $e->getMessage());
                }

            case 'csv':
                $fileName = strtolower(str_replace(' ', '_', $title)) . '_' . now()->format('Y-m-d') . '.csv';
                return $this->generateCsv($data, $reportType, $fileName);

            default:
                return redirect()->back()->with('error', 'Invalid format type.');
        }
    }

    /**
     * Get scholar report data.
     *
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getScholarReportData($startDate, $endDate)
    {
        $query = ScholarProfile::with('user');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get fund report data.
     *
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFundReportData($startDate, $endDate)
    {
        $query = FundRequest::with(['scholarProfile', 'user']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get document report data.
     *
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDocumentReportData($startDate, $endDate)
    {
        $query = Document::with(['scholarProfile', 'user']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get manuscript report data.
     *
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getManuscriptReportData($startDate, $endDate)
    {
        $query = Manuscript::with(['scholarProfile.user']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Generate CSV file from report data.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $data
     * @param  string  $reportType
     * @param  string  $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function generateCsv($data, $reportType, $fileName)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data, $reportType) {
            $file = fopen('php://output', 'w');

            // Add headers based on report type
            switch ($reportType) {
                case 'scholars':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Program', 'University', 'Status', 'Start Date', 'Expected Completion', 'Created At']);
                    foreach ($data as $row) {
                        $userName = $row->user ? $row->user->name : 'Unknown';
                        $userEmail = $row->user ? $row->user->email : 'N/A';

                        fputcsv($file, [
                            $row->id,
                            $userName,
                            $userEmail,
                            $row->program ?? 'N/A',
                            $row->university ?? 'N/A',
                            $row->status ?? 'N/A',
                            $row->start_date ?? 'N/A',
                            $row->expected_completion_date ?? 'N/A',
                            $row->created_at
                        ]);
                    }
                    break;

                case 'funds':
                    fputcsv($file, ['ID', 'Scholar', 'Email', 'Purpose', 'Amount', 'Status', 'Submitted Date', 'Processed Date']);
                    foreach ($data as $row) {
                        $userName = $row->user ? $row->user->name : 'Unknown';
                        $userEmail = $row->user ? $row->user->email : 'N/A';

                        fputcsv($file, [
                            $row->id,
                            $userName,
                            $userEmail,
                            $row->purpose ?? 'N/A',
                            $row->amount ?? 0,
                            $row->status ?? 'N/A',
                            $row->created_at,
                            $row->updated_at
                        ]);
                    }
                    break;

                case 'documents':
                    fputcsv($file, ['ID', 'Scholar', 'Email', 'Title', 'Type', 'Status', 'Uploaded Date', 'Verified Date']);
                    foreach ($data as $row) {
                        $userName = $row->scholarProfile && $row->scholarProfile->user ? $row->scholarProfile->user->name : 'Unknown';
                        $userEmail = $row->scholarProfile && $row->scholarProfile->user ? $row->scholarProfile->user->email : 'N/A';

                        fputcsv($file, [
                            $row->id,
                            $userName,
                            $userEmail,
                            $row->title ?? 'N/A',
                            $row->document_type ?? 'N/A',
                            $row->status ?? 'N/A',
                            $row->created_at,
                            $row->verified_at
                        ]);
                    }
                    break;

                case 'manuscripts':
                    fputcsv($file, ['ID', 'Scholar', 'Email', 'Title', 'Type', 'Status', 'Created Date', 'Last Updated']);
                    foreach ($data as $row) {
                        $scholarName = $row->scholarProfile && $row->scholarProfile->user ? $row->scholarProfile->user->name : 'Unknown';
                        $scholarEmail = $row->scholarProfile && $row->scholarProfile->user ? $row->scholarProfile->user->email : 'N/A';

                        fputcsv($file, [
                            $row->id,
                            $scholarName,
                            $scholarEmail,
                            $row->title,
                            $row->manuscript_type ?? 'N/A',
                            $row->status,
                            $row->created_at,
                            $row->updated_at
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Test the PDF generation functionality.
     *
     * @return \Illuminate\Http\Response
     */
    public function testPdf()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        try {
            $data = ScholarProfile::with('user')->limit(5)->get();
            $title = 'Scholar Test Report';

            $pdf = PDF::loadView('admin.reports.scholars-pdf', [
                'data' => $data,
                'title' => $title,
                'startDate' => now()->subMonths(1),
                'endDate' => now(),
                'isPdf' => true
            ]);

            // Use landscape orientation for better table display
            $pdf->setPaper('a4', 'landscape');

            return $pdf->download('test_scholar_report.pdf');
        } catch (\Exception $e) {
            Log::error('Scholar PDF generation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'PDF generation failed',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test the manuscript PDF generation functionality.
     *
     * @return \Illuminate\Http\Response
     */
    public function testManuscriptPdf()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        try {
            $data = Manuscript::with(['scholarProfile.user'])->limit(5)->get();
            $title = 'Manuscript Test Report';

            $pdf = PDF::loadView('admin.reports.manuscripts-pdf', [
                'data' => $data,
                'title' => $title,
                'startDate' => now()->subMonths(1),
                'endDate' => now(),
                'isPdf' => true
            ]);

            // Set landscape orientation for better table display
            $pdf->setPaper('a4', 'landscape');

            return $pdf->download('test_manuscript_report.pdf');
        } catch (\Exception $e) {
            Log::error('Manuscript PDF generation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Manuscript PDF generation failed',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Generate PDF file from report data.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $data
     * @param  string  $reportType
     * @param  string  $title
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function generatePdf($data, $reportType, $title, $startDate = null, $endDate = null)
    {
        // Set view based on report type
        switch ($reportType) {
            case 'scholars':
                $view = 'admin.reports.scholars-pdf';
                break;
            case 'funds':
                $view = 'admin.reports.funds';
                break;
            case 'documents':
                $view = 'admin.reports.documents';
                break;
            case 'manuscripts':
                $view = 'admin.reports.manuscripts-pdf';
                break;
            default:
                $view = 'admin.reports.' . $reportType;
        }

        try {
            $pdf = PDF::loadView($view, [
                'data' => $data,
                'title' => $title,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'isPdf' => true
            ]);

            // Set paper to landscape for better table display in manuscripts and scholars
            if ($reportType === 'manuscripts' || $reportType === 'scholars') {
                $pdf->setPaper('a4', 'landscape');
            }

            return $pdf->download($reportType . '_report_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return an error response
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }
}
