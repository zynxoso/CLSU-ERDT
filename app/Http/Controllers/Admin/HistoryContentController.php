<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryContentBlock;
use Illuminate\Http\Request;

class HistoryContentController extends Controller
{
    public function index()
    {
        $contentBlocks = HistoryContentBlock::ordered()->get()->groupBy('section');
        return view('admin.history.content.index', compact('contentBlocks'));
    }

    public function create()
    {
        $sections = [
            'hero' => 'Hero Section',
            'introduction' => 'Introduction Section',
            'vision' => 'Vision Section',
            'contact' => 'Contact Section'
        ];

        return view('admin.history.content.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:100',
            'key' => 'required|string|max:100',
            'value' => 'required|string',
            'type' => 'required|in:text,html,image,url',
            'sort_order' => 'required|integer|min:0'
        ]);

        // Check for unique section-key combination
        $exists = HistoryContentBlock::where('section', $request->section)
            ->where('key', $request->key)
            ->exists();

        if ($exists) {
            return back()->withErrors(['key' => 'This key already exists for the selected section.']);
        }

        HistoryContentBlock::create($request->all());

        return redirect()->route('admin.history-content.index')
            ->with('success', 'Content block created successfully.');
    }

    public function show(HistoryContentBlock $historyContent)
    {
        return view('admin.history.content.show', compact('historyContent'));
    }

    public function edit(HistoryContentBlock $historyContent)
    {
        $sections = [
            'hero' => 'Hero Section',
            'introduction' => 'Introduction Section',
            'vision' => 'Vision Section',
            'contact' => 'Contact Section'
        ];

        return view('admin.history.content.edit', compact('historyContent', 'sections'));
    }

    public function update(Request $request, HistoryContentBlock $historyContent)
    {
        $request->validate([
            'section' => 'required|string|max:100',
            'key' => 'required|string|max:100',
            'value' => 'required|string',
            'type' => 'required|in:text,html,image,url',
            'sort_order' => 'required|integer|min:0'
        ]);

        // Check for unique section-key combination (excluding current record)
        $exists = HistoryContentBlock::where('section', $request->section)
            ->where('key', $request->key)
            ->where('id', '!=', $historyContent->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['key' => 'This key already exists for the selected section.']);
        }

        $historyContent->update($request->all());

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
