<div>
    <!-- Success/Error Messages -->
    @if($successMessage)
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button wire:click="setActiveTab('announcements')"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'announcements' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Announcements
            </button>
            <button wire:click="setActiveTab('faculty')"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'faculty' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Faculty & Expertise
            </button>
        </nav>
    </div>

    <!-- Content Management Tab -->
    @if($activeTab === 'content')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Website Content Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage the content displayed on the public scholar website pages</p>
            </div>
            <div class="p-6">
                <div class="space-y-8">

                    <!-- Homepage Hero Section -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Homepage Hero Section</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-1">Main Title</label>
                                <input type="text" id="hero_title" wire:model="content.hero_title"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Welcome to the">
                            </div>
                            <div>
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                                <input type="text" id="hero_subtitle" wire:model="content.hero_subtitle"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="CLSU-ERDT Portal">
                            </div>
                            <div>
                                <label for="hero_tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                                <input type="text" id="hero_tagline" wire:model="content.hero_tagline"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Streamlining Your Scholarship Journey">
                            </div>
                            <div>
                                <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">Hero Background Image</label>
                                <input type="file" id="hero_image" wire:model="heroImage" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-1">Recommended size: 1920x1080px</p>
                                @error('heroImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="lg:col-span-2">
                                <label for="hero_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="hero_description" wire:model="content.hero_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="This official portal for the Engineering Research & Development..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- About Page Hero Section -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">About Page Hero Section</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="about_hero_title" class="block text-sm font-medium text-gray-700 mb-1">Main Title</label>
                                <input type="text" id="about_hero_title" wire:model="content.about_hero_title"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Engineering Excellence">
                            </div>
                            <div>
                                <label for="about_hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                                <input type="text" id="about_hero_subtitle" wire:model="content.about_hero_subtitle"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="at CLSU-ERDT">
                            </div>
                            <div>
                                <label for="about_hero_tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                                <input type="text" id="about_hero_tagline" wire:model="content.about_hero_tagline"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Fostering Advanced Education & Research">
                            </div>
                            <div class="lg:col-span-2">
                                <label for="about_hero_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="about_hero_description" wire:model="content.about_hero_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Discover our mission to develop world-class engineers..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Mission & Vision -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mission & Vision</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="mission" class="block text-sm font-medium text-gray-700 mb-1">Mission Statement</label>
                                <textarea id="mission" wire:model="content.mission" rows="5"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="To develop world-class engineers and researchers..."></textarea>
                            </div>
                            <div>
                                <label for="vision" class="block text-sm font-medium text-gray-700 mb-1">Vision Statement</label>
                                <textarea id="vision" wire:model="content.vision" rows="5"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="To be the premier center of excellence..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Homepage Statistics -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Homepage Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="stat_universities" class="block text-sm font-medium text-gray-700 mb-1">Universities Count</label>
                                <input type="text" id="stat_universities" wire:model="content.stat_universities"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="8">
                                <input type="text" wire:model="content.stat_universities_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Partner Universities">
                            </div>
                            <div>
                                <label for="stat_stipend" class="block text-sm font-medium text-gray-700 mb-1">Stipend Amount</label>
                                <input type="text" id="stat_stipend" wire:model="content.stat_stipend"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="₱38K">
                                <input type="text" wire:model="content.stat_stipend_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Monthly Stipend">
                            </div>
                            <div>
                                <label for="stat_scholars" class="block text-sm font-medium text-gray-700 mb-1">Scholars Count</label>
                                <input type="text" id="stat_scholars" wire:model="content.stat_scholars"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="200+">
                                <input type="text" wire:model="content.stat_scholars_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Scholars Supported">
                            </div>
                            <div>
                                <label for="stat_research_areas" class="block text-sm font-medium text-gray-700 mb-1">Research Areas</label>
                                <input type="text" id="stat_research_areas" wire:model="content.stat_research_areas"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="15+">
                                <input type="text" wire:model="content.stat_research_areas_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Research Areas">
                            </div>
                        </div>
                    </div>

                    <!-- About Page Statistics -->
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">About Page Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="about_stat_years" class="block text-sm font-medium text-gray-700 mb-1">Years Count</label>
                                <input type="text" id="about_stat_years" wire:model="content.about_stat_years"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="25+">
                                <input type="text" wire:model="content.about_stat_years_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Years of Excellence">
                            </div>
                            <div>
                                <label for="about_stat_graduates" class="block text-sm font-medium text-gray-700 mb-1">Graduates Count</label>
                                <input type="text" id="about_stat_graduates" wire:model="content.about_stat_graduates"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="500+">
                                <input type="text" wire:model="content.about_stat_graduates_label"
                                       class="w-full px-3 py-1 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                       placeholder="Graduates">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Content Sections -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Content</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="erdt_description" class="block text-sm font-medium text-gray-700 mb-1">ERDT Program Description</label>
                                <textarea id="erdt_description" wire:model="content.erdt_description" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="The ERDT program is a flagship initiative..."></textarea>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label for="benefits_title" class="block text-sm font-medium text-gray-700 mb-1">Benefits Section Title</label>
                                    <input type="text" id="benefits_title" wire:model="content.benefits_title"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="A System Built for Your Success">
                                </div>
                                <div>
                                    <label for="cta_title" class="block text-sm font-medium text-gray-700 mb-1">Call to Action Title</label>
                                    <input type="text" id="cta_title" wire:model="content.cta_title"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="Ready to Join Our Community?">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label for="benefits_description" class="block text-sm font-medium text-gray-700 mb-1">Benefits Description</label>
                                    <textarea id="benefits_description" wire:model="content.benefits_description" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                              placeholder="The CLSU-ERDT portal provides..."></textarea>
                                </div>
                                <div>
                                    <label for="cta_description" class="block text-sm font-medium text-gray-700 mb-1">Call to Action Description</label>
                                    <textarea id="cta_description" wire:model="content.cta_description" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                              placeholder="Become part of a prestigious community..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="contact_email" wire:model="content.contact_email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="erdt@clsu.edu.ph">
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" id="contact_phone" wire:model="content.contact_phone"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="0920-9312126">
                            </div>
                            <div>
                                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea id="contact_address" wire:model="content.contact_address" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="CLSU-ERDT Office, Engineering Building..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button wire:click="saveContent" type="button"
                            wire:loading.attr="disabled"
                            class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2 px-6 rounded text-sm inline-flex items-center">
                        <div wire:loading wire:target="saveContent" class="mr-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save All Content Changes
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Announcements Tab -->
    @if($activeTab === 'announcements')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Announcements Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Create and manage announcements for the scholar website</p>
                </div>
                <button wire:click="openAnnouncementModal"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Announcement
                </button>
            </div>
            <div class="p-6">
                <!-- Announcements List -->
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $announcement['title'] }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement['content'], 150) }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <span>Type: {{ ucfirst($announcement['type']) }}</span>
                                        <span class="mx-2">•</span>
                                        <span>Created: {{ \Carbon\Carbon::parse($announcement['created_at'])->format('M d, Y') }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="{{ $announcement['is_active'] ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $announcement['is_active'] ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <button wire:click="openAnnouncementModal({{ $announcement['id'] }})"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Edit
                                    </button>
                                    <button wire:click="toggleAnnouncementStatus({{ $announcement['id'] }})"
                                            class="text-green-600 hover:text-green-800 text-sm">
                                        {{ $announcement['is_active'] ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button wire:click="deleteAnnouncement({{ $announcement['id'] }})"
                                            wire:confirm="Are you sure you want to delete this announcement?"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0v10a2 2 0 002 2h6a2 2 0 002-2V8m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first announcement.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Faculty & Expertise Tab -->
    @if($activeTab === 'faculty')
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Faculty & Expertise Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage faculty profiles and expertise displayed on the about page</p>
                </div>
                <button wire:click="openFacultyModal"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Faculty
                </button>
            </div>
            <div class="p-6">
                <!-- Faculty Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($facultyMembers as $faculty)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="text-center">
                                <div class="mb-4">
                                    @if($faculty['photo_path'])
                                        <img src="{{ asset('experts/' . $faculty['photo_path']) }}" alt="{{ $faculty['name'] }}"
                                             class="w-20 h-20 rounded-full mx-auto object-cover">
                                    @else
                                        <div class="w-20 h-20 rounded-full mx-auto bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $faculty['name'] }}</h3>
                                <p class="text-sm text-indigo-600 mb-1">{{ $faculty['position'] }}</p>
                                <p class="text-sm text-gray-600 mb-1">{{ $faculty['specialization'] }}</p>
                                <p class="text-xs text-gray-500 mb-3">{{ $faculty['department'] }}</p>
                                <div class="flex justify-center space-x-2">
                                    <button wire:click="openFacultyModal({{ $faculty['id'] }})"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Edit
                                    </button>
                                    <button wire:click="deleteFaculty({{ $faculty['id'] }})"
                                            wire:confirm="Are you sure you want to delete this faculty member?"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="col-span-full text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No faculty members</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first faculty member.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Announcement Modal -->
    @if($showAnnouncementModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAnnouncementModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingAnnouncement ? 'Edit Announcement' : 'Add New Announcement' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="announcement_title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" id="announcement_title" wire:model="announcementForm.title"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('announcementForm.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="announcement_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select id="announcement_type" wire:model="announcementForm.type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="general">General</option>
                                    <option value="application">Application</option>
                                    <option value="scholarship">Scholarship</option>
                                    <option value="event">Event</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                @error('announcementForm.type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="announcement_content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                <textarea id="announcement_content" wire:model="announcementForm.content" rows="5"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                @error('announcementForm.content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="announcement_priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (0-10)</label>
                                <input type="number" id="announcement_priority" wire:model="announcementForm.priority" min="0" max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('announcementForm.priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="announcementForm.is_active"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveAnnouncement" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button wire:click="closeAnnouncementModal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Faculty Modal -->
    @if($showFacultyModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeFacultyModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingFaculty ? 'Edit Faculty Member' : 'Add New Faculty Member' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="faculty_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="faculty_name" wire:model="facultyForm.name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="e.g., Dr. Juan Dela Cruz">
                                @error('facultyForm.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="faculty_position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <input type="text" id="faculty_position" wire:model="facultyForm.position"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="e.g., Professor, Associate Professor">
                                @error('facultyForm.position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="faculty_specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                                <input type="text" id="faculty_specialization" wire:model="facultyForm.specialization"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="e.g., Agricultural Engineering, Water Resources">
                                @error('facultyForm.specialization') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="faculty_department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <input type="text" id="faculty_department" wire:model="facultyForm.department"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="e.g., Central Luzon State University">
                                @error('facultyForm.department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="faculty_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" id="faculty_sort_order" wire:model="facultyForm.sort_order" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('facultyForm.sort_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="faculty_photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                                <input type="file" id="faculty_photo" wire:model="facultyPhoto" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-1">Recommended size: 400x400px (JPG, PNG)</p>
                                @error('facultyPhoto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveFaculty" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button wire:click="closeFacultyModal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
