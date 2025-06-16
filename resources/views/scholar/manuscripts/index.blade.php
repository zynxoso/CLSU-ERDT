@extends('layouts.app')

@section('title', 'Manuscripts')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Manuscripts</h1>
    <a href="{{ route('scholar.manuscripts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i> New Manuscript
    </a>
</div>

<!-- Manuscripts List -->
<div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
    @if(count($manuscripts) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($manuscripts as $manuscript)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="truncate max-w-xs cursor-help"
                                     title="{{ $manuscript->title }}"
                                     data-tooltip="{{ $manuscript->title }}">
                                    {{ Str::limit($manuscript->title, 50, '...') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full inline-flex items-center
                                    @if($manuscript->status == 'Published') bg-green-100 text-green-800
                                    @elseif($manuscript->status == 'Accepted') bg-green-100 text-green-700  {{-- Slightly different green for Accepted vs Published --}}
                                    @elseif($manuscript->status == 'Rejected') bg-red-100 text-red-800
                                    @elseif($manuscript->status == 'Under Review') bg-yellow-100 text-yellow-800
                                    @elseif($manuscript->status == 'Revision Requested') bg-orange-100 text-orange-800
                                    @elseif($manuscript->status == 'Submitted') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-600 @endif"> {{-- Default for Draft or other statuses --}}
                                    @if($manuscript->status == 'Published')
                                        <i class="fas fa-book mr-1" style="color: #065f46;"></i>
                                    @elseif($manuscript->status == 'Accepted')
                                        <i class="fas fa-check-circle mr-1" style="color: #065f46;"></i>
                                    @elseif($manuscript->status == 'Rejected')
                                        <i class="fas fa-times-circle mr-1" style="color: #7f1d1d;"></i>
                                    @elseif($manuscript->status == 'Under Review')
                                        <i class="fas fa-search mr-1" style="color: #92400e;"></i>
                                    @elseif($manuscript->status == 'Revision Requested')
                                        <i class="fas fa-exclamation-circle mr-1" style="color: #9a3412;"></i>
                                    @elseif($manuscript->status == 'Submitted')
                                        <i class="fas fa-file-alt mr-1" style="color: #1e40af;"></i>
                                    @else
                                        <i class="fas fa-pencil-alt mr-1" style="color: #4b5563;"></i>
                                    @endif
                                    {{ $manuscript->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('scholar.manuscripts.show', $manuscript->id) }}" class="text-blue-600 hover:text-blue-900">
                                     view manuscript
                                </a>
                                @if($manuscript->status === 'Draft' || $manuscript->status === 'Revision Requested')
                                <form id="submitForm{{ $manuscript->id }}" action="{{ route('scholar.manuscripts.submit', $manuscript->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmSubmit({{ $manuscript->id }})" class="text-green-600 hover:text-green-900 mr-3" title="Submit Manuscript">
                                        <i class="fas fa-check-circle" style="color: black;"></i> Submit
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-book text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800 mb-2">No Manuscripts Yet</h3>
            <p class="text-gray-600 mb-6">You haven't created any manuscripts yet.</p>
            <a href="{{ route('scholar.manuscripts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Create Your First Manuscript
            </a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmSubmit(manuscriptId) {
        Swal.fire({
            title: 'Submit Manuscript?',
            text: "Once submitted, the manuscript will be final and cannot be edited.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('submitForm' + manuscriptId).submit();
            }
        });
    }
</script>

@endsection
