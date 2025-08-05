@extends('layouts.app')

@section('title', 'Create Stipend Disbursement')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-5 border-b d-flex justify-content-between align-items-center" style="border-color: #E0E0E0;">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3" style="background-color: rgba(76, 175, 80, 0.1);">
                            <i class="fas fa-plus" style="color: #4CAF50;"></i>
                        </div>
                        <h3 class="mb-0" style="color: #212121; font-size: 20px;">Create Individual Stipend Disbursement</h3>
                    </div>
                    <a href="{{ route('admin.stipends.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors" style="background-color: #757575; color: white;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Stipends
                    </a>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.stipends.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scholar_profile_id" class="form-label">Scholar <span class="text-danger">*</span></label>
                                    <select name="scholar_profile_id" id="scholar_profile_id" class="form-select @error('scholar_profile_id') is-invalid @enderror" required>
                                        <option value="">Select Scholar</option>
                                        @foreach($scholars as $scholar)
                                        <option value="{{ $scholar->id }}" {{ old('scholar_profile_id') == $scholar->id ? 'selected' : '' }}>
                                            {{ $scholar->user->first_name }} {{ $scholar->user->last_name }} 
                                            ({{ $scholar->intended_degree }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('scholar_profile_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="disbursement_month" class="form-label">Month <span class="text-danger">*</span></label>
                                    <select name="disbursement_month" id="disbursement_month" class="form-select @error('disbursement_month') is-invalid @enderror" required>
                                        <option value="">Select Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('disbursement_month', date('n')) == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                        @endfor
                                    </select>
                                    @error('disbursement_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="disbursement_year" class="form-label">Year <span class="text-danger">*</span></label>
                                    <select name="disbursement_year" id="disbursement_year" class="form-select @error('disbursement_year') is-invalid @enderror" required>
                                        <option value="">Select Year</option>
                                        @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}" {{ old('disbursement_year', date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                        @endfor
                                    </select>
                                    @error('disbursement_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           step="0.01" min="0" value="{{ old('amount') }}" required>
                                    @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small id="suggested-amount" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processed" {{ old('status') == 'Processed' ? 'selected' : '' }}>Processed</option>
                                        <option value="Failed" {{ old('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="disbursement_type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="disbursement_type" id="disbursement_type" class="form-select @error('disbursement_type') is-invalid @enderror" required>
                                        <option value="Regular" {{ old('disbursement_type', 'Regular') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="Adjustment" {{ old('disbursement_type') == 'Adjustment' ? 'selected' : '' }}>Adjustment</option>
                                        <option value="Bonus" {{ old('disbursement_type') == 'Bonus' ? 'selected' : '' }}>Bonus</option>
                                        <option value="Backpay" {{ old('disbursement_type') == 'Backpay' ? 'selected' : '' }}>Backpay</option>
                                    </select>
                                    @error('disbursement_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Optional notes about this disbursement...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Scholar Information Panel -->
                        <div id="scholar-info" class="card bg-light mb-3" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">Scholar Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Intended Degree:</strong> <span id="program-level">-</span></p>
                                        <p><strong>Status:</strong> <span id="scholar-status">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Base Stipend:</strong> ₱<span id="base-stipend">-</span></p>
                                        <p><strong>Last Disbursement:</strong> <span id="last-disbursement">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-4 border-t" style="border-color: #E0E0E0;">
                            <a href="{{ route('admin.stipends.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors" style="background-color: #757575; color: white;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors" style="background-color: #4CAF50; color: white;" onmouseover="this.style.backgroundColor='#43A047'" onmouseout="this.style.backgroundColor='#4CAF50'">
                                <i class="fas fa-save mr-2"></i> Create Disbursement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Scholar selection change handler
    $('#scholar_profile_id').change(function() {
        const scholarId = $(this).val();
        
        if (scholarId) {
            // Show loading state
            $('#scholar-info').show();
            $('#program-level, #scholar-status, #base-stipend, #last-disbursement').text('Loading...');
            $('#suggested-amount').text('Calculating suggested amount...');
            
            // Fetch scholar information
            $.get(`/admin/scholars/${scholarId}`, function(data) {
                $('#program-level').text(data.intended_degree || '-');
                $('#scholar-status').text(data.status || '-');
                
                // Calculate and show suggested amount
                const baseAmount = (data.intended_degree && (data.intended_degree.toLowerCase().includes('phd') || data.intended_degree.toLowerCase().includes('doctorate') || data.intended_degree.toLowerCase().includes('doctoral'))) ? 15000 : 12000;
                $('#base-stipend').text(baseAmount.toLocaleString());
                $('#amount').val(baseAmount);
                $('#suggested-amount').text(`Suggested amount: ₱${baseAmount.toLocaleString()} (${data.intended_degree} level)`);
                
                // Show last disbursement info if available
                if (data.last_disbursement) {
                    $('#last-disbursement').text(`${data.last_disbursement.month}/${data.last_disbursement.year} - ₱${data.last_disbursement.amount}`);
                } else {
                    $('#last-disbursement').text('No previous disbursements');
                }
            }).fail(function() {
                $('#program-level, #scholar-status, #base-stipend, #last-disbursement').text('Error loading data');
                $('#suggested-amount').text('Could not calculate suggested amount');
            });
        } else {
            $('#scholar-info').hide();
            $('#amount').val('');
            $('#suggested-amount').text('');
        }
    });
    
    // Auto-suggest amount based on program level
    $('#disbursement_type').change(function() {
        const type = $(this).val();
        const scholarId = $('#scholar_profile_id').val();
        
        if (scholarId && type === 'Regular') {
            // Trigger scholar change to recalculate amount
            $('#scholar_profile_id').trigger('change');
        }
    });
});
</script>
@endpush