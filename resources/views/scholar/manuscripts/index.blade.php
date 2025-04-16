@extends('layouts.app')

@section('title', 'My Manuscripts')

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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($manuscripts as $manuscript)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $manuscript->progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-1">{{ $manuscript->progress }}% Complete</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($manuscript->status == 'Completed') bg-green-100 text-green-800
                                    @elseif($manuscript->status == 'Rejected') bg-red-100 text-red-800
                                    @elseif($manuscript->status == 'Under Review') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-600 @endif">
                                    {{ $manuscript->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('scholar.manuscripts.show', $manuscript->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('scholar.manuscripts.edit', $manuscript->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('scholar.manuscripts.destroy', $manuscript->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this manuscript?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
@endsection
