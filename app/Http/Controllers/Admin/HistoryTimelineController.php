<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryTimelineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class HistoryTimelineController extends Controller
{
    public function index()
    {
        $timelineItems = HistoryTimelineItem::ordered()->get();
        return view('admin.history.timeline.index', compact('timelineItems'));
    }

    public function create()
    {
        return view('admin.history.timeline.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'year_label' => 'nullable|string|max:20',
            'category' => 'required|in:milestone,achievement,partnership',
            'icon' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('history/timeline', 'public');
            $data['image_path'] = $imagePath;
        }

        HistoryTimelineItem::create($data);

        // Clear history cache
        Artisan::call('history:clear-cache');

        return redirect()->route('admin.history-timeline.index')
            ->with('success', 'Timeline item created successfully.');
    }

    public function show(HistoryTimelineItem $historyTimeline)
    {
        return view('admin.history.timeline.show', compact('historyTimeline'));
    }

    public function edit(HistoryTimelineItem $historyTimeline)
    {
        return view('admin.history.timeline.edit', compact('historyTimeline'));
    }

    public function update(Request $request, HistoryTimelineItem $historyTimeline)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'year_label' => 'nullable|string|max:20',
            'category' => 'required|in:milestone,achievement,partnership',
            'icon' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($historyTimeline->image_path) {
                Storage::disk('public')->delete($historyTimeline->image_path);
            }

            $imagePath = $request->file('image')->store('history/timeline', 'public');
            $data['image_path'] = $imagePath;
        }

        $historyTimeline->update($data);

        // Clear history cache
        Artisan::call('history:clear-cache');

        return redirect()->route('admin.history-timeline.index')
            ->with('success', 'Timeline item updated successfully.');
    }

    public function destroy(HistoryTimelineItem $historyTimeline)
    {
        // Delete associated image
        if ($historyTimeline->image_path) {
            Storage::disk('public')->delete($historyTimeline->image_path);
        }

        $historyTimeline->delete();

        // Clear history cache
        Artisan::call('history:clear-cache');

        return redirect()->route('admin.history-timeline.index')
            ->with('success', 'Timeline item deleted successfully.');
    }

    public function toggleStatus(HistoryTimelineItem $historyTimeline)
    {
        $historyTimeline->update([
            'is_active' => !$historyTimeline->is_active
        ]);

        // Clear history cache
        Artisan::call('history:clear-cache');

        $status = $historyTimeline->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.history-timeline.index')
            ->with('success', "Timeline item {$status} successfully.");
    }
}
 