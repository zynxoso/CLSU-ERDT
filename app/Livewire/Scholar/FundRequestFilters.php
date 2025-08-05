<?php

declare(strict_types=1);

namespace App\Livewire\Scholar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FundRequest;
use App\Models\RequestType;
use Illuminate\Support\Facades\Auth;

class FundRequestFilters extends Component
{
    use WithPagination;

    public $status = '';
    public $purpose = '';
    public $date = '';
    public $perPage = 10;

    protected $queryString = [
        'status' => ['except' => ''],
        'purpose' => ['except' => ''],
        'date' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->status = request()->query('status', '');
        $this->purpose = request()->query('purpose', '');
        $this->date = request()->query('date', '');
    }

    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function render()
    {
        $scholarId = Auth::user()->scholarProfile->id;

        $fundRequests = FundRequest::where('scholar_profile_id', $scholarId)
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->purpose, function ($query) {
                $query->where('request_type_id', $this->purpose);
            })
            ->when($this->date, function ($query) {
                $query->whereDate('created_at', $this->date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $requestTypes = RequestType::all();

        // Calculate statistics for summary cards
        $totalRequested = FundRequest::where('scholar_profile_id', $scholarId)->count();
        $approved = FundRequest::where('scholar_profile_id', $scholarId)->where('status', FundRequest::STATUS_APPROVED)->count();
        $pending = FundRequest::where('scholar_profile_id', $scholarId)->whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->count();
        $rejected = FundRequest::where('scholar_profile_id', $scholarId)->where('status', FundRequest::STATUS_REJECTED)->count();

        return view('livewire.scholar.fund-request-filters', [
            'fundRequests' => $fundRequests,
            'requestTypes' => $requestTypes,
            'totalRequested' => $totalRequested,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
        ]);
    }

    public function clearFilters()
    {
        $this->status = '';
        $this->purpose = '';
        $this->date = '';
        $this->resetPage();
    }
}
