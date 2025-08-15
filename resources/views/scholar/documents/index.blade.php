@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <!-- Document List -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">My Uploaded Documents</h2>
            </div>
            
            @if($documents->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Uploaded</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-blue-500 rounded-lg">
                                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                    <i class="fas fa-file-image text-blue-500 text-lg"></i>
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                    <i class="fas fa-file-word text-blue-700 text-lg"></i>
                                                @else
                                                    <i class="fas fa-file text-blue-600 text-lg"></i>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $document->file_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $document->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('scholar.documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('scholar.documents.download', $document->id) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">You haven't uploaded any documents yet.</p>
                    <a href="{{ route('scholar.documents.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-plus mr-2" style="color: rgb(255 255 255) !important;"></i> Upload Your First Document
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
