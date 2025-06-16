<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryAchievement;
use Illuminate\Http\Request;

class HistoryAchievementController extends Controller
{
    public function index()
    {
        $achievements = HistoryAchievement::ordered()->get();
        return view('admin.history.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.history.achievements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:500',
            'statistic' => 'nullable|string|max:50',
            'statistic_label' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0'
        ]);

        HistoryAchievement::create($request->all());

        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    public function show(HistoryAchievement $historyAchievement)
    {
        return view('admin.history.achievements.show', compact('historyAchievement'));
    }

    public function edit(HistoryAchievement $historyAchievement)
    {
        return view('admin.history.achievements.edit', compact('historyAchievement'));
    }

    public function update(Request $request, HistoryAchievement $historyAchievement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:500',
            'statistic' => 'nullable|string|max:50',
            'statistic_label' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0'
        ]);

        $historyAchievement->update($request->all());

        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    public function destroy(HistoryAchievement $historyAchievement)
    {
        $historyAchievement->delete();

        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }

    public function toggleStatus(HistoryAchievement $historyAchievement)
    {
        $historyAchievement->update([
            'is_active' => !$historyAchievement->is_active
        ]);

        $status = $historyAchievement->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.history-achievements.index')
            ->with('success', "Achievement {$status} successfully.");
    }
}
