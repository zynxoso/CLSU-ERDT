@if(count($scholars) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y" style="--tw-divide-opacity: 1; divide-color: #E0E0E0;">
            <thead>
                <tr>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Scholar</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Intended University</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                    <th scope="col" class="px-4 py-2 text-center text-xs font-medium uppercase tracking-wider" style="color: #757575;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 1; divide-color: #E0E0E0;">
                @foreach($scholars as $scholar)
                    <tr class="hover:bg-opacity-50 transition-colors duration-150" style="--tw-bg-opacity: 1;" onmouseover="this.style.backgroundColor='rgba(76, 175, 80, 0.05)'" onmouseout="this.style.backgroundColor='transparent'">
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center overflow-hidden shadow-sm border" style="background-color: rgba(76, 175, 80, 0.1); border-color: #4CAF50;">
                                    @if($scholar->profile_photo)
                                        <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-xs" style="color: #C2185B;"></i>
                                    @endif
                                </div>
                                <div class="ml-2">
                                    <div class="text-xs font-medium truncate max-w-[120px]" title="{{ $scholar->full_name }}" style="color: #424242;">{{ $scholar->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="text-xs truncate max-w-[80px]" title="{{ $scholar->intended_university }}" style="color: #757575;">
                                {{ Str::limit($scholar->intended_university, 12, '...') }}
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($scholar->status == 'Active' || $scholar->status == 'Ongoing') text-white
                                    @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated') text-white
                                    @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') text-white
                                    @elseif($scholar->status == 'On Extension') text-white
                                    @elseif($scholar->status == 'New') text-white
                                    @else text-white @endif"
                                    style="
                                    @if($scholar->status == 'Active' || $scholar->status == 'Ongoing') background-color: #4CAF50; color: white;
                                    @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated') background-color: #D32F2F; color: white;
                                    @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') background-color: #4CAF50; color: white;
                                    @elseif($scholar->status == 'On Extension') background-color: #4CAF50; color: white;
                                    @elseif($scholar->status == 'New') background-color: #4A90E2; color: white;
                                    @else background-color: #FFCA28; color: #975A16; @endif">
                                    {{ $scholar->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center space-x-1">
                                <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 rounded-full hover:opacity-80" style="color: white; background-color: #4A90E2;" title="View Scholar">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 rounded-full hover:opacity-80" style="color: white; background-color: #4CAF50;" title="Edit Scholar">
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
        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 border shadow-sm" style="background-color: rgba(76, 175, 80, 0.1); border-color: #4CAF50;">
            <i class="fas fa-user-graduate text-2xl" style="color: #C2185B;"></i>
        </div>
        <h3 class="text-lg font-medium mb-2" style="color: #424242;">No Scholars Found</h3>
        <p class="mb-6" style="color: #757575;">There are no scholars matching your filter criteria.</p>
        <a href="{{ route('admin.scholars.create') }}" class="inline-flex items-center px-6 py-2 text-white rounded-lg transition-all duration-200 hover:opacity-90 hover:scale-105" style="background-color: #4CAF50;">
            <i class="fas fa-user-plus mr-2"></i>
            Add New Scholar
        </a>
    </div>
@endif

<style>
/* Global Typography Improvements */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #424242;
}

/* Enhanced Transitions */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Professional Shadow Effects */
.shadow-sm {
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

/* Enhanced Hover Effects */
tr:hover {
    transition: background-color 0.15s ease-in-out;
}

a[title]:hover {
    transform: scale(1.1);
    transition: all 0.2s ease-in-out;
}
</style>
