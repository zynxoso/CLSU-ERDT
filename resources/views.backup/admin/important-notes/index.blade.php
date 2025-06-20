@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Important Notes Management</h1>
        <a href="{{ route('admin.important-notes.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Note
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTales Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Important Notes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sort Order</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Content Preview</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                        <tr>
                            <td>{{ $note->sort_order }}</td>
                            <td>{{ $note->title }}</td>
                            <td>
                                <span class="badge badge-{{ $note->type == 'main' ? 'primary' : ($note->type == 'submission' ? 'warning' : 'info') }}">
                                    {{ ucfirst($note->type) }}
                                </span>
                            </td>
                            <td>{{ Str::limit(strip_tags($note->content), 50) }}</td>
                            <td>
                                <span class="badge badge-{{ $note->is_active ? 'success' : 'secondary' }}">
                                    {{ $note->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.important-notes.edit', $note) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.important-notes.toggle-status', $note) }}"
                                          style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $note->is_active ? 'secondary' : 'success' }}"
                                                onclick="return confirm('Are you sure you want to {{ $note->is_active ? 'deactivate' : 'activate' }} this note?')">
                                            <i class="fas fa-{{ $note->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.important-notes.destroy', $note) }}"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this note?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No important notes found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, "asc" ]]
    });
});
</script>
@endpush
