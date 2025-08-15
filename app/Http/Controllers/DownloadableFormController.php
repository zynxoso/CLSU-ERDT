<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DownloadableForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadableFormController extends Controller
{
    public function __construct()
    {
        // Route-level middleware handles auth/authorization.
        // Super admin routes are protected in routes/web.php by the SuperAdminMiddleware.
    }

    /**
     * Display a listing of downloadable forms
     */
    public function index(Request $request)
    {
        $query = DownloadableForm::with('uploader')
            ->when($request->category, function ($q) use ($request) {
                return $q->byCategory($request->category);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%')
                          ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            });

        if (Auth::check() && Auth::user()->role !== 'super_admin') {
            $query->active();
        } elseif (!Auth::check()) {
            $query->active();
        }

        $forms = $query->orderBy('sort_order')
                      ->orderBy('created_at', 'desc')
                      ->paginate(12);

        $categories = DownloadableForm::getCategories();

        return view('downloadable-forms.index', compact('forms', 'categories'));
    }

    /**
     * Show the form for creating a new downloadable form
     */
    public function create()
    {
        $categories = DownloadableForm::getCategories();
        return view('downloadable-forms.create', compact('categories'));
    }

    /**
     * Store a newly created downloadable form
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:' . implode(',', array_keys(DownloadableForm::getCategories())),
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Store file in public/forms directory
            $filePath = 'forms/' . $filename;
            $file->move(public_path('forms'), $filename);

            $form = DownloadableForm::create([
                'title' => $request->title,
                'description' => $request->description,
                'filename' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'category' => $request->category,
                'status' => $request->boolean('is_active', true),
                'sort_order' => $request->integer('sort_order', 0),
                'uploaded_by' => Auth::id() ?? null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Form uploaded successfully!',
                    'form' => $form
                ]);
            }

            return redirect()->route('downloadable-forms.index')
                           ->with('success', 'Form uploaded successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload form. Please try again.'
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to upload form. Please try again.')
                           ->withInput();
        }
    }

    /**
     * Display the specified downloadable form
     */
    public function show(DownloadableForm $downloadableForm)
    {
        $downloadableForm->load('uploader');
        return view('downloadable-forms.show', compact('downloadableForm'));
    }

    /**
     * Show the form for editing the specified downloadable form
     */
    public function edit(DownloadableForm $downloadableForm, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $downloadableForm->id,
                'title' => $downloadableForm->title,
                'description' => $downloadableForm->description,
                'category' => $downloadableForm->category,
                'sort_order' => $downloadableForm->sort_order,
                'is_active' => $downloadableForm->status,
                'file_path' => $downloadableForm->file_path,
                'filename' => $downloadableForm->filename
            ]);
        }
        
        $categories = DownloadableForm::getCategories();
        return view('downloadable-forms.edit', compact('downloadableForm', 'categories'));
    }

    /**
     * Update the specified downloadable form
     */
    public function update(Request $request, DownloadableForm $downloadableForm)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:' . implode(',', array_keys(DownloadableForm::getCategories())),
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'status' => $request->boolean('is_active', true),
                'sort_order' => $request->integer('sort_order', 0)
            ];

            // Handle file replacement
            if ($request->hasFile('file')) {
                // Delete old file
                if ($downloadableForm->fileExists()) {
                    unlink(public_path($downloadableForm->file_path));
                }

                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                $filePath = 'forms/' . $filename;
                $file->move(public_path('forms'), $filename);

                $data['filename'] = $originalName;
                $data['file_path'] = $filePath;
                $data['file_size'] = $file->getSize();
                $data['mime_type'] = $file->getMimeType();
            }

            $downloadableForm->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Form updated successfully!',
                    'form' => $downloadableForm->fresh()
                ]);
            }

            return redirect()->route('downloadable-forms.index')
                           ->with('success', 'Form updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update form. Please try again.'
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to update form. Please try again.')
                           ->withInput();
        }
    }

    /**
     * Remove the specified downloadable form
     */
    public function destroy(DownloadableForm $downloadableForm, Request $request)
    {
        try {
            // Delete file from storage
            if ($downloadableForm->fileExists()) {
                unlink(public_path($downloadableForm->file_path));
            }

            $downloadableForm->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Form deleted successfully!'
                ]);
            }

            return redirect()->route('downloadable-forms.index')
                           ->with('success', 'Form deleted successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete form. Please try again.'
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to delete form. Please try again.');
        }
    }

    /**
     * Download the specified form
     */
    public function download(DownloadableForm $downloadableForm)
    {
        if (!$downloadableForm->status) {
            abort(404);
        }

        if (!$downloadableForm->fileExists()) {
            return redirect()->back()
                           ->with('error', 'File not found.');
        }

        // Increment download count
        $downloadableForm->incrementDownloadCount();

        $filePath = public_path($downloadableForm->file_path);
        
        return response()->download($filePath, $downloadableForm->filename);
    }

    /**
     * Toggle form status
     */
    public function toggleStatus(DownloadableForm $downloadableForm, Request $request)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$downloadableForm->status;
        
        $downloadableForm->update([
            'status' => $newStatus
        ]);

        $status = $downloadableForm->fresh()->status ? 'activated' : 'deactivated';
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Form {$status} successfully!",
                'status' => $downloadableForm->status
            ]);
        }
        
        return redirect()->back()
                       ->with('success', "Form {$status} successfully!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'forms' => 'required|array|min:1',
            'forms.*' => 'exists:downloadable_forms,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator);
        }

        try {
            $forms = DownloadableForm::whereIn('id', $request->forms)->get();
            $count = $forms->count();

            switch ($request->action) {
                case 'activate':
                    DownloadableForm::whereIn('id', $request->forms)->update(['status' => true]);
                    $message = "{$count} form(s) activated successfully!";
                    break;
                    
                case 'deactivate':
                    DownloadableForm::whereIn('id', $request->forms)->update(['status' => false]);
                    $message = "{$count} form(s) deactivated successfully!";
                    break;
                    
                case 'delete':
                    foreach ($forms as $form) {
                        if ($form->fileExists()) {
                            unlink(public_path($form->file_path));
                        }
                        $form->delete();
                    }
                    $message = "{$count} form(s) deleted successfully!";
                    break;
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Bulk action failed. Please try again.');
        }
    }
}