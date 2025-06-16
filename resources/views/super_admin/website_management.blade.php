@extends('layouts.app')

@section('title', 'Website Management')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mx-auto py-6" x-data="websiteManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Website Management</h1>
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'content'"
                    :class="activeTab === 'content' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Content Management
            </button>
            <button @click="activeTab = 'announcements'"
                    :class="activeTab === 'announcements' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Announcements
            </button>
            <button @click="activeTab = 'faculty'"
                    :class="activeTab === 'faculty' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Faculty & Expertise
            </button>
        </nav>
    </div>

    <!-- Content Management Tab -->
    <div x-show="activeTab === 'content'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Website Content Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage the content displayed on the public scholar website pages</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Hero Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Hero Section</h3>
                        <div>
                            <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-1">Main Title</label>
                            <input type="text" id="hero_title" name="hero_title"
                                   x-model="content.hero_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Welcome to CLSU-ERDT">
                        </div>
                        <div>
                            <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                            <textarea id="hero_subtitle" name="hero_subtitle" rows="3"
                                      x-model="content.hero_subtitle"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Engineering Research and Development for Technology"></textarea>
                        </div>
                        <div>
                            <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">Hero Background Image</label>
                            <input type="file" id="hero_image" name="hero_image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-xs text-gray-500 mt-1">Recommended size: 1920x1080px</p>
                        </div>
                    </div>

                    <!-- About Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">About Section</h3>
                        <div>
                            <label for="about_title" class="block text-sm font-medium text-gray-700 mb-1">About Title</label>
                            <input type="text" id="about_title" name="about_title"
                                   x-model="content.about_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="About CLSU-ERDT">
                        </div>
                        <div>
                            <label for="about_description" class="block text-sm font-medium text-gray-700 mb-1">About Description</label>
                            <textarea id="about_description" name="about_description" rows="5"
                                      x-model="content.about_description"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Describe CLSU-ERDT program..."></textarea>
                        </div>
                    </div>

                    <!-- Vision & Mission -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Vision & Mission</h3>
                        <div>
                            <label for="vision" class="block text-sm font-medium text-gray-700 mb-1">Vision</label>
                            <textarea id="vision" name="vision" rows="3"
                                      x-model="content.vision"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Our vision statement..."></textarea>
                        </div>
                        <div>
                            <label for="mission" class="block text-sm font-medium text-gray-700 mb-1">Mission</label>
                            <textarea id="mission" name="mission" rows="3"
                                      x-model="content.mission"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Our mission statement..."></textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="contact_email" name="contact_email"
                                   x-model="content.contact_email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="erdt@clsu.edu.ph">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="contact_phone" name="contact_phone"
                                   x-model="content.contact_phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="0920-9312126">
                        </div>
                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="contact_address" name="contact_address" rows="3"
                                      x-model="content.contact_address"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="CLSU-ERDT Office, Engineering Building..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="saveContent()" type="button"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Content Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Tab -->
    <div x-show="activeTab === 'announcements'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Announcements Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Create and manage announcements for the scholar website</p>
                </div>
                <button @click="showAnnouncementModal = true; editingAnnouncement = null; resetAnnouncementForm()"
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
                    <template x-for="announcement in announcements" :key="announcement.id">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900" x-text="announcement.title"></h3>
                                    <p class="text-sm text-gray-600 mt-1" x-text="announcement.content.substring(0, 150) + '...'"></p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <span x-text="'Type: ' + announcement.type"></span>
                                        <span class="mx-2">•</span>
                                        <span x-text="'Created: ' + new Date(announcement.created_at).toLocaleDateString()"></span>
                                        <span class="mx-2">•</span>
                                        <span :class="announcement.is_active ? 'text-green-600' : 'text-red-600'"
                                              x-text="announcement.is_active ? 'Active' : 'Inactive'"></span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <button @click="editAnnouncement(announcement)"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Edit
                                    </button>
                                    <button @click="toggleAnnouncementStatus(announcement)"
                                            class="text-green-600 hover:text-green-800 text-sm"
                                            x-text="announcement.is_active ? 'Deactivate' : 'Activate'">
                                    </button>
                                    <button @click="deleteAnnouncement(announcement.id)"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="announcements.length === 0" class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0v10a2 2 0 002 2h6a2 2 0 002-2V8m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first announcement.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty & Expertise Tab -->
    <div x-show="activeTab === 'faculty'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Faculty & Expertise Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage faculty profiles and expertise displayed on the about page</p>
                </div>
                <button @click="showFacultyModal = true; editingFaculty = null; resetFacultyForm()"
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
                    <template x-for="faculty in facultyMembers" :key="faculty.id">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="text-center">
                                <div class="mb-4">
                                    <img x-show="faculty.photo" :src="faculty.photo" :alt="faculty.name"
                                         class="w-20 h-20 rounded-full mx-auto object-cover">
                                    <div x-show="!faculty.photo" class="w-20 h-20 rounded-full mx-auto bg-gray-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900" x-text="faculty.name"></h3>
                                <p class="text-sm text-indigo-600 mb-2" x-text="faculty.position"></p>
                                <p class="text-xs text-gray-600 mb-3" x-text="faculty.specialization"></p>
                                <div class="flex justify-center space-x-2">
                                    <button @click="editFaculty(faculty)"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Edit
                                    </button>
                                    <button @click="deleteFaculty(faculty.id)"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="facultyMembers.length === 0" class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No faculty members</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding your first faculty member.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div x-show="showAnnouncementModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showAnnouncementModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showAnnouncementModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingAnnouncement ? 'Edit Announcement' : 'Add New Announcement'"></h3>

                    <div class="space-y-4">
                        <div>
                            <label for="announcement_title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="announcement_title" x-model="announcementForm.title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="announcement_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="announcement_type" x-model="announcementForm.type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="general">General</option>
                                <option value="application">Application</option>
                                <option value="scholarship">Scholarship</option>
                                <option value="event">Event</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label for="announcement_content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea id="announcement_content" x-model="announcementForm.content" rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label for="announcement_priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (0-10)</label>
                            <input type="number" id="announcement_priority" x-model="announcementForm.priority" min="0" max="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="announcementForm.is_active"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="saveAnnouncement()" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button @click="showAnnouncementModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Modal -->
    <div x-show="showFacultyModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showFacultyModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showFacultyModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingFaculty ? 'Edit Faculty Member' : 'Add New Faculty Member'"></h3>

                    <div class="space-y-4">
                        <div>
                            <label for="faculty_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="faculty_name" x-model="facultyForm.name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="faculty_position" class="block text-sm font-medium text-gray-700 mb-1">Position/Title</label>
                            <input type="text" id="faculty_position" x-model="facultyForm.position"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="e.g., Professor, Associate Professor">
                        </div>

                        <div>
                            <label for="faculty_specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                            <input type="text" id="faculty_specialization" x-model="facultyForm.specialization"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="e.g., Agricultural Engineering, Water Resources">
                        </div>

                        <div>
                            <label for="faculty_education" class="block text-sm font-medium text-gray-700 mb-1">Education Background</label>
                            <textarea id="faculty_education" x-model="facultyForm.education" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Ph.D from University, M.S. from..."></textarea>
                        </div>

                        <div>
                            <label for="faculty_description" class="block text-sm font-medium text-gray-700 mb-1">Research Description</label>
                            <textarea id="faculty_description" x-model="facultyForm.description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Brief description of research focus and expertise..."></textarea>
                        </div>

                        <div>
                            <label for="faculty_photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                            <input type="file" id="faculty_photo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-xs text-gray-500 mt-1">Recommended size: 400x400px</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="saveFaculty()" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button @click="showFacultyModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function websiteManagement() {
    return {
        activeTab: 'content',
        showAnnouncementModal: false,
        showFacultyModal: false,
        editingAnnouncement: null,
        editingFaculty: null,

        // Content Management Data
        content: {
            hero_title: 'Welcome to CLSU-ERDT',
            hero_subtitle: 'Engineering Research and Development for Technology Scholarship Program',
            about_title: 'About CLSU-ERDT',
            about_description: 'The Engineering Research and Development for Technology (ERDT) program...',
            vision: 'To be a leading center for engineering research and development...',
            mission: 'To provide comprehensive financial assistance and mentorship...',
            contact_email: 'erdt@clsu.edu.ph',
            contact_phone: '0920-9312126',
            contact_address: 'CLSU-ERDT Office, Engineering Building\nCentral Luzon State University\nScience City of Muñoz, Nueva Ecija'
        },

        // Announcements Data - Load from server
        announcements: @json($announcements ?? []),

        announcementForm: {
            title: '',
            content: '',
            type: 'general',
            is_active: true,
            priority: 0
        },

        // Faculty Data
        facultyMembers: [
            {
                id: 1,
                name: 'Dr. Alejandro Robles',
                position: 'Professor, Biological and Agricultural Engineering',
                specialization: 'Land, Air, Water Resources and Environmental Engineering',
                education: 'Ph.D from Washington State University',
                description: 'Research focuses on sustainable water management systems and environmental impact assessment of agricultural practices.',
                photo: null
            },
            {
                id: 2,
                name: 'Dr. Maria Santos',
                position: 'Associate Professor, Biological and Agricultural Engineering',
                specialization: 'Machinery systems, precision agriculture, soil and water management',
                education: 'Ph.D from Kansas State University',
                description: 'Develops innovative agricultural mechanization solutions for small-scale farmers in the Philippines.',
                photo: null
            }
        ],

        facultyForm: {
            name: '',
            position: '',
            specialization: '',
            education: '',
            description: '',
            photo: null
        },

        // Methods
        saveContent() {
            // Here you would make an API call to save the content
            alert('Content saved successfully!');
        },

        editAnnouncement(announcement) {
            this.editingAnnouncement = announcement;
            this.announcementForm = { ...announcement };
            this.showAnnouncementModal = true;
        },

        resetAnnouncementForm() {
            this.announcementForm = {
                title: '',
                content: '',
                type: 'general',
                is_active: true,
                priority: 0
            };
        },

        async saveAnnouncement() {
            try {
                const url = this.editingAnnouncement
                    ? `{{ route('super_admin.announcements.update', '') }}/${this.editingAnnouncement.id}`
                    : '{{ route("super_admin.announcements.store") }}';

                const method = this.editingAnnouncement ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.announcementForm)
                });

                const data = await response.json();

                if (data.success) {
                    if (this.editingAnnouncement) {
                        // Update existing announcement
                        const index = this.announcements.findIndex(a => a.id === this.editingAnnouncement.id);
                        this.announcements[index] = data.announcement;
                    } else {
                        // Add new announcement
                        this.announcements.unshift(data.announcement);
                    }

                    this.showAnnouncementModal = false;
                    this.resetAnnouncementForm();
                    alert('Announcement saved successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error saving announcement:', error);
                alert('An error occurred while saving the announcement.');
            }
        },

        async toggleAnnouncementStatus(announcement) {
            try {
                const response = await fetch(`{{ route('super_admin.announcements.toggle_status', '') }}/${announcement.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    announcement.is_active = data.announcement.is_active;
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error toggling announcement status:', error);
                alert('An error occurred while updating the announcement status.');
            }
        },

        async deleteAnnouncement(id) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                try {
                    const response = await fetch(`{{ route('super_admin.announcements.delete', '') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.announcements = this.announcements.filter(a => a.id !== id);
                        alert('Announcement deleted successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error deleting announcement:', error);
                    alert('An error occurred while deleting the announcement.');
                }
            }
        },

        editFaculty(faculty) {
            this.editingFaculty = faculty;
            this.facultyForm = { ...faculty };
            this.showFacultyModal = true;
        },

        resetFacultyForm() {
            this.facultyForm = {
                name: '',
                position: '',
                specialization: '',
                education: '',
                description: '',
                photo: null
            };
        },

        saveFaculty() {
            if (this.editingFaculty) {
                // Update existing faculty
                const index = this.facultyMembers.findIndex(f => f.id === this.editingFaculty.id);
                this.facultyMembers[index] = {
                    ...this.editingFaculty,
                    ...this.facultyForm
                };
            } else {
                // Add new faculty
                const newFaculty = {
                    id: Date.now(),
                    ...this.facultyForm
                };
                this.facultyMembers.push(newFaculty);
            }
            this.showFacultyModal = false;
            this.resetFacultyForm();
        },

        deleteFaculty(id) {
            if (confirm('Are you sure you want to delete this faculty member?')) {
                this.facultyMembers = this.facultyMembers.filter(f => f.id !== id);
            }
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
