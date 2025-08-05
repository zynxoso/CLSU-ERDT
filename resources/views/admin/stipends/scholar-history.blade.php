@extends('layouts.app')

@section('title', 'Scholar Stipend History')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-5 border-b d-flex justify-content-between align-items-center" style="border-color: #E0E0E0;">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3" style="background-color: rgba(156, 39, 176, 0.1);">
                            <i class="fas fa-history" style="color: #9C27B0;"></i>
                        </div>
                        <h3 class="mb-0" style="color: #212121; font-size: 20px;">Stipend History - {{ $scholar->user->first_name }} {{ $scholar->user->last_name }}</h3>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.stipends.create', ['scholar_id' => $scholar->id]) }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #4CAF50; color: white;" onmouseover="this.style.backgroundColor='#43A047'" onmouseout="this.style.backgroundColor='#4CAF50'">
                            <i class="fas fa-plus mr-2"></i> New Disbursement
                        </a>
                        <a href="{{ route('admin.scholars.show', $scholar) }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #4A90E2; color: white;" onmouseover="this.style.backgroundColor='#357ABD'" onmouseout="this.style.backgroundColor='#4A90E2'">
                            <i class="fas fa-user mr-2"></i> Scholar Profile
                        </a>
                        <a href="{{ route('admin.stipends.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #757575; color: white;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Stipends
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Scholar Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $disbursements->count() }}</h4>
                                    <p class="mb-0">Total Disbursements</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">₱{{ number_format($totalAmount, 2) }}</h4>
                                    <p class="mb-0">Total Amount</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $processedCount }}</h4>
                                    <p class="mb-0">Processed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $pendingCount }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scholar Information -->
                    <div class="card bg-light mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Scholar Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $scholar->user->first_name }} {{ $scholar->user->last_name }}</p>
                                    <p><strong>Email:</strong> {{ $scholar->user->email }}</p>
                                    <p><strong>Intended Degree:</strong> 
                        <span class="badge bg-primary">{{ $scholar->intended_degree }}</span>
                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $scholar->status === 'Active' ? 'success' : 'secondary' }}">
                                            {{ $scholar->status }}
                                        </span>
                                    </p>
                                    <p><strong>Intended University:</strong> {{ $scholar->intended_university ?? 'N/A' }}</p>
                                <p><strong>Course:</strong> {{ $scholar->course ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.stipends.scholar-history', $scholar) }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="year" class="form-label">Year</label>
                                    <select name="year" id="year" class="form-select">
                                        <option value="">All Years</option>
                                        @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processed" {{ request('status') === 'Processed' ? 'selected' : '' }}>Processed</option>
                                        <option value="Failed" {{ request('status') === 'Failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="Regular" {{ request('type') === 'Regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="Adjustment" {{ request('type') === 'Adjustment' ? 'selected' : '' }}>Adjustment</option>
                                        <option value="Bonus" {{ request('type') === 'Bonus' ? 'selected' : '' }}>Bonus</option>
                                        <option value="Backpay" {{ request('type') === 'Backpay' ? 'selected' : '' }}>Backpay</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.stipends.scholar-history', $scholar) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Disbursements Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Processed</th>
                                    <th>Processed By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disbursements as $disbursement)
                                <tr>
                                    <td>
                                        <strong>{{ date('F Y', mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }}</strong>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">₱{{ number_format($disbursement->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $disbursement->disbursement_type }}</span>
                                    </td>
                                    <td>
                                        @if($disbursement->status === 'Pending')
                                        <span class="badge bg-warning">{{ $disbursement->status }}</span>
                                        @elseif($disbursement->status === 'Processed')
                                        <span class="badge bg-success">{{ $disbursement->status }}</span>
                                        @else
                                        <span class="badge bg-danger">{{ $disbursement->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $disbursement->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @if($disbursement->processed_at)
                                        <small>{{ $disbursement->processed_at->format('M d, Y g:i A') }}</small>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($disbursement->processedBy)
                                        <small>{{ $disbursement->processedBy->first_name }} {{ $disbursement->processedBy->last_name }}</small>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.stipends.show', $disbursement) }}" 
                                               class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.stipends.edit', $disbursement) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($disbursement->status === 'Pending')
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="processQuick({{ $disbursement->id }})" title="Quick Process">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No stipend disbursements found for this scholar.</p>
                                            <a href="{{ route('admin.stipends.create', ['scholar_id' => $scholar->id]) }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create First Disbursement
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($disbursements->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $disbursements->appends(request()->query())->links() }}
                    </div>
                    @endif
                    
                    <!-- Monthly Summary Chart -->
                    @if($disbursements->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Monthly Disbursement Summary</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyChart" height="100"></canvas>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Quick Process Modal -->
<div class="modal fade" id="quickProcessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quickProcessForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Quick Process Disbursement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Process this disbursement as successful?
                    </div>
                    <div class="mb-3">
                        <label for="quick_notes" class="form-label">Processing Notes (Optional)</label>
                        <textarea name="notes" id="quick_notes" class="form-control" rows="2" 
                                  placeholder="Optional notes..."></textarea>
                    </div>
                    <input type="hidden" name="status" value="Processed">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Quick process function
function processQuick(disbursementId) {
    const form = document.getElementById('quickProcessForm');
    form.action = `/admin/stipends/${disbursementId}/process`;
    
    const modal = new bootstrap.Modal(document.getElementById('quickProcessModal'));
    modal.show();
}

// Monthly chart
@if($disbursements->count() > 0)
const monthlyData = @json($monthlyData);

const ctx = document.getElementById('monthlyChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Amount (₱)',
            data: monthlyData.map(item => item.amount),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Amount: ₱' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
@endif
</script>
@endpush