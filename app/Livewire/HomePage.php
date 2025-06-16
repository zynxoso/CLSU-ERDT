<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Announcement;

class HomePage extends Component
{
    public function render()
    {
        // Get latest announcements for the home page
        $announcements = Announcement::active()
            ->published()
            ->orderByPriority()
            ->limit(4)
            ->get();

        return view('livewire.home-page', [
            'announcements' => $announcements
        ]);
    }
}
