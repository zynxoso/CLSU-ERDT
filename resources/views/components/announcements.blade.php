<!-- Announcements Section -->
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 py-8" id="announcements">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Latest Announcements</h2>
            <p class="text-gray-600">Stay updated with the latest news and important information</p>
        </div>

        <!-- Dynamic announcements from database -->
        <div class="max-w-4xl mx-auto space-y-4" x-data="{ expandedAnnouncement: null }">
            @forelse($announcements as $index => $announcement)
                <!-- Dynamic Announcement -->
                <div class="bg-white rounded-lg shadow-md border-l-4 overflow-hidden {{ str_replace(['bg-', 'text-'], ['border-', ''], explode(' ', $announcement->badge_color)[2] ?? 'border-gray-500') }}">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->badge_color }} mr-2">
                                        {{ $announcement->badge_label }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $announcement->created_at->format('F j, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $announcement->title }}</h3>

                                @if(strlen($announcement->content) > 200)
                                    <div x-show="expandedAnnouncement !== {{ $index }}">
                                        <p class="text-gray-700 mb-4">
                                            {{ Str::limit($announcement->content, 200) }}
                                        </p>
                                        <button @click="expandedAnnouncement = {{ $index }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Read more →
                                        </button>
                                    </div>
                                    <div x-show="expandedAnnouncement === {{ $index }}" x-transition>
                                        <p class="text-gray-700 mb-4">
                                            {{ $announcement->content }}
                                        </p>
                                        <button @click="expandedAnnouncement = null" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            ← Show less
                                        </button>
                                    </div>
                                @else
                                    <p class="text-gray-700 mb-4">
                                        {{ $announcement->content }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $announcement->badge_color }}">
                                        {{ ucfirst($announcement->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- No Announcements -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No announcements</h3>
                    <p class="mt-1 text-gray-500">Check back later for important updates and news.</p>
                </div>
            @endforelse

            <!-- View All Announcements -->
            @if($announcements->count() > 0)
                <div class="text-center mt-8">
                    <a href="#" onclick="alert('Full announcements archive coming soon!')"
                       class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        View All Announcements
                    </a>
                </div>
            @endif
        </div>
    </div>

    <footer class="bg-white text-black py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center mb-4">
                    <img src="{{ asset('storage/logo/erdt_logo.png') }}" alt="CLSU-ERDT Logo" class="h-10 mr-3">
                        <span class="logo-text font-bold text-xl ml-2 text-black">CLSU-ERDT</span>
                    </div>
                    <p class="text-gray-600 max-w-xs">Engineering Research and Development for Technology Scholarship Program at CLSU.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Home</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">How to Apply</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">About</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">History</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-white transition">FAQ</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Requirements</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Downloads</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-white transition">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span>Science City of Muñoz, Nueva Ecija, Philippines</span>
                            </li>
                            <li class="flex items-center " >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span>(044) 456-0123</span>
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span>erdt@clsu.edu.ph</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; 2023 CLSU-ERDT Scholarship Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>



<!-- JavaScript for announcements functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional announcement-specific JavaScript here
    console.log('Announcements component loaded');
});
</script>
