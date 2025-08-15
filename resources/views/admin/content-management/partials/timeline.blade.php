<div class="tab-pane fade show active" id="timeline" role="tabpanel">
    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.content-management.index') }}" class="d-flex gap-2">
                <input type="hidden" name="tab" value="timeline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search timeline events..." value="{{ $searchTerm }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select name="filter_status" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filterStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.content-management.index', ['tab' => 'timeline']) }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>
    </div>

    <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" onclick="openModal('addTimelineModal')">
                <i class="fas fa-plus"></i> Add New Timeline Event
            </button>
        </div>
    </div>

    <!-- Timeline Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($applicationTimelines) && $applicationTimelines->count() > 0)
                    @foreach($applicationTimelines as $timeline)
                        <tr>
                            <td>
                                <strong>{{ $timeline->title }}</strong>
                                @if($timeline->description)
                                    <br><small class="text-muted">{{ Str::limit($timeline->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($timeline->type ?? 'Event') }}</span>
                            </td>
                            <td>
                                <small>{{ $timeline->start_date ? $timeline->start_date->format('M d, Y') : 'N/A' }}</small>
                                @if($timeline->start_time)
                                    <br><small class="text-muted">{{ $timeline->start_time }}</small>
                                @endif
                            </td>
                            <td>
                                @if($timeline->end_date)
                                    <small>{{ $timeline->end_date->format('M d, Y') }}</small>
                                    @if($timeline->end_time)
                                        <br><small class="text-muted">{{ $timeline->end_time }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $timeline->is_active ? 'success' : 'secondary' }}">
                                    {{ $timeline->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($timeline->priority)
                                    <span class="badge bg-{{ $timeline->priority === 'high' ? 'danger' : ($timeline->priority === 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($timeline->priority) }}
                                    </span>
                                @else
                                    <span class="text-muted">Normal</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editTimeline({{ $timeline->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $timeline->is_active ? 'warning' : 'success' }}" 
                                            onclick="toggleStatus('timeline', {{ $timeline->id }})">
                                        <i class="fas fa-{{ $timeline->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteItem('timeline', {{ $timeline->id }}, '{{ addslashes($timeline->title) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No timeline events found.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($applicationTimelines) && $applicationTimelines->hasPages())
        <div class="d-flex justify-content-center">
            {{ $applicationTimelines->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Add Timeline Modal -->
<x-modal id="addTimelineModal" maxWidth="lg">
    <x-slot name="title">
        Add New Timeline Event
    </x-slot>
    
    <form method="POST" action="{{ route('admin.content-management.timeline.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="title" name="title" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="type" name="type">
                        <option value="event">Event</option>
                        <option value="deadline">Deadline</option>
                        <option value="milestone">Milestone</option>
                        <option value="announcement">Announcement</option>
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="priority" name="priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="start_date" name="start_date" required>
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="start_time" name="start_time">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="end_date" name="end_date">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="end_time" name="end_time">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="sort_order" name="sort_order" min="0">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="location" name="location">
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="description" name="description" rows="4"></textarea>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('addTimelineModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Create Timeline Event</button>
        </x-slot>
    </form>
</x-modal>

<!-- Edit Timeline Modal -->
<x-modal id="editTimelineModal" maxWidth="lg">
    <x-slot name="title">
        Edit Timeline Event
    </x-slot>
    
    <form method="POST" id="editTimelineForm">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_title" name="title" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_type" name="type">
                        <option value="event">Event</option>
                        <option value="deadline">Deadline</option>
                        <option value="milestone">Milestone</option>
                        <option value="announcement">Announcement</option>
                    </select>
                </div>
                <div>
                    <label for="edit_priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_priority" name="priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_start_date" name="start_date" required>
                </div>
                <div>
                    <label for="edit_start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_start_time" name="start_time">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_end_date" name="end_date">
                </div>
                <div>
                    <label for="edit_end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_end_time" name="end_time">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_sort_order" name="sort_order" min="0">
                </div>
                <div>
                    <label for="edit_location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_location" name="location">
                </div>
            </div>
            <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_description" name="description" rows="4"></textarea>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('editTimelineModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Timeline Event</button>
        </x-slot>
    </form>
</x-modal>

<script>
function editTimeline(id) {
    // Set the form action
    document.getElementById('editTimelineForm').action = `/admin/content-management/timeline/${id}`;
    
    // In a real implementation, you'd fetch the timeline data and populate the form
    // fetch(`/admin/content-management/timeline/${id}/edit`)
    //     .then(response => response.json())
    //     .then(data => {
    //         document.getElementById('edit_title').value = data.title;
    //         document.getElementById('edit_type').value = data.type;
    //         // ... populate other fields
    //     });
    
    openModal('editTimelineModal');
}
</script>
