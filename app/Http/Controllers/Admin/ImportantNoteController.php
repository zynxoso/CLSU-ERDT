<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantNote;
use Illuminate\Http\Request;

/**
 * Controller para sa pag-manage ng mga Important Notes
 * Ginagamit ito ng mga admin para mag-add, edit, delete ng mga mahalagang paalala
 */
class ImportantNoteController extends Controller
{
    /**
     * Ipapakita ang listahan ng lahat ng important notes
     * Ginagamit ito kapag pumunta ang admin sa important notes page
     */
    public function index()
    {
        // Kunin lahat ng notes na naka-sort na ayon sa order
        $notes = ImportantNote::ordered()->get();
        
        // Ipakita ang index page kasama ang mga notes
        return view('admin.important-notes.index', compact('notes'));
    }

    /**
     * Ipapakita ang form para sa pag-create ng bagong important note
     * Ginagamit ito kapag nag-click ang admin sa "Add New Note" button
     */
    public function create()
    {
        // Ipakita ang create form
        return view('admin.important-notes.create');
    }

    /**
     * I-save ang bagong important note sa database
     * Ginagamit ito kapag nag-submit ang admin ng create form
     */
    public function store(Request $request)
    {
        // I-validate muna ang mga input bago i-save
        $request->validate([
            'title' => 'required|string|max:255',        // Title ay required, string, max 255 characters
            'content' => 'required|string',              // Content ay required at string
            'type' => 'required|in:main,submission,deadline', // Type ay required at dapat isa sa tatlong options
            'sort_order' => 'required|integer|min:0',    // Sort order ay required number, hindi pwedeng negative
        ]);

        // I-create ang bagong important note gamit ang mga input
        ImportantNote::create($request->all());

        // Bumalik sa index page at ipakita ang success message
        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note created successfully.');
    }

    /**
     * Ipapakita ang detalye ng isang specific na important note
     * Ginagamit ito kapag nag-click ang admin sa "View" button
     */
    public function show(ImportantNote $importantNote)
    {
        // Ipakita ang show page kasama ang selected note
        return view('admin.important-notes.show', compact('importantNote'));
    }

    /**
     * Ipapakita ang edit form para sa isang specific na important note
     * Ginagamit ito kapag nag-click ang admin sa "Edit" button
     */
    public function edit(ImportantNote $importantNote)
    {
        // Ipakita ang edit form na may laman na ng existing data
        return view('admin.important-notes.edit', compact('importantNote'));
    }

    /**
     * I-update ang existing important note sa database
     * Ginagamit ito kapag nag-submit ang admin ng edit form
     */
    public function update(Request $request, ImportantNote $importantNote)
    {
        // I-validate muna ang mga input bago i-update
        $request->validate([
            'title' => 'required|string|max:255',        // Title ay required, string, max 255 characters
            'content' => 'required|string',              // Content ay required at string
            'type' => 'required|in:main,submission,deadline', // Type ay required at dapat isa sa tatlong options
            'sort_order' => 'required|integer|min:0',    // Sort order ay required number, hindi pwedeng negative
        ]);

        // I-update ang important note gamit ang mga bagong input
        $importantNote->update($request->all());

        // Bumalik sa index page at ipakita ang success message
        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note updated successfully.');
    }

    /**
     * I-delete ang important note sa database
     * Ginagamit ito kapag nag-click ang admin sa "Delete" button at nag-confirm
     */
    public function destroy(ImportantNote $importantNote)
    {
        // I-delete ang important note sa database
        $importantNote->delete();

        // Bumalik sa index page at ipakita ang success message
        return redirect()->route('admin.important-notes.index')
            ->with('success', 'Important note deleted successfully.');
    }

    /**
     * I-toggle ang status ng important note (active/inactive)
     * Ginagamit ito kapag nag-click ang admin sa toggle switch o activate/deactivate button
     */
    public function toggleStatus(ImportantNote $importantNote)
    {
        // I-update ang is_active status - kung active, gawing inactive at vice versa
        $importantNote->update([
            'is_active' => !$importantNote->is_active
        ]);

        // I-set ang message depende sa bagong status
        $status = $importantNote->is_active ? 'activated' : 'deactivated';

        // Bumalik sa index page at ipakita ang success message
        return redirect()->route('admin.important-notes.index')
            ->with('success', "Important note {$status} successfully.");
    }
}
