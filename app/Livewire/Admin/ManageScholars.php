<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\DB;

class ManageScholars extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $status = '';
    public $start_date_filter = '';
    public $search = '';
    public $perPage = 10;
    
    protected $queryString = [
        'status' => ['except' => ''],
        'start_date_filter' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    public function mount()
    {
        $this->status = request()->query('status', '');
        $this->start_date_filter = request()->query('start_date_filter', '');
        $this->search = request()->query('search', '');
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedStatus()
    {
        $this->resetPage();
    }
    
    public function updatedStartDateFilter()
    {
        $this->resetPage();
    }
    
    public function filter()
    {
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->reset(['status', 'start_date_filter', 'search']);
        $this->resetPage();
    }
    
    public function deleteScholar($id)
    {
        $scholar = ScholarProfile::findOrFail($id);
        $scholarName = $scholar->full_name;
        
        try {
            DB::beginTransaction();
            $scholar->delete();
            DB::commit();
            
            session()->flash('success', "Scholar <strong>{$scholarName}</strong> has been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete scholar. Please try again.');
        }
    }
    
    public function render()
    {
        $scholars = ScholarProfile::query()
            ->with('user')
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->start_date_filter, function ($query) {
                if (strpos($this->start_date_filter, '-') !== false) {
                    // Format is year-month (e.g., 2025-01)
                    list($year, $month) = explode('-', $this->start_date_filter);
                    return $query->whereYear('start_date', $year)
                                 ->whereMonth('start_date', $month);
                } else {
                    // If just a year is provided
                    return $query->whereYear('start_date', $this->start_date_filter);
                }
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('university', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('start_date', 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.admin.manage-scholars', [
            'scholars' => $scholars,
        ]);
    }
}
