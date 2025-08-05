<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryContentBlock;
use Illuminate\Http\Request;

/**
 * HistoryContentController - Nag-manage ng mga content blocks para sa history page
 * 
 * Ginagamit ito ng mga admin para mag-edit ng mga content sa history page
 * tulad ng hero section, introduction, vision, at contact information
 */
class HistoryContentController extends Controller
{
    /**
     * Ipapakita ang lahat ng content blocks na naka-group by section
     * 
     * Ginagamit kapag pumunta ang admin sa history content management page
     * Para makita nila lahat ng existing content na pwedeng i-edit
     */
    public function index()
    {
        // Kunin lahat ng content blocks, i-order ayon sa sort_order, tapos i-group by section
        $contentBlocks = HistoryContentBlock::ordered()->get()->groupBy('section');
        
        // Ipakita ang admin view kasama ang mga content blocks
        return view('admin.history.content.index', compact('contentBlocks'));
    }

    /**
     * Ipapakita ang form para sa paglikha ng bagong content block
     * 
     * Ginagamit kapag nag-click ang admin ng "Add New Content" button
     * Para makagawa sila ng bagong content sa iba't ibang sections
     */
    public function create()
    {
        // Lista ng mga available sections na pwedeng paglagyan ng content
        $sections = [
            'hero' => 'Hero Section',           // Para sa main banner/header
            'introduction' => 'Introduction Section',  // Para sa introduction text
            'vision' => 'Vision Section',       // Para sa vision/mission content
            'contact' => 'Contact Section'      // Para sa contact information
        ];

        // Ipakita ang create form kasama ang mga section options
        return view('admin.history.content.create', compact('sections'));
    }

    /**
     * I-save ang bagong content block sa database
     * 
     * Ginagamit kapag nag-submit ang admin ng create form
     * Dito natin iche-check kung valid ang data bago i-save
     */
    public function store(Request $request)
    {
        // I-validate ang mga input fields para siguradong tama ang format
        $request->validate([
            'section' => 'required|string|max:100',        // Dapat may section (hero, intro, etc.)
            'key' => 'required|string|max:100',            // Unique identifier para sa content
            'value' => 'required|string',                  // Actual content/text
            'type' => 'required|in:text,html,image,url',   // Uri ng content (text, html, image, url)
            'sort_order' => 'required|integer|min:0'       // Para sa pag-order ng mga content
        ]);

        // I-check kung may existing na content block na may same section at key
        // Para hindi mag-duplicate ang mga content
        $exists = HistoryContentBlock::where('section', $request->section)
            ->where('key', $request->key)
            ->exists();

        // Kung may existing na, balik sa form with error message
        if ($exists) {
            return back()->withErrors(['key' => 'This key already exists for the selected section.']);
        }

        // Kung walang duplicate, i-create ang bagong content block
        HistoryContentBlock::create($request->all());

        // Balik sa index page with success message
        return redirect()->route('admin.history-content.index')
            ->with('success', 'Content block created successfully.');
    }

    /**
     * Ipapakita ang detalye ng isang specific content block
     * 
     * Ginagamit kapag nag-click ang admin sa "View" button
     * Para makita nila ang buong details ng content block
     */
    public function show(HistoryContentBlock $historyContent)
    {
        // Ipakita ang show view kasama ang selected content block
        return view('admin.history.content.show', compact('historyContent'));
    }

    /**
     * Ipapakita ang edit form para sa existing content block
     * 
     * Ginagamit kapag nag-click ang admin ng "Edit" button
     * Para ma-modify nila ang existing content
     */
    public function edit(HistoryContentBlock $historyContent)
    {
        // Same sections list para sa dropdown options
        $sections = [
            'hero' => 'Hero Section',
            'introduction' => 'Introduction Section',
            'vision' => 'Vision Section',
            'contact' => 'Contact Section'
        ];

        // Ipakita ang edit form kasama ang current content at section options
        return view('admin.history.content.edit', compact('historyContent', 'sections'));
    }

    /**
     * I-update ang existing content block sa database
     * 
     * Ginagamit kapag nag-submit ang admin ng edit form
     * Dito natin ina-update ang mga changes sa content
     */
    public function update(Request $request, HistoryContentBlock $historyContent)
    {
        // I-validate ang mga input fields (same validation rules sa store)
        $request->validate([
            'section' => 'required|string|max:100',
            'key' => 'required|string|max:100',
            'value' => 'required|string',
            'type' => 'required|in:text,html,image,url',
            'sort_order' => 'required|integer|min:0'
        ]);

        // I-check kung may ibang content block na may same section at key
        // Pero hindi kasama ang current record na ina-update natin
        $exists = HistoryContentBlock::where('section', $request->section)
            ->where('key', $request->key)
            ->where('id', '!=', $historyContent->id)  // Exclude current record
            ->exists();

        // Kung may conflict, balik sa form with error
        if ($exists) {
            return back()->withErrors(['key' => 'This key already exists for the selected section.']);
        }

        // I-update ang content block with new data
        $historyContent->update($request->all());

        // Balik sa index page with success message
        return redirect()->route('admin.history-content.index')
            ->with('success', 'Content block updated successfully.');
    }

    public function destroy(HistoryContentBlock $historyContent)
    {
        $historyContent->delete();

        return redirect()->route('admin.history-content.index')
            ->with('success', 'Content block deleted successfully.');
    }

    public function toggleStatus(HistoryContentBlock $historyContent)
    {
        $historyContent->update([
            'is_active' => !$historyContent->is_active
        ]);

        $status = $historyContent->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.history-content.index')
            ->with('success', "Content block {$status} successfully.");
    }
}
