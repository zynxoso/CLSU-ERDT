<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantNote;
use Illuminate\Http\Request;

class ImportantNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = ImportantNote::ordered()->get();
        return view('admin.important-notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.important-notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:main,submission,deadline',
            'sort_order' => 'required|integer|min:0',
        ]);

        ImportantNote::create($request->all());

        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImportantNote $importantNote)
    {
        return view('admin.important-notes.show', compact('importantNote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImportantNote $importantNote)
    {
        return view('admin.important-notes.edit', compact('importantNote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportantNote $importantNote)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:main,submission,deadline',
            'sort_order' => 'required|integer|min:0',
        ]);

        $importantNote->update($request->all());

        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportantNote $importantNote)
    {
        $importantNote->delete();

        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note deleted successfully.');
    }

    /**
     * Toggle the active status of the important note.
     */
    public function toggleStatus(ImportantNote $importantNote)
    {
        $importantNote->update([
            'is_active' => !$importantNote->is_active
        ]);

        $status = $importantNote->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.important-notes.index')
            ->with('success', "Important note {$status} successfully.");
    }
}
