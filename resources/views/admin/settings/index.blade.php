<a href="#general" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-md">
                            <i class="fas fa-cog mr-3 text-gray-400"></i>
                            General Settings
                        </a>
                        <a href="#scholarship" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <i class="fas fa-graduation-cap mr-3 text-gray-400"></i>
                            Scholarship Settings
                        </a>
                        <a href="#users" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <i class="fas fa-users mr-3 text-gray-400"></i>
                            User Management
                        </a>
                        <a href="#notifications" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <i class="fas fa-bell mr-3 text-gray-400"></i>
                            Notification Settings
                        </a>
                        <a href="#backup" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <i class="fas fa-database mr-3 text-gray-400"></i>
                            Backup & Restore
                        </a>
                        <a href="#logs" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <i class="fas fa-list mr-3 text-gray-400"></i>
                            System Logs
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Settings -->
                <div id="general" class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                    <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                        <h2 class="text-lg font-semibold text-white">General Settings</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="site_name" class="block text-sm font-medium text-gray-400 mb-1">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="{{ $settings->site_name ?? 'CLSU-ERDT Scholarship Management System' }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="site_description" class="block text-sm font-medium text-gray-400 mb-1">Site Description</label>
                                <textarea id="site_description" name="site_description" rows="3" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings->site_description ?? 'Central Luzon State University - Engineering Research and Development for Technology Scholarship Management System' }}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="contact_email" class="block text-sm font-medium text-gray-400 mb-1">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="{{ $settings->contact_email ?? 'erdt@clsu.edu.ph' }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="contact_phone" class="block text-sm font-medium text-gray-400 mb-1">Contact Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" value="{{ $settings->contact_phone ?? '+63 44 456 0680' }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-400 mb-1">System Maintenance Mode</label>
                                <div class="flex items-center">
                                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ ($settings->maintenance_mode ?? false) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                    <label for="maintenance_mode" class="ml-2 block text-sm text-gray-300">Enable Maintenance Mode</label>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">When enabled, only administrators can access the system.</p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-save mr-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Scholarship Settings -->
                <div id="scholarship" class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                    <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                        <h2 class="text-lg font-semibold text-white">Scholarship Settings</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.settings.scholarship.update') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="default_stipend" class="block text-sm font-medium text-gray-400 mb-1">Default Monthly Stipend (₱)</label>
                                <input type="number" id="default_stipend" name="default_stipend" value="{{ $settings->default_stipend ?? 20000 }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="default_book_allowance" class="block text-sm font-medium text-gray-400 mb-1">Default Book Allowance (₱)</label>
                                <input type="number" id="default_book_allowance" name="default_book_allowance" value="{{ $settings->default_book_allowance ?? 10000 }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="default_research_allowance" class="block text-sm font-medium text-gray-400 mb-1">Default Research Allowance (₱)</label>
                                <input type="number" id="default_research_allowance" name="default_research_allowance" value="{{ $settings->default_research_allowance ?? 50000 }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="max_scholarship_duration" class="block text-sm font-medium text-gray-400 mb-1">Maximum Scholarship Duration (months)</label>
                                <input type="number" id="max_scholarship_duration" name="max_scholarship_duration" value="{{ $settings->max_scholarship_duration ?? 36 }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="required_documents" class="block text-sm font-medium text-gray-400 mb-1">Required Documents</label>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="req_transcript" name="required_documents[]" value="Transcript" {{ in_array('Transcript', $settings->required_documents ?? []) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                        <label for="req_transcript" class="ml-2 block text-sm text-gray-300">Transcript of Records</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="req_id" name="required_documents[]" value="ID" {{ in_array('ID', $settings->required_documents ?? []) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                        <label for="req_id" class="ml-2 block text-sm text-gray-300">Valid ID</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="req_enrollment" name="required_documents[]" value="Enrollment" {{ in_array('Enrollment', $settings->required_documents ?? []) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                        <label for="req_enrollment" class="ml-2 block text-sm text-gray-300">Proof of Enrollment</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="req_grades" name="required_documents[]" value="Grades" {{ in_array('Grades', $settings->required_documents ?? []) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                        <label for="req_grades" class="ml-2 block text-sm text-gray-300">Semestral Grades</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-save mr-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- User Management -->
                <div id="users" class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                    <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-white">User Management</h2>
                        <a href="{{ route('admin.users.create') }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Add User
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <input type="text" placeholder="Search users..." class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center">
                                                        @if($user->profile_photo)
                                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full">
                                                        @else
                                                            <i class="fas fa-user text-gray-400"></i>
                                                        @endif
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ ucfirst($user->role) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $user->active ? 'bg-green-500 bg-opacity-20 text-green-400' : 'bg-red-500 bg-opacity-20 text-red-400' }}">
                                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-400 hover:text-yellow-300 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="{{ $user->active ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300' }} mr-3">
                                                        <i class="fas {{ $user->active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection