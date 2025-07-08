<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Manuscript;
use App\Models\Document;
use App\Models\ScholarProfile;
use App\Services\AuditService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ManuscriptController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of manuscripts for admin users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Return the view with Livewire component
        return view('admin.manuscripts.index');
    }





    /**
     * Export manuscripts with filtering options
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        $format = $request->get('format', 'excel'); // Default to excel

        // Get all filtered manuscripts (without pagination)
        $manuscripts = $this->getFilteredManuscriptsForExport($request);

        if ($format === 'zip') {
            return $this->exportAsZip($manuscripts, $request);
        } else {
            return $this->exportAsExcel($manuscripts, $request);
        }
    }

    /**
     * Get filtered manuscripts for export (without pagination)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFilteredManuscriptsForExport(Request $request)
    {
        $query = Manuscript::with(['scholarProfile.user', 'documents']);

        // Apply same filters as in getFilteredManuscripts
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('scholar')) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('submission_date_from')) {
            $query->where('created_at', '>=', $request->submission_date_from);
        }

        if ($request->filled('submission_date_to')) {
            $query->where('created_at', '<=', $request->submission_date_to . ' 23:59:59');
        }

        if ($request->filled('type')) {
            $query->where('manuscript_type', $request->type);
        }

        if ($request->filled('keywords')) {
            $keywords = explode(',', $request->keywords);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('keywords', 'like', '%' . trim($keyword) . '%');
                }
            });
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * Export manuscripts as Excel file
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $manuscripts
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function exportAsExcel($manuscripts, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'Reference Number',
            'Title',
            'Author',
            'Author Email',
            'Program',
            'Manuscript Type',
            'Status',
            'Co-Authors',
            'Keywords',
            'Submission Date',
            'Last Updated',
            'Abstract',
            'Admin Notes',
            'Document Count'
        ];

        // Write headers
        $colIndex = 0;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
        foreach ($headers as $header) {
            $sheet->setCellValue($columns[$colIndex] . '1', $header);
            $colIndex++;
        }

        // Style headers
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A1:N1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Write data
        $row = 2;
        foreach ($manuscripts as $manuscript) {
            $sheet->setCellValue('A' . $row, $manuscript->reference_number ?? 'N/A');
            $sheet->setCellValue('B' . $row, $manuscript->title);
            $sheet->setCellValue('C' . $row, $manuscript->user ? $manuscript->user->name : 'Unknown');
            $sheet->setCellValue('D' . $row, $manuscript->user ? $manuscript->user->email : 'N/A');
            $sheet->setCellValue('E' . $row, $manuscript->scholarProfile ? $manuscript->scholarProfile->program : 'N/A');
            $sheet->setCellValue('F' . $row, $manuscript->manuscript_type);
            $sheet->setCellValue('G' . $row, $manuscript->status);
            $sheet->setCellValue('H' . $row, $manuscript->co_authors ?? 'N/A');
            $sheet->setCellValue('I' . $row, $manuscript->keywords ?? 'N/A');
            $sheet->setCellValue('J' . $row, $manuscript->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $manuscript->updated_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('L' . $row, Str::limit($manuscript->abstract, 500));
            $sheet->setCellValue('M' . $row, $manuscript->admin_notes ?? 'N/A');
            $sheet->setCellValue('N' . $row, $manuscript->documents->count());
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Generate filename with timestamp and filters
        $filename = 'manuscripts_export_' . date('Y-m-d_H-i-s');
        if ($request->filled('status')) {
            $filename .= '_status-' . Str::slug($request->status);
        }
        if ($request->filled('type')) {
            $filename .= '_type-' . Str::slug($request->type);
        }
        $filename .= '.xlsx';

        // Create writer and save to temp file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'manuscript_export');
        $writer->save($tempFile);

        // Log the export action
        $this->auditService->logCustomAction('exported_manuscripts_excel', 'Manuscript', null, [
            'filter_count' => $manuscripts->count(),
            'filters' => $request->except(['_token', 'format'])
        ]);

        // Return file download response
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export manuscripts as compressed archive with documents
     * Creates proper ZIP structure with date-based folders
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $manuscripts
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function exportAsZip($manuscripts, $request)
    {
        // Try different ZIP creation methods in order of preference
        if (class_exists('ZipArchive')) {
            return $this->createZipWithZipArchive($manuscripts, $request);
        } else {
            // Use system ZIP command if available, otherwise create tar.gz
            return $this->createZipWithSystemCommand($manuscripts, $request);
        }
    }

    /**
     * Create ZIP using ZipArchive (when available)
     */
    private function createZipWithZipArchive($manuscripts, $request)
    {
        $dateFolder = date('Y-m-d_H-i-s');
        $zipFileName = 'manuscripts_batch_' . $dateFolder . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'Could not create ZIP file');
        }

        // Create the main date folder inside ZIP
        $zip->addEmptyDir($dateFolder);

        // Add metadata Excel file to the date folder
        $metadataFile = $this->createMetadataFile($manuscripts);
        $zip->addFile($metadataFile, $dateFolder . '/manuscripts_metadata.xlsx');

        // Add all manuscripts to the date folder
        foreach ($manuscripts as $index => $manuscript) {
            $manuscriptName = $this->sanitizeFileName($manuscript->title) . '_' . $manuscript->id;
            $manuscriptFolder = $dateFolder . '/' . sprintf('%03d', $index + 1) . '_' . $manuscriptName;

            // Create manuscript folder
            $zip->addEmptyDir($manuscriptFolder);

            // Add manuscript info file
            $metadataContent = $this->generateManuscriptMetadata($manuscript);
            $zip->addFromString($manuscriptFolder . '/manuscript_info.txt', $metadataContent);

            // Add documents folder if there are documents
            if ($manuscript->documents->count() > 0) {
                $zip->addEmptyDir($manuscriptFolder . '/documents');

                foreach ($manuscript->documents as $docIndex => $document) {
                    $filePath = storage_path('app/public/' . $document->file_path);
                    if (file_exists($filePath)) {
                        $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                        $documentName = sprintf('%02d', $docIndex + 1) . '_' . $this->sanitizeFileName($document->file_name);
                        $zip->addFile($filePath, $manuscriptFolder . '/documents/' . $documentName);
                    }
                }
            }
        }

        $zip->close();

        // Clean up temporary metadata file
        if (file_exists($metadataFile)) {
            unlink($metadataFile);
        }

        // Log the export action
        $this->auditService->logCustomAction('exported_manuscripts_zip', 'Manuscript', null, [
            'filter_count' => $manuscripts->count(),
            'filters' => $request->except(['_token', 'format']),
            'date_folder' => $dateFolder
        ]);

        return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Create ZIP using system command or create organized folder structure
     */
    private function createZipWithSystemCommand($manuscripts, $request)
    {
        $dateFolder = date('Y-m-d_H-i-s');
        $batchId = 'batch_' . $dateFolder . '_' . uniqid();
        $batchPath = storage_path('app/temp/' . $batchId);
        $dateFolderPath = $batchPath . '/' . $dateFolder;

        // Create directory structure
        if (!file_exists($dateFolderPath)) {
            mkdir($dateFolderPath, 0755, true);
        }

        // Create metadata Excel file in the date folder
        $metadataFile = $this->createMetadataFile($manuscripts);
        $metadataDestination = $dateFolderPath . '/manuscripts_metadata.xlsx';
        copy($metadataFile, $metadataDestination);

        // Create manuscript folders
        foreach ($manuscripts as $index => $manuscript) {
            $manuscriptName = $this->sanitizeFileName($manuscript->title) . '_' . $manuscript->id;
            $manuscriptFolder = $dateFolderPath . '/' . sprintf('%03d', $index + 1) . '_' . $manuscriptName;

            if (!file_exists($manuscriptFolder)) {
                mkdir($manuscriptFolder, 0755, true);
            }

            // Create manuscript info file
            $metadataContent = $this->generateManuscriptMetadata($manuscript);
            file_put_contents($manuscriptFolder . '/manuscript_info.txt', $metadataContent);

            // Copy documents
            if ($manuscript->documents->count() > 0) {
                $documentsFolder = $manuscriptFolder . '/documents';
                if (!file_exists($documentsFolder)) {
                    mkdir($documentsFolder, 0755, true);
                }

                foreach ($manuscript->documents as $docIndex => $document) {
                    $sourcePath = storage_path('app/public/' . $document->file_path);
                    if (file_exists($sourcePath)) {
                        $documentName = sprintf('%02d', $docIndex + 1) . '_' . $this->sanitizeFileName($document->file_name);
                        $destinationPath = $documentsFolder . '/' . $documentName;
                        copy($sourcePath, $destinationPath);
                    }
                }
            }
        }

        // Try to create ZIP using system command
        $zipFileName = 'manuscripts_batch_' . $dateFolder . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Convert paths to Windows format for PowerShell
        $windowsBatchPath = str_replace('/', '\\', $batchPath);
        $windowsZipPath = str_replace('/', '\\', $zipPath);
        $windowsDateFolder = str_replace('/', '\\', $dateFolder);

        // Try different ZIP commands based on OS
        $zipCommands = [];

        // Windows PowerShell command (most reliable on Windows)
        if (PHP_OS_FAMILY === 'Windows') {
            $zipCommands[] = "powershell -Command \"Compress-Archive -Path '{$windowsBatchPath}\\{$windowsDateFolder}' -DestinationPath '{$windowsZipPath}' -Force\"";
        }

        // Unix-like systems
        $zipCommands[] = "cd " . escapeshellarg($batchPath) . " && zip -r " . escapeshellarg($zipPath) . " " . escapeshellarg($dateFolder);
        $zipCommands[] = "cd " . escapeshellarg($batchPath) . " && 7z a " . escapeshellarg($zipPath) . " " . escapeshellarg($dateFolder);

        $zipCreated = false;
        $finalFileName = $zipFileName;
        $commandOutput = [];

        foreach ($zipCommands as $command) {
            exec($command . " 2>&1", $commandOutput, $returnCode);
            if ($returnCode === 0 && file_exists($zipPath)) {
                $zipCreated = true;
                break;
            }
        }

        // Clean up temporary files
        if (file_exists($metadataFile)) {
            unlink($metadataFile);
        }

        if ($zipCreated && file_exists($zipPath)) {
            // Clean up the temporary folder
            $this->deleteDirectory($batchPath);

            // Log the export action
            $this->auditService->logCustomAction('exported_manuscripts_zip', 'Manuscript', null, [
                'filter_count' => $manuscripts->count(),
                'filters' => $request->except(['_token', 'format']),
                'date_folder' => $dateFolder,
                'method' => 'system_command'
            ]);

            return Response::download($zipPath, $finalFileName)->deleteFileAfterSend(true);
        } else {
            // If ZIP creation failed, try creating a tar.gz as fallback
            $tarFileName = 'manuscripts_batch_' . $dateFolder . '.tar.gz';
            $tarPath = storage_path('app/temp/' . $tarFileName);

            $tarCommand = "cd " . escapeshellarg($batchPath) . " && tar -czf " . escapeshellarg($tarPath) . " " . escapeshellarg($dateFolder);
            exec($tarCommand . " 2>&1", $tarOutput, $tarReturnCode);

            if ($tarReturnCode === 0 && file_exists($tarPath)) {
                // Clean up the temporary folder
                $this->deleteDirectory($batchPath);

                // Log the export action
                $this->auditService->logCustomAction('exported_manuscripts_archive', 'Manuscript', null, [
                    'filter_count' => $manuscripts->count(),
                    'filters' => $request->except(['_token', 'format']),
                    'date_folder' => $dateFolder,
                    'archive_type' => 'tar.gz'
                ]);

                return Response::download($tarPath, $tarFileName)->deleteFileAfterSend(true);
            } else {
                // If all compression methods failed, return the organized folder structure for manual download
                return $this->createManualDownloadInterface($manuscripts, $request, $batchId, $dateFolder);
            }
        }
    }

    /**
     * Create manual download interface when ZIP creation fails
     */
    private function createManualDownloadInterface($manuscripts, $request, $batchId, $dateFolder)
    {
        // Log the export action
        $this->auditService->logCustomAction('exported_manuscripts_manual', 'Manuscript', null, [
            'filter_count' => $manuscripts->count(),
            'filters' => $request->except(['_token', 'format']),
            'batch_id' => $batchId,
            'date_folder' => $dateFolder
        ]);

        // Return the download page with organized structure
        return response()->view('admin.manuscripts.batch_download', [
            'batchId' => $batchId,
            'dateFolder' => $dateFolder,
            'manuscriptCount' => $manuscripts->count(),
            'fileCount' => $this->countTotalFiles($manuscripts)
        ]);
    }

    /**
     * Count total files for a collection of manuscripts
     */
    private function countTotalFiles($manuscripts)
    {
        $count = 1; // metadata file
        foreach ($manuscripts as $manuscript) {
            $count++; // manuscript info file
            $count += $manuscript->documents->count(); // document files
        }
        return $count;
    }

    /**
     * Sanitize filename for safe file system usage
     */
    private function sanitizeFileName($filename)
    {
        // Remove or replace unsafe characters
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $filename);
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        // Trim underscores from start and end
        $filename = trim($filename, '_');
        // Limit length
        return substr($filename, 0, 100);
    }

    /**
     * Recursively delete a directory
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Create metadata Excel file for ZIP export
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $manuscripts
     * @return string
     */
    private function createMetadataFile($manuscripts)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Manuscripts Metadata');

        // Set headers
        $headers = [
            'Reference Number',
            'Title',
            'Author',
            'Author Email',
            'Program',
            'Manuscript Type',
            'Status',
            'Co-Authors',
            'Keywords',
            'Submission Date',
            'Last Updated',
            'Abstract',
            'Admin Notes',
            'Document Count',
            'Folder Path'
        ];

        // Write headers
        $colIndex = 0;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
        foreach ($headers as $header) {
            $sheet->setCellValue($columns[$colIndex] . '1', $header);
            $colIndex++;
        }

        // Style headers
        $sheet->getStyle('A1:O1')->getFont()->setBold(true);
        $sheet->getStyle('A1:O1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Write data
        $row = 2;
        foreach ($manuscripts as $manuscript) {
            $authorSlug = $manuscript->user ? Str::slug($manuscript->user->name) : 'unknown';
            $folderPath = $authorSlug . '/' . Str::slug($manuscript->title) . '_' . $manuscript->id;

            $sheet->setCellValue('A' . $row, $manuscript->reference_number ?? 'N/A');
            $sheet->setCellValue('B' . $row, $manuscript->title);
            $sheet->setCellValue('C' . $row, $manuscript->user ? $manuscript->user->name : 'Unknown');
            $sheet->setCellValue('D' . $row, $manuscript->user ? $manuscript->user->email : 'N/A');
            $sheet->setCellValue('E' . $row, $manuscript->scholarProfile ? $manuscript->scholarProfile->program : 'N/A');
            $sheet->setCellValue('F' . $row, $manuscript->manuscript_type);
            $sheet->setCellValue('G' . $row, $manuscript->status);
            $sheet->setCellValue('H' . $row, $manuscript->co_authors ?? 'N/A');
            $sheet->setCellValue('I' . $row, $manuscript->keywords ?? 'N/A');
            $sheet->setCellValue('J' . $row, $manuscript->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $manuscript->updated_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('L' . $row, Str::limit($manuscript->abstract, 500));
            $sheet->setCellValue('M' . $row, $manuscript->admin_notes ?? 'N/A');
            $sheet->setCellValue('N' . $row, $manuscript->documents->count());
            $sheet->setCellValue('O' . $row, $folderPath);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'manuscript_metadata');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return $tempFile;
    }

    /**
     * Generate manuscript metadata as text
     *
     * @param  \App\Models\Manuscript  $manuscript
     * @return string
     */
    private function generateManuscriptMetadata($manuscript)
    {
        $metadata = "MANUSCRIPT INFORMATION\n";
        $metadata .= "========================\n\n";
        $metadata .= "Reference Number: " . ($manuscript->reference_number ?? 'N/A') . "\n";
        $metadata .= "Title: " . $manuscript->title . "\n";
        $metadata .= "Author: " . ($manuscript->user ? $manuscript->user->name : 'Unknown') . "\n";
        $metadata .= "Author Email: " . ($manuscript->user ? $manuscript->user->email : 'N/A') . "\n";
        $metadata .= "Program: " . ($manuscript->scholarProfile ? $manuscript->scholarProfile->program : 'N/A') . "\n";
        $metadata .= "Manuscript Type: " . $manuscript->manuscript_type . "\n";
        $metadata .= "Status: " . $manuscript->status . "\n";
        $metadata .= "Co-Authors: " . ($manuscript->co_authors ?? 'N/A') . "\n";
        $metadata .= "Keywords: " . ($manuscript->keywords ?? 'N/A') . "\n";
        $metadata .= "Submission Date: " . $manuscript->created_at->format('Y-m-d H:i:s') . "\n";
        $metadata .= "Last Updated: " . $manuscript->updated_at->format('Y-m-d H:i:s') . "\n";
        $metadata .= "Document Count: " . $manuscript->documents->count() . "\n\n";

        $metadata .= "ABSTRACT\n";
        $metadata .= "========\n";
        $metadata .= $manuscript->abstract . "\n\n";

        if ($manuscript->admin_notes) {
            $metadata .= "ADMIN NOTES\n";
            $metadata .= "===========\n";
            $metadata .= $manuscript->admin_notes . "\n\n";
        }

        if ($manuscript->documents->count() > 0) {
            $metadata .= "ASSOCIATED DOCUMENTS\n";
            $metadata .= "===================\n";
            foreach ($manuscript->documents as $document) {
                $metadata .= "- " . $document->file_name . " (" . $this->formatFileSize($document->file_size) . ")\n";
            }
        }

        return $metadata;
    }

    /**
     * Format file size in human readable format
     *
     * @param  int  $size
     * @return string
     */
    private function formatFileSize($size)
    {
        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    /**
     * Show the form for creating a new manuscript.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        $scholars = ScholarProfile::with('user')->get();
        return view('admin.manuscripts.create', compact('scholars'));
    }

    /**
     * Store a newly created manuscript in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'scholar_id' => 'required|exists:scholar_profiles,id',
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'manuscript_type' => 'required|string|in:Outline,Final',
            'co_authors' => 'nullable|string|max:255',
            'keywords' => 'required|string|max:255',
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'admin_notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        $manuscript = new Manuscript();
        $manuscript->scholar_profile_id = $validated['scholar_id'];
        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'] ?? null;
        $manuscript->keywords = $validated['keywords'];
        $manuscript->status = $validated['status'];
        $manuscript->admin_notes = $validated['admin_notes'] ?? null;
        $manuscript->save();

        // Generate reference number after saving
        $manuscript->reference_number = 'MS-' . str_pad($manuscript->id, 5, '0', STR_PAD_LEFT);
        $manuscript->save();

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('manuscripts', 'public');

            // Create a document entry for this file
            $document = new Document([
                'scholar_profile_id' => $validated['scholar_id'],
                'title' => $manuscript->title . ' - Manuscript File',
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_type' => 'manuscript',
                'category' => 'manuscript',
                'status' => 'verified',
            ]);

            $manuscript->documents()->save($document);
        }

        $this->auditService->logCreate('Manuscript', $manuscript->id, $manuscript->toArray());

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $manuscript = Manuscript::with(['scholarProfile.user', 'documents', 'reviewComments'])
            ->findOrFail($id);

        return view('admin.manuscripts.show', compact('manuscript'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $manuscript = Manuscript::findOrFail($id);
        $scholars = ScholarProfile::with('user')->get();

        return view('admin.manuscripts.edit', compact('manuscript', 'scholars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $manuscript = Manuscript::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'manuscript_type' => 'required|string|in:Outline,Final',
            'co_authors' => 'nullable|string|max:255',
            'keywords' => 'required|string|max:255',
            'status' => 'sometimes|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $oldValues = $manuscript->toArray();

        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'];
        $manuscript->keywords = $validated['keywords'];
        $manuscript->admin_notes = $validated['admin_notes'];

        // Update status if provided
        if (isset($validated['status'])) {
            $manuscript->status = $validated['status'];
        }

        $manuscript->save();

        $this->auditService->logUpdate('Manuscript', $manuscript->id, $oldValues, $manuscript->toArray());

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $manuscript = Manuscript::findOrFail($id);
        $oldValues = $manuscript->toArray();

        // Delete associated documents
        foreach ($manuscript->documents as $document) {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();
        }

        $manuscript->delete();

        $this->auditService->logDelete('Manuscript', $id, $oldValues);

        return redirect()->route('admin.manuscripts.index')
            ->with('success', 'Manuscript deleted successfully');
    }

    /**
     * Download individual files from batch export
     *
     * @param  string  $batchId
     * @param  string  $file
     * @return \Illuminate\Http\Response
     */
    public function batchDownloadFile($batchId, $file)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // Sanitize the batch ID to prevent directory traversal
        $batchId = preg_replace('/[^a-zA-Z0-9_-]/', '', $batchId);
        $batchPath = storage_path('app/temp/' . $batchId);

        // Check if batch directory exists
        if (!file_exists($batchPath)) {
            abort(404, 'Batch not found');
        }

        // Sanitize and construct file path
        $filePath = $batchPath . '/' . $file;

        // Ensure the file is within the batch directory (prevent directory traversal)
        $realBatchPath = realpath($batchPath);
        $realFilePath = realpath($filePath);

        if (!$realFilePath || strpos($realFilePath, $realBatchPath) !== 0) {
            abort(404, 'File not found');
        }

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Get the original filename for download
        $downloadName = basename($file);

        // Determine content type
        $contentType = $this->getContentType($filePath);

        // Return file download
        return Response::download($filePath, $downloadName, [
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Get content type for file download
     *
     * @param  string  $filePath
     * @return string
     */
    private function getContentType($filePath)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        return $mimeType;
    }

    /**
     * Update just the status and notes of a manuscript and notify the scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatusAndNotes(Request $request, $id)
    {
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'admin_notes' => 'nullable|string',
            'notify_scholar' => 'sometimes|boolean'
        ]);

        $oldValues = $manuscript->toArray();
        $oldStatus = $manuscript->status;
        $newStatus = $validated['status'];

        // Update manuscript status and notes
        $manuscript->setAttribute('status', $newStatus);
        $manuscript->admin_notes = $validated['admin_notes'] ?? $manuscript->admin_notes;
        $manuscript->save();

        // Log the status change
        $this->auditService->logCustomAction("status_change_from_{$oldStatus}_to_{$newStatus}", 'Manuscript', $manuscript->id);

        // Always notify scholar when status changes (unless it's the same status)
        if ($oldStatus !== $newStatus) {
            $scholarProfile = $manuscript->scholarProfile;
            if ($scholarProfile && $scholarProfile->user) {
                // Send Laravel notification (email + database) if checkbox is checked
                if ($request->has('notify_scholar') && $request->notify_scholar) {
                    $scholarProfile->user->notify(new \App\Notifications\ManuscriptStatusChanged(
                        $manuscript,
                        $oldStatus,
                        $newStatus,
                        $validated['admin_notes'] ?? null
                    ));
                }

                // Always send CustomNotification for unified in-app display
                $notificationService = app(\App\Services\NotificationService::class);
                $title = 'Manuscript Status Updated';
                $message = "Your manuscript \"{$manuscript->title}\" status has been changed from \"{$oldStatus}\" to \"{$newStatus}\".";

                if ($validated['admin_notes']) {
                    $message .= "\n\nAdmin Notes: " . $validated['admin_notes'];
                }

                $notificationService->notify(
                    $scholarProfile->user->id,
                    $title,
                    $message,
                    'manuscript',
                    route('scholar.manuscripts.show', $manuscript->id),
                    false // Email is handled by Laravel notification above
                );
            }
        }

        $notificationMessage = $oldStatus !== $newStatus
            ? "Manuscript status updated successfully. Scholar has been notified of the status change."
            : "Manuscript updated successfully.";

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', $notificationMessage);
    }
}
