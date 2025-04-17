@if(count($scholars) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Scholar</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Program</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">University</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Start Date</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Progress</th>
                    <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($scholars as $scholar)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden shadow-sm border border-blue-100">
                                    @if($scholar->user->profile_photo)
                                        <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-blue-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="ml-2">
                                    <div class="text-xs font-medium text-gray-600 truncate max-w-[120px]" title="{{ $scholar->user->name }}">{{ $scholar->user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-600 truncate max-w-[80px]" title="{{ $scholar->program }}">
                                {{ Str::limit($scholar->program, 15, '...') }}
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-600 truncate max-w-[80px]" title="{{ $scholar->university }}">
                                {{ Str::limit($scholar->university, 12, '...') }}
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ $scholar->start_date ? date('M Y', strtotime($scholar->start_date)) : '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-xs rounded-full
                                @if($scholar->status == 'Active' || $scholar->status == 'Ongoing') bg-green-500 bg-opacity-20 text-green-400
                                @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated') bg-red-500 bg-opacity-20 text-red-400
                                @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') bg-blue-500 bg-opacity-20 text-blue-400
                                @else bg-yellow-500 bg-opacity-20 text-yellow-400 @endif">
                                {{ $scholar->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
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
                            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $progress }}%</span>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center space-x-1">
                                <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="text-blue-400 hover:text-blue-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-blue-50 rounded-full" title="View Scholar">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="text-yellow-400 hover:text-yellow-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-yellow-50 rounded-full" title="Edit Scholar">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
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
