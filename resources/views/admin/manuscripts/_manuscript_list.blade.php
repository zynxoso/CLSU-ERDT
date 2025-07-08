@if(count($manuscripts) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y" style="border-color: #E0E0E0; background-color: white;">
            <thead style="background-color: #F8F9FA;">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Last Updated</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                @foreach($manuscripts as $manuscript)
                    <tr style="transition: background-color 0.15s ease;"
                        onmouseover="this.style.backgroundColor='#F8F9FA'"
                        onmouseout="this.style.backgroundColor='white'">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-full max-w-xs">
                                    <div class="text-sm truncate cursor-help"
                                         title="{{ $manuscript->title }}"
                                         data-tooltip="{{ $manuscript->title }}"
                                         style="color: #424242; font-size: 15px;">
                                        {{ Str::limit($manuscript->title, 40, '...') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" style="background-color: #F8BBD0;">
                                    @if($manuscript->scholarProfile && $manuscript->scholarProfile->profile_photo)
                                        <img src="{{ asset('images/' . $manuscript->scholarProfile->profile_photo) }}" alt="{{ $manuscript->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <i class="fas fa-user" style="color: #2E7D32;"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium" style="color: #212121; font-size: 15px;">{{ $manuscript->user ? $manuscript->user->name : 'Unknown' }}</div>
                                    <div class="text-xs" style="color: #757575; font-size: 13px;">{{ Str::limit($manuscript->scholarProfile ? $manuscript->scholarProfile->program : 'N/A', 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242; font-size: 14px;">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($manuscript->status == 'Published') text-white" style="background-color: #2E7D32;
                                    @elseif($manuscript->status == 'Accepted') text-white" style="background-color: #2E7D32;
                                    @elseif($manuscript->status == 'Revision Requested') text-white" style="background-color: #FF9800;
                                    @elseif($manuscript->status == 'Under Review') text-white" style="background-color: #FFCA28;
                                    @elseif($manuscript->status == 'Submitted') text-white" style="background-color: #1976D2;
                                    @elseif($manuscript->status == 'Rejected') text-white" style="background-color: #D32F2F;
                                    @else text-white" style="background-color: #757575; @endif">
                                    {{ $manuscript->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center">
                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                                   class="manuscript-action-link transition-colors duration-200 px-3 py-1 rounded-md"
                                   style="color: #2E7D32; background-color: #E8F5E8; font-size: 14px;"
                                   onmouseover="this.style.backgroundColor='#C8E6C8'"
                                   onmouseout="this.style.backgroundColor='#E8F5E8'"
                                   title="View Details">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
            <i class="fas fa-book text-2xl" style="color: #9E9E9E;"></i>
        </div>
        <h3 class="text-lg font-medium mb-2" style="color: #212121;">No Manuscripts Found</h3>
        <p class="mb-6" style="color: #757575;">There are no manuscripts matching your filter criteria.</p>
    </div>
@endif
