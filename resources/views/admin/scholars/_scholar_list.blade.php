@if(count($scholars) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Program</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">University</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Start Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Progress</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($scholars as $scholar)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden shadow-sm border border-blue-100">
                                    @if($scholar->user->profile_photo)
                                        <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-10 w-10 rounded-full object-cover transition-transform duration-200 hover:scale-110">
                                    @else
                                        <i class="fas fa-user text-blue-400"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-600">{{ $scholar->user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $scholar->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $scholar->program }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $scholar->university }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'Not set' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full transition-all duration-300 hover:shadow-md
                                @if($scholar->status == 'Active' || $scholar->status == 'Ongoing') bg-green-500 bg-opacity-20 text-green-400 hover:bg-opacity-30
                                @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated') bg-red-500 bg-opacity-20 text-red-400 hover:bg-opacity-30
                                @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') bg-blue-500 bg-opacity-20 text-blue-400 hover:bg-opacity-30
                                @else bg-yellow-500 bg-opacity-20 text-yellow-400 hover:bg-opacity-30 @endif">
                                {{ $scholar->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $progress = 0;
                                if($scholar->start_date && $scholar->expected_completion_date) {
                                    $startDate = \Carbon\Carbon::parse($scholar->start_date);
                                    $endDate = \Carbon\Carbon::parse($scholar->expected_completion_date);
                                    $today = \Carbon\Carbon::now();

                                    $totalDays = $startDate->diffInDays($endDate);
                                    $daysPassed = $startDate->diffInDays($today);

                                    $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;
                                }
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full text-gray-600" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 mt-1">{{ $progress }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex justify-center items-center space-x-4">
                                <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="text-blue-400 hover:text-blue-300 transition-all duration-200 hover:scale-125 inline-flex items-center justify-center h-8 w-8" title="View Scholar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="text-yellow-400 hover:text-yellow-300 transition-all duration-200 hover:scale-125 inline-flex items-center justify-center h-8 w-8" title="Edit Scholar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                    class="text-red-400 hover:text-red-300 transition-all duration-200 hover:scale-125 delete-scholar-btn inline-flex items-center justify-center h-8 w-8"
                                    data-id="{{ $scholar->id }}"
                                    data-name="{{ $scholar->user->name }}"
                                    title="Delete Scholar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12 animate-fade-in">
        <div class="w-16 h-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-4 border border-blue-100 shadow-sm">
            <i class="fas fa-user-graduate text-2xl text-blue-400"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-700 mb-2">No Scholars Found</h3>
        <p class="text-gray-500 mb-6">There are no scholars matching your filter criteria.</p>
        <x-button
            href="{{ route('admin.scholars.create') }}"
            variant="primary"
            icon="fas fa-user-plus"
            animate="true"
        >
            Add New Scholar
        </x-button>
    </div>
@endif
