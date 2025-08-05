@extends('layouts.app')

@section('title', 'Stipend Disbursement Details')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-5 border-b d-flex justify-content-between align-items-center" style="border-color: #E0E0E0;">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3" style="background-color: rgba(74, 144, 226, 0.1);">
                            <i class="fas fa-money-bill-wave" style="color: #4A90E2;"></i>
                        </div>
                        <h3 class="mb-0" style="color: #212121; font-size: 20px;">Stipend Disbursement Details</h3>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #FFCA28; color: white;" onmouseover="this.style.backgroundColor='#FFB300'" onmouseout="this.style.backgroundColor='#FFCA28'" data-bs-toggle="modal" data-bs-target="#notifyModal">
                            <i class="fas fa-bell mr-2"></i> Notify Scholar
                        </button>
                        @if($disbursement->status === 'Pending')
                        <button type="button" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #4CAF50; color: white;" onmouseover="this.style.backgroundColor='#43A047'" onmouseout="this.style.backgroundColor='#4CAF50'" data-bs-toggle="modal" data-bs-target="#processModal">
                            <i class="fas fa-check mr-2"></i> Process
                        </button>
                        @endif
                        <a href="{{ route('admin.stipends.edit', $disbursement) }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #FF9800; color: white;" onmouseover="this.style.backgroundColor='#F57C00'" onmouseout="this.style.backgroundColor='#FF9800'">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.stipends.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-sm" style="background-color: #757575; color: white;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="row">
                        <!-- Disbursement Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Disbursement Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td>{{ $disbursement->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Period:</strong></td>
                                            <td>{{ date('F Y', mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Amount:</strong></td>
                                            <td class="fw-bold text-success">₱{{ number_format($disbursement->amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ $disbursement->disbursement_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($disbursement->status === 'Pending')
                                                <span class="badge bg-warning">{{ $disbursement->status }}</span>
                                                @elseif($disbursement->status === 'Processed')
                                                <span class="badge bg-success">{{ $disbursement->status }}</span>
                                                @else
                                                <span class="badge bg-danger">{{ $disbursement->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $disbursement->created_at->format('M d, Y g:i A') }}</td>
                                        </tr>
                                        @if($disbursement->processed_at)
                                        <tr>
                                            <td><strong>Processed:</strong></td>
                                            <td>{{ $disbursement->processed_at->format('M d, Y g:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Scholar Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Scholar Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>
                                                <a href="{{ route('admin.scholars.show', $disbursement->scholarProfile) }}" class="text-decoration-none">
                                                    {{ $disbursement->scholarProfile->user->first_name }} {{ $disbursement->scholarProfile->user->last_name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $disbursement->scholarProfile->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Intended Degree:</strong></td>
                                            <td>
                                                <span class="badge bg-primary">{{ $disbursement->scholarProfile->intended_degree }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $disbursement->scholarProfile->status === 'Active' ? 'success' : 'secondary' }}">
                                                    {{ $disbursement->scholarProfile->status }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>University:</strong></td>
                                            <td>{{ $disbursement->scholarProfile->intended_university ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                        <td>{{ $disbursement->scholarProfile->department ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Processing Information -->
                    @if($disbursement->processed_by || $disbursement->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Processing Information</h5>
                                </div>
                                <div class="card-body">
                                    @if($disbursement->processedBy)
                                    <div class="mb-3">
                                        <strong>Processed By:</strong>
                                        {{ $disbursement->processedBy->first_name }} {{ $disbursement->processedBy->last_name }}
                                        <small class="text-muted">({{ $disbursement->processedBy->email }})</small>
                                    </div>
                                    @endif
                                    
                                    @if($disbursement->notes)
                                    <div class="mb-3">
                                        <strong>Notes:</strong>
                                        <div class="mt-2 p-3 bg-light rounded">
                                            {{ $disbursement->notes }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Recent Disbursements for this Scholar -->
                    @if($recentDisbursements->count() > 1)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Disbursements for this Scholar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Period</th>
                                                    <th>Amount</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Processed</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentDisbursements as $recent)
                                                <tr class="{{ $recent->id === $disbursement->id ? 'table-warning' : '' }}">
                                                    <td>{{ date('M Y', mktime(0, 0, 0, $recent->disbursement_month, 1, $recent->disbursement_year)) }}</td>
                                                    <td>₱{{ number_format($recent->amount, 2) }}</td>
                                                    <td><span class="badge bg-info">{{ $recent->disbursement_type }}</span></td>
                                                    <td>
                                                        @if($recent->status === 'Pending')
                                                        <span class="badge bg-warning">{{ $recent->status }}</span>
                                                        @elseif($recent->status === 'Processed')
                                                        <span class="badge bg-success">{{ $recent->status }}</span>
                                                        @else
                                                        <span class="badge bg-danger">{{ $recent->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $recent->processed_at ? $recent->processed_at->format('M d, Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if($recent->id !== $disbursement->id)
                                                        <a href="{{ route('admin.stipends.show', $recent) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @else
                                                        <span class="badge bg-secondary">Current</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Modal -->
@if($disbursement->status === 'Pending')
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.stipends.process', $disbursement) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Process Stipend Disbursement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        You are about to process the stipend disbursement for 
                        <strong>{{ $disbursement->scholarProfile->user->first_name }} {{ $disbursement->scholarProfile->user->last_name }}</strong>
                        for <strong>{{ date('F Y', mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }}</strong>
                        in the amount of <strong>₱{{ number_format($disbursement->amount, 2) }}</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label for="process_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="process_status" class="form-select" required>
                            <option value="Processed">Processed (Success)</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="process_notes" class="form-label">Processing Notes</label>
                        <textarea name="notes" id="process_notes" class="form-control" rows="3" 
                                  placeholder="Optional notes about the processing...">{{ $disbursement->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Process Disbursement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Notify Scholar Modal -->
<div class="modal fade" id="notifyModal" tabindex="-1" aria-labelledby="notifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifyModalLabel">Send Notification to Scholar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="notifyForm" action="{{ route('admin.stipends.notify', $disbursement) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notificationType" class="form-label">Notification Type</label>
                        <select class="form-select" id="notificationType" name="type" required>
                            <option value="">Select notification type...</option>
                            <option value="disbursement_ready">Disbursement Ready</option>
                            <option value="status_update">Status Update</option>
                            <option value="reminder">Reminder</option>
                            <option value="custom">Custom Message</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="customMessageDiv" style="display: none;">
                        <label for="customMessage" class="form-label">Custom Message</label>
                        <textarea class="form-control" id="customMessage" name="message" rows="4" placeholder="Enter your custom message..."></textarea>
                    </div>
                    
                    <div class="alert alert-info" id="messagePreview" style="display: none;">
                        <strong>Message Preview:</strong>
                        <div id="previewContent"></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Scholar:</strong> {{ $disbursement->scholarProfile->user->first_name }} {{ $disbursement->scholarProfile->user->last_name }}<br>
                        <strong>Amount:</strong> ₱{{ number_format($disbursement->amount, 2) }}<br>
                        <strong>Period:</strong> {{ date('F Y', mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }}<br>
                        <strong>Status:</strong> <span class="badge bg-{{ $disbursement->status === 'Processed' ? 'success' : ($disbursement->status === 'Pending' ? 'warning' : 'secondary') }}">{{ $disbursement->status }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendNotificationBtn" disabled>
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function processStipend() {
        document.getElementById('processForm').submit();
    }
    
    // Notification modal functionality
    document.getElementById('notificationType').addEventListener('change', function() {
        const type = this.value;
        const customMessageDiv = document.getElementById('customMessageDiv');
        const messagePreview = document.getElementById('messagePreview');
        const previewContent = document.getElementById('previewContent');
        const sendBtn = document.getElementById('sendNotificationBtn');
        
        if (type === 'custom') {
            customMessageDiv.style.display = 'block';
            messagePreview.style.display = 'none';
            sendBtn.disabled = true;
        } else if (type) {
            customMessageDiv.style.display = 'none';
            messagePreview.style.display = 'block';
            sendBtn.disabled = false;
            
            // Generate preview based on type
            let preview = '';
            switch(type) {
                case 'disbursement_ready':
                    preview = 'Your stipend disbursement of ₱{{ number_format($disbursement->amount, 2) }} for {{ date("F Y", mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }} is now ready for processing.';
                    break;
                case 'status_update':
                    preview = 'Your stipend disbursement status has been updated to: {{ $disbursement->status }}. Amount: ₱{{ number_format($disbursement->amount, 2) }} for {{ date("F Y", mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }}.';
                    break;
                case 'reminder':
                    preview = 'Reminder: You have a stipend disbursement of ₱{{ number_format($disbursement->amount, 2) }} for {{ date("F Y", mktime(0, 0, 0, $disbursement->disbursement_month, 1, $disbursement->disbursement_year)) }} that requires your attention.';
                    break;
            }
            previewContent.innerHTML = preview;
        } else {
            customMessageDiv.style.display = 'none';
            messagePreview.style.display = 'none';
            sendBtn.disabled = true;
        }
    });
    
    // Handle custom message input
    document.getElementById('customMessage').addEventListener('input', function() {
        const sendBtn = document.getElementById('sendNotificationBtn');
        sendBtn.disabled = this.value.trim() === '';
    });
    
    // Handle form submission
    document.getElementById('notifyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('sendNotificationBtn');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <strong>Success!</strong> ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('notifyModal'));
                modal.hide();
                
                // Reset form
                this.reset();
                document.getElementById('customMessageDiv').style.display = 'none';
                document.getElementById('messagePreview').style.display = 'none';
            } else {
                throw new Error(data.message || 'Failed to send notification');
            }
        })
        .catch(error => {
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <strong>Error!</strong> ${error.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
</div>

@endsection