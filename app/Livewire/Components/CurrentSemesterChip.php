<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\SiteSetting;
use Livewire\Attributes\On;

class CurrentSemesterChip extends Component
{
    public $currentSemester;
    public $academicYear;
    public $isApplicationClosed = false;
    public $isManuallySet = false;
    
    public function getSemesterLabelProperty()
    {
        if ($this->currentSemester !== 'none') {
            return $this->getSemesterLabel() . ' AY ' . $this->academicYear;
        }
        return 'No Active Semester';
    }
    
    public function mount()
    {
        $this->loadSemesterData();
    }
    
    public function loadSemesterData()
    {
        // Get current semester
        $this->currentSemester = SiteSetting::getCurrentSemester();
        
        // Get academic year
        $this->academicYear = SiteSetting::get('academic_year', date('Y') . '–' . (date('Y') + 1));
        
        // Check if applications are closed (you can add this logic based on your requirements)
        $this->isApplicationClosed = $this->checkApplicationStatus();
        
        // Check if semester is manually set (you can add this logic if needed)
        $this->isManuallySet = SiteSetting::get('semester_manually_set', false);
    }
    
    private function checkApplicationStatus()
    {
        // Use the SiteSetting method to check if applications are open
        return !SiteSetting::areApplicationsOpen();
    }
    
    public function getSemesterLabel()
    {
        switch ($this->currentSemester) {
            case 'first':
                return '1st Semester';
            case 'second':
                return '2nd Semester';
            case 'summer':
                return 'Summer Term';
            default:
                return 'No Active Semester';
        }
    }
    
    public function getTooltipText()
    {
        $tooltip = "Current Academic Period: {$this->getSemesterLabel()} AY {$this->academicYear}";
        
        if ($this->isApplicationClosed) {
            $tooltip .= " • Applications Closed";
        }
        
        if ($this->isManuallySet) {
            $tooltip .= " • Manually Set by Admin";
        }
        
        return $tooltip;
    }
    
    #[On('semester-updated')]
    public function refreshSemester()
    {
        $this->loadSemesterData();
    }
    
    public function render()
    {
        return view('livewire.components.current-semester-chip');
    }
}