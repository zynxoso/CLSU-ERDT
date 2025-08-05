@extends('layouts.app')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto">
        <!-- Enhanced Header Section -->
        <div class="rounded-xl shadow-sm border mb-6" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-5">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-2">
                    <!-- Title Section -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: rgba(76, 175, 80, 0.1);">
                                <i class="fas fa-file-alt text-lg" style="color: #4CAF50;"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Manuscript Details</h1>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-sm" style="color: #757575;">Reference:</span>
                                <span class="text-sm font-mono px-2 py-1 rounded" style="background-color: #F8F9FA; color: #424242;">{{ $manuscript->reference_number }}</span>
                                <!-- Enhanced Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($manuscript->status === 'Draft')
                                        text-white" style="background-color: #757575;
                                    @elseif($manuscript->status === 'Submitted')
                                        text-white" style="background-color: #4A90E2;
                                    @elseif($manuscript->status === 'Under Review')
                                        text-white" style="background-color: #FFCA28;
                                    @elseif($manuscript->status === 'Revision Requested')
                                        text-white" style="background-color: #FF9800;
                                    @elseif($manuscript->status === 'Accepted')
                                        text-white" style="background-color: #4CAF50;
                                    @elseif($manuscript->status === 'Rejected')
                                        text-white" style="background-color: #D32F2F;
                                    @elseif($manuscript->status === 'Published')
                                        text-white" style="background-color: #4CAF50;
                                    @endif">
                                    <span class="w-2 h-2 rounded-full mr-2" style="background-color: rgba(255, 255, 255, 0.8);"></span>
                                    {{ $manuscript->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
            <!-- Main Content - Left Side -->
            <div class="xl:col-span-3 space-y-6">
                <!-- Manuscript Information Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b" style="border-color: #E0E0E0;">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold" style="color: #212121;">{{ $manuscript->title ?? '[Untitled Manuscript]' }}</h2>
                            <span class="text-sm px-3 py-1 rounded-full" style="color: #757575; background-color: #F8F9FA;">{{ $manuscript->manuscript_type }}</span>
                        </div>
                    </div>

                    <!-- Abstract Section -->
                    <div class="px-6 py-5 border-b" style="border-color: #E0E0E0;">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background-color: rgba(76, 175, 80, 0.1);">
                                <i class="fas fa-align-left text-sm" style="color: #4CAF50;"></i>
                            </div>
                            <h3 class="text-lg font-medium" style="color: #212121;">Abstract</h3>
                        </div>
                        <div class="border rounded-lg p-4" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                            <p class="leading-relaxed whitespace-pre-line" style="color: #424242;">{{ $manuscript->abstract ?: 'No abstract provided.' }}</p>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="px-6 py-5">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background-color: rgba(76, 175, 80, 0.1);">
                                <i class="fas fa-paperclip text-sm" style="color: #4CAF50;"></i>
                            </div>
                            <h3 class="text-lg font-medium" style="color: #212121;">Attached Documents</h3>
                            @if($manuscript->documents->count() > 0)
                                <span class="ml-3 text-xs font-medium px-2 py-1 rounded-full" style="background-color: #E8F5E8; color: #4CAF50;">
                                    {{ $manuscript->documents->count() }} {{ Str::plural('file', $manuscript->documents->count()) }}
                                </span>
                            @endif
                        </div>

                        @if($manuscript->documents->count() > 0)
                            <div class="space-y-3">
                                @foreach($manuscript->documents as $document)
                                    <div class="flex items-center justify-between p-4 border rounded-lg" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(211, 47, 47, 0.1);">
                                                <i class="fas fa-file-pdf" style="color: #D32F2F;"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium" style="color: #212121;">{{ $document->title }}</p>
                                                <p class="text-sm" style="color: #757575;">PDF Document</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.documents.download', $document->id) }}"
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                                           style="background-color: #4CAF50; color: white;"
                                           onmouseover="this.style.backgroundColor='#43A047'"
                                           onmouseout="this.style.backgroundColor='#4CAF50'"
                                           title="Download document">
                                            <i class="fas fa-download mr-2"></i>
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #F8F9FA;">
                                    <i class="fas fa-file-alt text-xl" style="color: #9E9E9E;"></i>
                                </div>
                                <p style="color: #757575;">No documents attached to this manuscript</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Right Side -->
            <div class="space-y-6">
                <!-- Author Information Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-5">
                        <div class="flex items-center mb-2">
                            <h3 class="text-lg font-medium" style="color: #212121;">Author Information</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide" style="color: #757575;">Primary Author</label>
                                <p class="mt-1 text-sm font-medium" style="color: #212121;">{{ $manuscript->scholarProfile?->user?->name ?? 'N/A' }}</p>
                            </div>

                            {{-- @if($manuscript->co_authors)
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wide" style="color: #757575;">Co-Authors</label>
                                    <p class="mt-1 text-sm" style="color: #424242;">{{ $manuscript->co_authors }}</p>
                                </div>
                            @endif --}}

                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide" style="color: #757575;">Submission Date</label>
                                <p class="mt-1 text-sm" style="color: #424242;">{{ $manuscript->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Update Form -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-2 border-b" style="border-color: #E0E0E0;">
                        <div class="flex items-center">
                            <h3 class="text-lg font-medium" style="color: #212121;">Update Status</h3>
                        </div>
                    </div>

                    <div class="px-4 py-3">
                        <form action="{{ route('admin.manuscripts.updateStatus', $manuscript->id) }}" method="POST" class="space-y-4"
                              x-data="{
                                  oldStatus: '{{ $manuscript->status }}',
                                  newStatus: '{{ $manuscript->status }}',
                                  notifyScholar: true,
                                  statusChanged: false
                              }"
                              x-init="$watch('newStatus', value => statusChanged = (value !== oldStatus))"
                              @submit="if(statusChanged) {
                                  $dispatch('manuscript-status-updated', {
                                      manuscriptId: {{ $manuscript->id }},
                                      oldStatus: oldStatus,
                                      newStatus: newStatus
                                  });
                              }">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="status" class="block text-sm font-medium mb-2" style="color: #424242;">Status</label>
                                <select name="status" id="status" x-model="newStatus"
                                        class="w-full px-3 py-2 border rounded-lg shadow-sm text-sm"
                                        style="border-color: #E0E0E0;">
                                    @foreach(['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'] as $status)
                                        <option value="{{ $status }}" {{ $manuscript->status == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="admin_notes" class="block text-sm font-medium mb-2" style="color: #424242;">Admin Notes</label>
                                <textarea name="admin_notes" id="admin_notes" rows="2"
                                    class="w-full px-3 py-2 border rounded-lg shadow-sm text-sm resize-none"
                                    style="border-color: #E0E0E0;"
                                    placeholder="Add notes about this status update...">{{ $manuscript->admin_notes }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg shadow-sm transition-colors"
                                style="background-color: #4CAF50; color: white;"
                                onmouseover="this.style.backgroundColor='#43A047'"
                                onmouseout="this.style.backgroundColor='#4CAF50'"
                                :title="statusChanged ? 'Update manuscript status' : 'No changes to save'">
                                <span x-text="statusChanged ? 'Update Manuscript & Notify Scholar' : 'Update Manuscript'"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Current Admin Notes Display -->
                @if($manuscript->admin_notes)
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-5">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background-color: rgba(255, 202, 40, 0.1);">
                                <i class="fas fa-sticky-note text-sm" style="color: #FFCA28;"></i>
                            </div>
                            <h3 class="text-lg font-medium" style="color: #212121;">Current Admin Notes</h3>
                        </div>
                        <div class="border rounded-lg p-4" style="background-color: #FFFDE7; border-color: #FFCA28;">
                            <p class="text-sm leading-relaxed whitespace-pre-line" style="color: #424242;">{{ $manuscript->admin_notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Enhanced focus states for better accessibility */
    .focus-ring:focus {
        outline: none !important;
        border-color: #4CAF50 !important;
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2) !important;
    }

    /* Custom scrollbar for better aesthetics */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #F8F9FA;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E0E0E0;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #BDBDBD;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced status change confirmation
        const statusSelect = document.getElementById('status');
        const form = statusSelect?.closest('form');

        if (form) {
            form.addEventListener('submit', function(e) {
                const newStatus = statusSelect.value;
                const currentStatus = '{{ $manuscript->status }}';

                if (newStatus !== currentStatus) {
                    const confirmation = confirm(`Are you sure you want to change the status from "${currentStatus}" to "${newStatus}"?`);
                    if (!confirmation) {
                        e.preventDefault();
                    }
                }
            });
        }

        // Auto-resize textarea
        const textarea = document.getElementById('admin_notes');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });
</script>
@endpush

@endsection
