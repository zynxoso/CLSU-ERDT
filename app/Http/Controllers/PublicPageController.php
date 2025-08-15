<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationTimeline;
use App\Models\ImportantNote;
use App\Models\FacultyMember;

use App\Models\HistoryAchievement;
use App\Models\HistoryContentBlock;
use App\Models\DownloadableForm;
use Illuminate\Support\Facades\Cache;

class PublicPageController extends Controller
{
    /**
     * Display the how-to-apply page with dynamic content.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function howToApply()
    {
        // Fetch active timeline items ordered by sort_order
        $timelines = ApplicationTimeline::active()->ordered()->get();

        // Fetch active important notes ordered by sort_order
        $importantNotes = ImportantNote::active()->ordered()->get();

        // Fetch active downloadable forms and group them by display headings expected by the view
        $forms = DownloadableForm::active()->ordered()->get();

        // Map internal category keys to display groups used in the view
        $categoryDisplayMap = [
            'application'   => 'Essential Application Forms',
            'scholarship'   => 'Essential Application Forms',
            'research'      => 'Research & Grant Forms',
            'academic'      => 'Research & Grant Forms',
            'administrative'=> 'Administrative & Monitoring Forms',
            'other'         => 'Administrative & Monitoring Forms',
        ];

        $grouped = $forms->groupBy(function ($form) use ($categoryDisplayMap) {
            return $categoryDisplayMap[$form->category] ?? 'Administrative & Monitoring Forms';
        });

        // Ensure all expected keys exist even if empty
        $expectedGroups = [
            'Essential Application Forms',
            'Research & Grant Forms',
            'Administrative & Monitoring Forms',
        ];

        $downloadableForms = collect($expectedGroups)
            ->mapWithKeys(fn ($g) => [$g => $grouped->get($g, collect())]);

        return view('how-to-apply', compact('timelines', 'importantNotes', 'downloadableForms'));
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about()
    {
        // Fetch active faculty members ordered by sort_order
        $facultyMembers = FacultyMember::active()->ordered()->get();

        return view('about', compact('facultyMembers'));
    }

    /**
     * Display the history page with dynamic content.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function history()
    {
        // Cache the history data for 1 hour to improve performance

        $achievements = Cache::remember('history_achievements', 3600, function() {
            return HistoryAchievement::active()->ordered()->get();
        });

        $heroContent = Cache::remember('history_hero_content', 3600, function() {
            return HistoryContentBlock::getSectionContent('hero');
        });

        $introContent = Cache::remember('history_intro_content', 3600, function() {
            return HistoryContentBlock::getSectionContent('introduction');
        });

        $visionContent = Cache::remember('history_vision_content', 3600, function() {
            return HistoryContentBlock::getSectionContent('vision');
        });

        $contactContent = Cache::remember('history_contact_content', 3600, function() {
            return HistoryContentBlock::getSectionContent('contact');
        });

        return view('history', compact(
            'achievements',
            'heroContent',
            'introContent',
            'visionContent',
            'contactContent'
        ));
    }

    /**
     * Display the faculty page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function faculty()
    {
        return view('faculty');
    }
}
