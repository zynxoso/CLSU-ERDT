<?php

namespace App\Http\Controllers\Admin;

// Mga kailangan na library at class para sa controller
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

/**
 * ManuscriptController - Nag-hahandle ng lahat ng manuscript operations para sa admin
 * Kasama dito ang pag-view, pag-create, pag-edit, pag-delete, at pag-export ng manuscripts
 */
class ManuscriptController extends Controller
{
    // Service na ginagamit para sa audit logging (para ma-track ang mga changes)
    protected $auditService; 

    /**
     * Constructor - Tumatakbo kapag ginagawa ang bagong instance ng controller
     * Sinisiguro na naka-login ang user at naka-setup ang audit service
     */
    public function __construct(AuditService $auditService)
    {
        // Siguraduhing naka-login ang user bago mag-access ng kahit anong method
        $this->middleware('auth');
        // I-setup ang audit service para sa logging
        $this->auditService = $auditService;
    }

    /**
     * Ipapakita ang list ng lahat ng manuscripts para sa admin
     * Ito ang main page kung saan makikita ng admin ang lahat ng submitted manuscripts
     *
     * @param  \Illuminate\Http\Request  $request - HTTP request na may filters at search parameters
     * @return \Illuminate\Http\Response - Babalik na view na may manuscript list
     */
    public function index(Request $request)
    {
        // I-check muna kung admin ba ang naka-login na user
        if (Auth::user()->role !== 'admin') {
            // Kung hindi admin, i-redirect sa home page na may error message
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // I-return ang view na may Livewire component para sa dynamic na manuscript list
        // Ang Livewire component ay nag-hahandle ng filtering, searching, at pagination
        return view('admin.manuscripts.index');
    }





    /**
     * Mag-export ng manuscripts sa iba't ibang format (Excel o ZIP)
     * Pwedeng i-filter ang mga manuscript bago i-export
     *
     * @param  \Illuminate\Http\Request  $request - May format at filter parameters
     * @return \Illuminate\Http\Response - File download response
     */
    public function export(Request $request)
    {
        // I-check muna kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // Kunin ang format na gusto (default ay excel)
        $format = $request->get('format', 'excel');

        // Kunin lahat ng manuscripts na naka-filter (walang pagination para sa export)
        $manuscripts = $this->getFilteredManuscriptsForExport($request);

        // Depende sa format, tawagan ang tamang export method
        if ($format === 'zip') {
            // Kung ZIP, kasama ang mga files ng manuscript
            return $this->exportAsZip($manuscripts, $request);
        } else {
            // Kung Excel, data lang ng manuscripts
            return $this->exportAsExcel($manuscripts, $request);
        }
    }

    /**
     * Kunin ang mga manuscript na naka-filter para sa export
     * Walang pagination dito kasi kailangan natin lahat ng data para sa export
     *
     * @param  \Illuminate\Http\Request  $request - May mga filter parameters
     * @return \Illuminate\Database\Eloquent\Collection - Collection ng filtered manuscripts
     */
    private function getFilteredManuscriptsForExport(Request $request)
    {
        // Gumawa ng query na kasama ang scholar profile, user, at documents
        $query = Manuscript::with(['scholarProfile.user', 'documents']);

        // I-apply ang mga filters depende sa mga parameters na nandoon sa request

        // Filter by status (Draft, Submitted, Under Review, etc.)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by scholar name
        if ($request->filled('scholar')) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Filter by manuscript title (search)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by submission date range - from date
        if ($request->filled('submission_date_from')) {
            $query->where('created_at', '>=', $request->submission_date_from);
        }

        // Filter by submission date range - to date (hanggang end ng araw)
        if ($request->filled('submission_date_to')) {
            $query->where('created_at', '<=', $request->submission_date_to . ' 23:59:59');
        }

        // Filter by manuscript type (Outline o Final)
        if ($request->filled('type')) {
            $query->where('manuscript_type', $request->type);
        }

        // Filter by keywords (pwedeng multiple keywords separated by comma)
        if ($request->filled('keywords')) {
            $keywords = explode(',', $request->keywords);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    // Hanapin ang keyword sa keywords field
                    $q->orWhere('keywords', 'like', '%' . trim($keyword) . '%');
                }
            });
        }

        // I-order by last updated (pinaka-recent una) at kunin lahat
        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * Mag-export ng manuscripts bilang Excel file
     * Gagawa ng spreadsheet na may lahat ng manuscript data
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $manuscripts - Mga manuscript na ie-export
     * @param  \Illuminate\Http\Request  $request - Request na may filter info
     * @return \Illuminate\Http\Response - Excel file download
     */
    private function exportAsExcel($manuscripts, $request)
    {
        // Gumawa ng bagong spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // I-define ang mga column headers para sa Excel
        $headers = [
            'Reference Number',    // Reference number ng manuscript
            'Title',              // Title ng manuscript
            'Author',             // Pangalan ng author
            'Author Email',       // Email ng author
            'Department',         // Department ng scholar
            'Manuscript Type',    // Outline o Final
            'Status',            // Current status
            'Co-Authors',        // Mga co-authors kung meron
            'Keywords',          // Keywords ng manuscript
            'Submission Date',   // Kailan na-submit
            'Last Updated',      // Kailan huling na-update
            'Abstract',          // Abstract ng manuscript
            'Rejection Reason',  // Reason kung na-reject
            'Document Count'     // Ilang documents ang attached
        ];

        // I-write ang mga headers sa first row
        $colIndex = 0;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
        foreach ($headers as $header) {
            $sheet->setCellValue($columns[$colIndex] . '1', $header);
            $colIndex++;
        }

        // I-style ang headers (bold at may background color)
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A1:N1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // I-write ang data ng bawat manuscript
        $row = 2; // Magsisimula sa row 2 kasi row 1 ay headers
        foreach ($manuscripts as $manuscript) {
            // I-populate ang bawat column ng current row
            $sheet->setCellValue('A' . $row, $manuscript->reference_number ?? 'N/A');
            $sheet->setCellValue('B' . $row, $manuscript->title);
            $sheet->setCellValue('C' . $row, $manuscript->user ? $manuscript->user->name : 'Unknown');
            $sheet->setCellValue('D' . $row, $manuscript->user ? $manuscript->user->email : 'N/A');
            $sheet->setCellValue('E' . $row, $manuscript->scholarProfile ? $manuscript->scholarProfile->department : 'N/A');
            $sheet->setCellValue('F' . $row, $manuscript->manuscript_type);
            $sheet->setCellValue('G' . $row, $manuscript->status);
            $sheet->setCellValue('H' . $row, $manuscript->co_authors ?? 'N/A');
            $sheet->setCellValue('I' . $row, $manuscript->keywords ?? 'N/A');
            $sheet->setCellValue('J' . $row, $manuscript->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $manuscript->updated_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('L' . $row, Str::limit($manuscript->abstract, 500)); // Limit sa 500 characters
            $sheet->setCellValue('M' . $row, $manuscript->rejection_reason ?? 'N/A');
            $sheet->setCellValue('N' . $row, $manuscript->documents->count());
            $row++; // Lumipat sa susunod na row
        }

        // I-auto-size ang lahat ng columns para magfit ang content
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Gumawa ng filename na may timestamp at filters
        $filename = 'manuscripts_export_' . date('Y-m-d_H-i-s');
        // Idagdag sa filename ang mga filters na ginamit
        if ($request->filled('status')) {
            $filename .= '_status-' . Str::slug($request->status);
        }
        if ($request->filled('type')) {
            $filename .= '_type-' . Str::slug($request->type);
        }
        $filename .= '.xlsx';

        // I-save ang spreadsheet sa temporary file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'manuscript_export');
        $writer->save($tempFile);

        // I-log ang export action para sa audit trail
        $this->auditService->logCustomAction('exported_manuscripts_excel', 'Manuscript', null, [
            'filter_count' => $manuscripts->count(),
            'filters' => $request->except(['_token', 'format'])
        ]);

        // I-return ang file download response
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true); // I-delete ang temp file pagkatapos ma-download
    }

    /**
     * Mag-export ng manuscripts bilang ZIP file kasama ang mga documents
     * Gagawa ng ZIP na may organized na folder structure
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $manuscripts - Mga manuscript na ie-export
     * @param  \Illuminate\Http\Request  $request - Request parameters
     * @return \Illuminate\Http\Response - ZIP file download
     */
    private function exportAsZip($manuscripts, $request)
    {
        // Subukan ang iba't ibang paraan ng pag-gawa ng ZIP file
        // Una, tingnan kung available ang ZipArchive class (mas preferred)
        if (class_exists('ZipArchive')) {
            return $this->createZipWithZipArchive($manuscripts, $request);
        } else {
            // Kung walang ZipArchive, gamitin ang system command
            return $this->createZipWithSystemCommand($manuscripts, $request);
        }
    }

    /**
     * Gumawa ng ZIP file gamit ang ZipArchive class (kung available)
     * Ito ang preferred method kasi mas reliable
     */
    private function createZipWithZipArchive($manuscripts, $request)
    {
        // Gumawa ng unique na folder name base sa current date/time
        $dateFolder = date('Y-m-d_H-i-s');
        $zipFileName = 'manuscripts_batch_' . $dateFolder . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Siguraduhing may temp directory
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        // Gumawa ng bagong ZIP archive
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'Could not create ZIP file');
        }

        // Gumawa ng main folder sa loob ng ZIP
        $mainFolderName = 'All Manuscript Files';
        $zip->addEmptyDir($mainFolderName);

        // I-loop ang bawat manuscript at idagdag ang mga files nito
        foreach ($manuscripts as $manuscript) {
            // Kunin ang pangalan ng scholar (default: Unknown_Scholar)
            $scholarName = 'Unknown_Scholar';
            if ($manuscript->scholarProfile && $manuscript->scholarProfile->user) {
                $scholarName = $this->sanitizeFileName($manuscript->scholarProfile->user->name);
            }
            
            // I-sanitize ang manuscript title at type para sa filename
            $manuscriptTitle = $this->sanitizeFileName($manuscript->title);
            $manuscriptType = $this->sanitizeFileName($manuscript->manuscript_type);

            // I-loop ang bawat document ng manuscript
            foreach ($manuscript->documents as $document) {
                $sourcePath = storage_path('app/public/' . $document->file_path);
                
                // I-check kung existing ang file bago idagdag sa ZIP
                if (file_exists($sourcePath)) {
                    // Gumawa ng descriptive filename: Scholar_Title_Type_OriginalFilename
                    $newFileName = "{$scholarName}_{$manuscriptTitle}_{$manuscriptType}_" . $this->sanitizeFileName($document->file_name);
                    // Idagdag ang file sa ZIP
                    $zip->addFile($sourcePath, $mainFolderName . '/' . $newFileName);
                }
            }
        }

        // I-close ang ZIP file
        $zip->close();

        // I-log ang export action para sa audit trail
        $this->auditService->logCustomAction('exported_manuscripts_zip', 'Manuscript', null, [
            'filter_count' => $manuscripts->count(),
            'filters' => $request->except(['_token', 'format']),
            'structure' => 'single_folder'
        ]);

        // I-return ang ZIP file para ma-download
        return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Gumawa ng ZIP gamit ang system command (fallback method)
     * Ginagamit kapag walang ZipArchive class available
     */
    private function createZipWithSystemCommand($manuscripts, $request)
    {
        // Gumawa ng unique identifiers para sa batch
        $dateFolder = date('Y-m-d_H-i-s');
        $batchId = 'batch_' . $dateFolder . '_' . uniqid();
        $batchPath = storage_path('app/temp/' . $batchId);
        $mainFolderName = 'All Manuscript Files';
        $mainFolderPath = $batchPath . '/' . $mainFolderName;

        // Gumawa ng directory structure
        if (!file_exists($mainFolderPath)) {
            mkdir($mainFolderPath, 0755, true);
        }

        // I-copy ang lahat ng documents sa main folder na may bagong pangalan
        foreach ($manuscripts as $manuscript) {
            // Kunin ang scholar name
            $scholarName = 'Unknown_Scholar';
            if ($manuscript->scholarProfile && $manuscript->scholarProfile->user) {
                $scholarName = $this->sanitizeFileName($manuscript->scholarProfile->user->name);
            }
            $manuscriptTitle = $this->sanitizeFileName($manuscript->title);
            $manuscriptType = $this->sanitizeFileName($manuscript->manuscript_type);

            // I-copy ang bawat document
            foreach ($manuscript->documents as $document) {
                $sourcePath = storage_path('app/public/' . $document->file_path);
                if (file_exists($sourcePath)) {
                    // Gumawa ng descriptive filename
                    $newFileName = "{$scholarName}_{$manuscriptTitle}_{$manuscriptType}_" . $this->sanitizeFileName($document->file_name);
                    $destinationPath = $mainFolderPath . '/' . $newFileName;
                    // I-copy ang file sa bagong location
                    copy($sourcePath, $destinationPath);
                }
            }
        }

        // Subukan gumawa ng ZIP gamit ang system command
        $zipFileName = 'manuscripts_batch_' . $dateFolder . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // I-convert ang paths sa Windows format kung kailangan
        $windowsBatchPath = str_replace('/', '\\', $batchPath);
        $windowsZipPath = str_replace('/', '\\', $zipPath);
        $windowsMainFolder = str_replace('/', '\\', $mainFolderName);

        // Mag-prepare ng iba't ibang ZIP commands depende sa OS
        $zipCommands = [];
        if (PHP_OS_FAMILY === 'Windows') {
            // PowerShell command para sa Windows
            $zipCommands[] = "powershell -Command \"Compress-Archive -Path '{$windowsBatchPath}\\{$windowsMainFolder}' -DestinationPath '{$windowsZipPath}' -Force\"";
        }
        // Unix/Linux zip command (fallback)
        $zipCommands[] = "cd " . escapeshellarg($batchPath) . " && zip -r " . escapeshellarg($zipPath) . " " . escapeshellarg($mainFolderName);

        // Subukan ang bawat command hanggang may mag-succeed
        $zipCreated = false;
        foreach ($zipCommands as $command) {
            exec($command . " 2>&1", $output, $returnCode);
            // I-check kung successful ang command at nag-exist ang ZIP file
            if ($returnCode === 0 && file_exists($zipPath)) {
                $zipCreated = true;
                break;
            }
        }

        if ($zipCreated) {
            // Kung successful, i-delete ang temporary directory
            $this->deleteDirectory($batchPath);
            
            // I-log ang export action
            $this->auditService->logCustomAction('exported_manuscripts_zip', 'Manuscript', null, [
                'filter_count' => $manuscripts->count(), 
                'filters' => $request->except(['_token', 'format']), 
                'method' => 'system_command'
            ]);
            
            // I-return ang ZIP file para ma-download
            return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            // Kung hindi nag-succeed, i-delete ang temp directory at mag-error
            $this->deleteDirectory($batchPath);
            return back()->with('error', 'Could not create ZIP file using system commands.');
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
     * I-sanitize ang filename para safe gamitin sa file system
     * Tinatanggal ang mga special characters na pwedeng mag-cause ng problema
     *
     * @param  string  $filename - Original filename na i-sa-sanitize
     * @return string - Clean filename na safe gamitin
     */
    private function sanitizeFileName($filename)
    {
        // Tanggalin o palitan ang mga unsafe characters ng underscore
        $filename = preg_replace('/[^\pL\pN\s\-\._()]/u', '_', $filename);
        
        // I-convert sa basic ASCII characters (tanggalin ang mga accents, etc.)
        $filename = Str::ascii($filename);
        
        // Palitan ang mga spaces at dashes ng single underscore
        $filename = preg_replace('/[\s\-]+/', '_', $filename);
        
        // Tanggalin ang multiple underscores (gawing single underscore lang)
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Tanggalin ang underscores sa simula at dulo
        $filename = trim($filename, '_');
        
        // I-limit ang length sa 150 characters para hindi masyadong mahaba
        return substr($filename, 0, 150);
    }

    /**
     * I-delete ang directory kasama ang lahat ng contents nito (recursive)
     * Ginagamit para sa pag-clean up ng temporary directories
     *
     * @param  string  $dir - Path ng directory na ide-delete
     * @return bool - True kung successful, false kung hindi
     */
    private function deleteDirectory($dir)
    {
        // Kung hindi existing ang directory, consider na successful
        if (!file_exists($dir)) {
            return true;
        }

        // Kung file lang (hindi directory), i-delete lang directly
        if (!is_dir($dir)) {
            return unlink($dir);
        }

        // I-scan ang lahat ng contents ng directory
        foreach (scandir($dir) as $item) {
            // I-skip ang current (.) at parent (..) directory references
            if ($item == '.' || $item == '..') {
                continue;
            }

            // I-delete recursively ang bawat item sa directory
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false; // Kung may hindi ma-delete, return false
            }
        }

        // Pagkatapos ma-delete ang lahat ng contents, i-delete na ang directory mismo
        return rmdir($dir);
    }

    /**
     * Create a metadata file for the export (No longer used in ZIP, but kept for potential future use or other exports)
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
            'Department',
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
            $sheet->setCellValue('E' . $row, $manuscript->scholarProfile ? $manuscript->scholarProfile->department : 'N/A');
            $sheet->setCellValue('F' . $row, $manuscript->manuscript_type);
            $sheet->setCellValue('G' . $row, $manuscript->status);
            $sheet->setCellValue('H' . $row, $manuscript->co_authors ?? 'N/A');
            $sheet->setCellValue('I' . $row, $manuscript->keywords ?? 'N/A');
            $sheet->setCellValue('J' . $row, $manuscript->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $manuscript->updated_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('L' . $row, Str::limit($manuscript->abstract, 500));
            $sheet->setCellValue('M' . $row, $manuscript->rejection_reason ?? 'N/A');
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
     * Generate metadata content for a single manuscript (No longer used in ZIP, but kept for potential future use)
     */
    private function generateManuscriptMetadata($manuscript)
    {
        $metadata = "Manuscript Information\n";
        $metadata .= "========================\n\n";
        $metadata .= "Reference Number: " . ($manuscript->reference_number ?? 'N/A') . "\n";
        $metadata .= "Title: " . $manuscript->title . "\n";
        $metadata .= "Author: " . ($manuscript->user ? $manuscript->user->name : 'Unknown') . "\n";
        $metadata .= "Author Email: " . ($manuscript->user ? $manuscript->user->email : 'N/A') . "\n";
        $metadata .= "Department: " . ($manuscript->scholarProfile ? $manuscript->scholarProfile->department : 'N/A') . "\n";
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

        if ($manuscript->rejection_reason) {
            $metadata .= "REJECTION REASON\n";
            $metadata .= "================\n";
            $metadata .= $manuscript->rejection_reason . "\n\n";
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
     * I-format ang file size para maging human readable
     * I-convert ang bytes sa KB, MB, etc. depende sa laki
     *
     * @param  int  $size - File size sa bytes
     * @return string - Formatted file size (e.g., "2.5 MB", "150 KB")
     */
    private function formatFileSize($size)
    {
        // Kung 1MB or more, i-display sa MB
        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } 
        // Kung 1KB or more, i-display sa KB
        elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } 
        // Kung less than 1KB, i-display sa bytes
        else {
            return $size . ' bytes';
        }
    }

    /**
     * Ipakita ang form para sa pag-create ng bagong manuscript
     * Ginagamit ng admin para mag-create ng manuscript para sa scholar
     *
     * @return \Illuminate\Http\Response - Create manuscript form
     */
    public function create()
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // Kunin ang lahat ng scholars para sa dropdown
        $scholars = ScholarProfile::with('user')->get();
        
        // I-return ang create form na may list ng scholars
        return view('admin.manuscripts.create', compact('scholars'));
    }

    /**
     * I-save ang bagong manuscript sa database
     * Ginagamit kapag nag-submit ng create form ang admin
     *
     * @param  \Illuminate\Http\Request  $request - Form data na na-submit
     * @return \Illuminate\Http\Response - Redirect sa manuscript show page
     */
    public function store(Request $request)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // I-validate ang form data na na-submit
        $validated = $request->validate([
            'scholar_id' => 'required|exists:scholar_profiles,id',           // Dapat may scholar na selected
            'title' => 'required|string|max:255',                           // Title ay required
            'abstract' => 'required|string',                                // Abstract ay required
            'manuscript_type' => 'required|string|in:Outline,Final',        // Outline o Final lang
            'co_authors' => 'nullable|string|max:255',                      // Co-authors ay optional
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'rejection_reason' => 'nullable|string',                        // Rejection reason ay optional
            'file' => 'nullable|file|mimes:pdf|max:10240'                   // PDF file, max 10MB, optional
        ]);

        // Gumawa ng bagong manuscript record
        $manuscript = new Manuscript();
        $manuscript->scholar_profile_id = $validated['scholar_id'];
        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'] ?? null;
        $manuscript->status = $validated['status'];
        $manuscript->rejection_reason = $validated['rejection_reason'] ?? null;
        $manuscript->save(); // I-save muna para makuha ang ID

        // Gumawa ng reference number pagkatapos ma-save (kailangan ng ID)
        $manuscript->reference_number = 'MS-' . str_pad($manuscript->id, 5, '0', STR_PAD_LEFT);
        $manuscript->save(); // I-save ulit para ma-update ang reference number

        // I-handle ang file upload kung may na-upload na file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            // I-store ang file sa manuscripts folder
            $path = $file->store('manuscripts', 'public');

            // Gumawa ng document record para sa uploaded file
            $document = new Document([
                'scholar_profile_id' => $validated['scholar_id'],
                'title' => $manuscript->title . ' - Manuscript File',
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_type' => 'manuscript',
                'category' => 'manuscript',
                'status' => 'verified', // Admin uploaded, so automatically verified
            ]);

            // I-associate ang document sa manuscript
            $manuscript->documents()->save($document);
        }

        // I-log ang creation para sa audit trail
        $this->auditService->logCreate('Manuscript', $manuscript->id, $manuscript->toArray());

        // I-redirect sa show page ng bagong manuscript na may success message
        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript created successfully');
    }

    /**
     * Ipakita ang detalye ng specific manuscript
     * Makikita dito ang lahat ng info ng manuscript kasama ang mga documents
     *
     * @param  int  $id - ID ng manuscript na gusto makita
     * @return \Illuminate\Http\Response - Show page ng manuscript
     */
    public function show($id)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        // Kunin ang manuscript kasama ang related data
        $manuscript = Manuscript::with([
            'scholarProfile.user',  // Scholar info at user details
            'documents',           // Mga attached documents
            'reviewComments'       // Mga review comments kung meron
        ])->findOrFail($id);

        // I-return ang show view na may manuscript data
        return view('admin.manuscripts.show', compact('manuscript'));
    }

    /**
     * Ipakita ang edit form para sa manuscript
     * Pwedeng i-edit ng admin ang manuscript details
     *
     * @param  int  $id - ID ng manuscript na ie-edit
     * @return \Illuminate\Http\Response - Edit form
     */
    public function edit($id)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        // Kunin ang manuscript na ie-edit
        $manuscript = Manuscript::findOrFail($id);
        // Kunin din ang lahat ng scholars para sa dropdown (kung sakaling palitan ang scholar)
        $scholars = ScholarProfile::with('user')->get();

        // I-return ang edit form na may current manuscript data at scholars list
        return view('admin.manuscripts.edit', compact('manuscript', 'scholars'));
    }

    /**
     * I-update ang manuscript sa database
     * Ginagamit kapag nag-submit ng edit form ang admin
     *
     * @param  \Illuminate\Http\Request  $request - Updated form data
     * @param  int  $id - ID ng manuscript na ina-update
     * @return \Illuminate\Http\Response - Redirect sa show page
     */
    public function update(Request $request, $id)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        // Kunin ang manuscript na ina-update
        $manuscript = Manuscript::findOrFail($id);

        // I-validate ang updated form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',                           // Title ay required
            'abstract' => 'required|string',                                // Abstract ay required
            'manuscript_type' => 'required|string|in:Outline,Final',        // Outline o Final lang
            'co_authors' => 'nullable|string|max:255',                      // Co-authors ay optional
            'status' => 'sometimes|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'rejection_reason' => 'nullable|string'                         // Rejection reason ay optional
        ]);

        // I-save ang old values para sa audit logging
        $oldValues = $manuscript->toArray();

        // I-update ang manuscript fields
        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'];
        $manuscript->rejection_reason = $validated['rejection_reason'];

        // I-update ang status kung nandoon sa validated data
        if (isset($validated['status'])) {
            $manuscript->status = $validated['status'];
        }

        // I-save ang mga changes
        $manuscript->save();

        // I-log ang update para sa audit trail
        $this->auditService->logUpdate('Manuscript', $manuscript->id, $oldValues, $manuscript->toArray());

        // I-redirect sa show page na may success message
        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript updated successfully');
    }

    /**
     * I-delete ang manuscript sa database kasama ang mga associated files
     * Ginagamit kapag nag-click ng delete button ang admin
     *
     * @param  int  $id - ID ng manuscript na ide-delete
     * @return \Illuminate\Http\Response - Redirect sa index page
     */
    public function destroy($id)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        // Kunin ang manuscript na ide-delete
        $manuscript = Manuscript::findOrFail($id);
        // I-save ang old values para sa audit logging
        $oldValues = $manuscript->toArray();

        // I-delete muna ang lahat ng associated documents
        foreach ($manuscript->documents as $document) {
            // I-check kung existing ang file sa storage bago i-delete
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            // I-delete ang document record sa database
            $document->delete();
        }

        // I-delete ang manuscript record
        $manuscript->delete();

        // I-log ang deletion para sa audit trail
        $this->auditService->logDelete('Manuscript', $id, $oldValues);

        // I-redirect sa index page na may success message
        return redirect()->route('admin.manuscripts.index')
            ->with('success', 'Manuscript deleted successfully');
    }

    /**
     * I-download ang individual file mula sa batch export
     * Ginagamit kapag may manual download interface (fallback)
     *
     * @param  string  $batchId - ID ng batch export
     * @param  string  $file - Filename na ide-download
     * @return \Illuminate\Http\Response - File download response
     */
    public function batchDownloadFile($batchId, $file)
    {
        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // I-sanitize ang batch ID para iwas sa directory traversal attacks
        $batchId = preg_replace('/[^a-zA-Z0-9_-]/', '', $batchId);
        $batchPath = storage_path('app/temp/' . $batchId);

        // I-check kung existing ang batch directory
        if (!file_exists($batchPath)) {
            abort(404, 'Batch not found');
        }

        // I-construct ang file path
        $filePath = $batchPath . '/' . $file;

        // Security check: siguraduhing nasa loob ng batch directory ang file
        // (para iwas sa directory traversal attacks)
        $realBatchPath = realpath($batchPath);
        $realFilePath = realpath($filePath);

        if (!$realFilePath || strpos($realFilePath, $realBatchPath) !== 0) {
            abort(404, 'File not found');
        }

        // I-check kung existing ang file
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Kunin ang original filename para sa download
        $downloadName = basename($file);

        // I-determine ang content type ng file
        $contentType = $this->getContentType($filePath);

        // I-return ang file download response
        return Response::download($filePath, $downloadName, [
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Kunin ang content type ng file para sa proper download
     * Ginagamit para ma-set ang tamang MIME type sa browser
     *
     * @param  string  $filePath - Path ng file
     * @return string - MIME type ng file (e.g., "application/pdf", "image/jpeg")
     */
    private function getContentType($filePath)
    {
        // Gamitin ang finfo para ma-detect ang MIME type ng file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        return $mimeType;
    }

    /**
     * I-update lang ang status at notes ng manuscript at i-notify ang scholar
     * Ginagamit sa quick status update form sa show page
     *
     * @param  \Illuminate\Http\Request  $request - Form data na may status at notes
     * @param  int  $id - ID ng manuscript na ina-update
     * @return \Illuminate\Http\Response - Redirect sa show page
     */
    public function updateStatusAndNotes(Request $request, $id)
    {
        // Kunin ang manuscript na ina-update
        $manuscript = Manuscript::findOrFail($id);

        // I-check kung admin ba ang user
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }

        // I-validate ang form data
        $validated = $request->validate([
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'rejection_reason' => 'nullable|string',        // Rejection reason ay optional
            'notify_scholar' => 'sometimes|boolean'         // Checkbox para sa email notification
        ]);

        // I-save ang old values para sa comparison at audit
        $oldValues = $manuscript->toArray();
        $oldStatus = $manuscript->status;
        $newStatus = $validated['status'];

        // I-update ang manuscript status at rejection reason
        $manuscript->setAttribute('status', $newStatus);
        $manuscript->rejection_reason = $validated['rejection_reason'] ?? $manuscript->rejection_reason;
        $manuscript->save();

        // I-log ang status change para sa audit trail
        $this->auditService->logCustomAction("status_change_from_{$oldStatus}_to_{$newStatus}", 'Manuscript', $manuscript->id);

        // I-notify ang scholar kapag nag-change ang status
        if ($oldStatus !== $newStatus) {
            $scholarProfile = $manuscript->scholarProfile;
            if ($scholarProfile && $scholarProfile->user) {
                
                // Kung naka-check ang notify_scholar checkbox, mag-send ng email notification
                if ($request->has('notify_scholar') && $request->notify_scholar) {
                    // I-send ang Laravel notification (email + database notification)
                    $scholarProfile->user->notify(new \App\Notifications\ManuscriptStatusChanged(
                        $manuscript,
                        $oldStatus,
                        $newStatus,
                        $validated['rejection_reason'] ?? null
                    ));
                } else {
                    // Kung hindi naka-check ang email, gumawa pa rin ng in-app notification
                    $notificationService = app(\App\Services\NotificationService::class);
                    $title = 'Manuscript Status Updated';
                    $message = "Your manuscript \"{$manuscript->title}\" status has been changed from \"{$oldStatus}\" to \"{$newStatus}\".";

                    // Idagdag ang rejection reason kung meron
                    if ($validated['rejection_reason']) {
                        $message .= "\n\nRejection Reason: " . $validated['rejection_reason'];
                    }

                    // I-create ang custom notification para sa in-app display
                    $notificationService->notify(
                        $scholarProfile->user->id,
                        $title,
                        $message,
                        'manuscript',
                        route('scholar.manuscripts.show', $manuscript->id),
                        false // Hindi mag-send ng email dito kasi handled na sa taas
                    );
                }
            }
        }

        // I-set ang success message depende sa kung nag-change ba ang status
        $notificationMessage = $oldStatus !== $newStatus
            ? "Manuscript status updated successfully. Scholar has been notified of the status change."
            : "Manuscript updated successfully.";

        // I-redirect sa show page na may success message
        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', $notificationMessage);
    }
}
