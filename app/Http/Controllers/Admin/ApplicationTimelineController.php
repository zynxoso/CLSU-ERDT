<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationTimeline;
use Illuminate\Http\Request;

class ApplicationTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timelines = ApplicationTimeline::ordered()->get();
        return view('admin.application-timeline.index', compact('timelines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.application-timeline.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        ApplicationTimeline::create($request->all());

        return redirect()->route('admin.application-timeline.index')
            ->with('success', 'Timeline item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationTimeline $applicationTimeline)
    {
        return view('admin.application-timeline.show', compact('applicationTimeline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationTimeline $applicationTimeline)
    {
        return view('admin.application-timeline.edit', compact('applicationTimeline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplicationTimeline $applicationTimeline)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        $applicationTimeline->update($request->all());

        return redirect()->route('admin.application-timeline.index')
            ->with('success', 'Timeline item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationTimeline $applicationTimeline)
    {
        $applicationTimeline->delete();

        return redirect()->route('admin.application-timeline.index')
            ->with('success', 'Timeline item deleted successfully.');
    }

    /**
     * Toggle the active status of the timeline item.
     */
    public function toggleStatus(ApplicationTimeline $applicationTimeline)
    {
        $applicationTimeline->update([
            'is_active' => !$applicationTimeline->is_active
        ]);

        $status = $applicationTimeline->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.application-timeline.index')
            ->with('success', "Timeline item {$status} successfully.");
    }
}
