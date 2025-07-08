<?php

namespace App\Livewire\Scholar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FundRequest;
use App\Models\RequestType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FundRequestsList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $status = '';
    public $date = '';
    public $search = '';
    public $perPage = 10;

    // Status card totals
    public $totalRequested = 0;
    public $totalApproved = 0;
    public $totalPending = 0;
    public $totalRejected = 0;

    protected $queryString = [
        'status' => ['except' => ''],
        'date' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->status = request()->query('status', '');
        $this->date = request()->query('date', '');
        $this->search = request()->query('search', '');

        $this->calculateStatusTotals();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['status', 'date', 'search']);
        $this->resetPage();
    }

    public function deleteFundRequest($requestId)
    {
        $user = Auth::user();

        if ($user->role !== 'scholar') {
            session()->flash('error', 'Unauthorized access');
            return;
        }

        $fundRequest = FundRequest::findOrFail($requestId);

        // Check if user owns the request
        if ($fundRequest->scholar_profile_id !== $user->scholarProfile->id) {
            session()->flash('error', 'Unauthorized access');
            return;
        }

        // Only pending requests can be deleted
        if ($fundRequest->status !== 'Pending') {
            session()->flash('error', 'Only pending requests can be cancelled');
            return;
        }

        $fundRequest->delete();

        // Recalculate totals after deletion
        $this->calculateStatusTotals();

        session()->flash('success', 'Fund request cancelled successfully');
    }

    public function getStatusUpdates()
    {
        $user = Auth::user();

        if ($user->role !== 'scholar') {
            return [];
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return [];
        }

        // Get all fund requests for this scholar with their latest status
        $fundRequests = FundRequest::where('scholar_profile_id', $scholarProfile->id)
            ->with(['statusHistory' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        $updates = [];
        foreach ($fundRequests as $request) {
            $updates[] = [
                'request_id' => $request->id,
                'status' => $request->status,
                'status_history' => $request->statusHistory->map(function($history) {
                    return [
                        'status' => $history->status,
                        'created_at' => $history->created_at->toISOString(),
                    ];
                }),
            ];
        }

        return $updates;
    }

    private function calculateStatusTotals()
    {
        $user = Auth::user();

        if ($user->role !== 'scholar' || !$user->scholarProfile) {
            return;
        }

        $scholarProfile = $user->scholarProfile;

        // Calculate totals by status
        $totals = FundRequest::where('scholar_profile_id', $scholarProfile->id)
            ->select('status', DB::raw('SUM(amount) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $this->totalRequested = FundRequest::where('scholar_profile_id', $scholarProfile->id)->sum('amount');
        $this->totalApproved = $totals['Approved'] ?? 0;
        $this->totalPending = $totals['Pending'] ?? 0;
        $this->totalRejected = $totals['Rejected'] ?? 0;
    }

    public function render()
    {
        $user = Auth::user();

        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        $query = FundRequest::where('scholar_profile_id', $scholarProfile->id)
            ->with(['requestType']);

        // Apply filters
        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->date) {
            $query->whereYear('created_at', substr($this->date, 0, 4))
                  ->whereMonth('created_at', substr($this->date, 5, 2));
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('amount', 'like', '%' . $this->search . '%')
                  ->orWhere('purpose', 'like', '%' . $this->search . '%')
                  ->orWhereHas('requestType', function($typeQuery) {
                      $typeQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $fundRequests = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.scholar.fund-requests-list', [
            'fundRequests' => $fundRequests,
        ]);
    }
}
