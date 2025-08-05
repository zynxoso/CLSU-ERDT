<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryAchievement;
use Illuminate\Http\Request;

class HistoryAchievementController extends Controller
{
    // Nagdidisplay ng lahat ng achievements sa admin page
    // Ginagamit ang ordered() para sa tamang pagkakaayos
    public function index()
    {
        // Kunin lahat ng achievements na naka-order na
        $achievements = HistoryAchievement::ordered()->get();
        
        // Ipakita sa admin view kasama ang data
        return view('admin.history.achievements.index', compact('achievements'));
    }

    // Nagpapakita ng form para sa pagcreate ng bagong achievement
    public function create()
    {
        return view('admin.history.achievements.create');
    }

    // Nagsasave ng bagong achievement sa database
    public function store(Request $request)
    {
        // Tinetest kung tama ang mga input na data
        $request->validate([
            'title' => 'required|string|max:255',           // Title - required, max 255 chars
            'description' => 'required|string',             // Description - required
            'icon' => 'nullable|string|max:500',            // Icon - optional, max 500 chars
            'statistic' => 'nullable|string|max:50',        // Statistic number - optional
            'statistic_label' => 'nullable|string|max:100', // Label ng statistic - optional
            'color' => 'required|string|max:50',            // Color - required
            'sort_order' => 'required|integer|min:0'        // Order sa pagdisplay - required
        ]);

        // Gumawa ng bagong achievement record
        HistoryAchievement::create($request->all());

        // Bumalik sa index page with success message
        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    // Nagpapakita ng detalye ng isang achievement
    public function show(HistoryAchievement $historyAchievement)
    {
        return view('admin.history.achievements.show', compact('historyAchievement'));
    }

    // Nagpapakita ng form para sa pag-edit ng achievement
    public function edit(HistoryAchievement $historyAchievement)
    {
        return view('admin.history.achievements.edit', compact('historyAchievement'));
    }

    // Nag-uupdate ng existing achievement sa database
    public function update(Request $request, HistoryAchievement $historyAchievement)
    {
        // Tinetest ulit kung tama ang mga input data (same rules sa store)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:500',
            'statistic' => 'nullable|string|max:50',
            'statistic_label' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0'
        ]);

        // I-update ang achievement record with new data
        $historyAchievement->update($request->all());

        // Bumalik sa index page with success message
        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    // Nagdedelete ng achievement sa database
    public function destroy(HistoryAchievement $historyAchievement)
    {
        // Tanggalin ang achievement record
        $historyAchievement->delete();

        // Bumalik sa index page with success message
        return redirect()->route('admin.history-achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }

    // Nag-oopen/close ng achievement (active/inactive)
    public function toggleStatus(HistoryAchievement $historyAchievement)
    {
        // Baligtarin ang current status (kung active, gawing inactive at vice versa)
        $historyAchievement->update([
            'is_active' => !$historyAchievement->is_active
        ]);

        // Kunin ang bagong status para sa message
        $status = $historyAchievement->is_active ? 'activated' : 'deactivated';

        // Bumalik sa index page with status message
        return redirect()->route('admin.history-achievements.index')
            ->with('success', "Achievement {$status} successfully.");
    }
}
