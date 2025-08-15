@extends('layouts.app')

@section('title', 'Content Management')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="mb-6 bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Content Management</h1>
                    <p class="text-sm text-gray-600">Manage announcements, faculty, forms, and timelines</p>
                </div>
                <div class="hidden md:block">
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100 border-2 border-green-500">
                        <span class="text-lg font-bold text-green-600">C</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Content Overview</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <!-- Announcements -->
                <a href="{{ route('admin.content-management.announcements.index') }}"
                    class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-600">Announcements</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['announcements']['active'] }}</div>
                        <div class="mt-1 text-xs text-gray-500">Active ({{ $stats['announcements']['total'] }} total)</div>
                    </div>
                </a>

                <!-- Faculty -->
                <a href="{{ route('admin.content-management.faculty.index') }}"
                    class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-600">Faculty</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['faculty']['active'] }}</div>
                        <div class="mt-1 text-xs text-gray-500">Active ({{ $stats['faculty']['total'] }} total)</div>
                    </div>
                </a>

                <!-- Forms -->
                <a href="{{ route('admin.content-management.forms.index') }}"
                    class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-600">Forms</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['forms']['active'] }}</div>
                        <div class="mt-1 text-xs text-gray-500">Active ({{ $stats['forms']['total'] }} total)</div>
                    </div>
                </a>

                <!-- Timelines -->
                <a href="{{ route('admin.content-management.timelines.index') }}"
                    class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-600">Timelines</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['timelines']['active'] }}</div>
                        <div class="mt-1 text-xs text-gray-500">Active ({{ $stats['timelines']['total'] }} total)</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Content Management Cards -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Content Management</h3>
                <p class="text-gray-500 text-sm">Click on any card above to manage specific content types. Each section
                    provides dedicated tools for creating, editing, and organizing your content.</p>
            </div>
        </div>
    </div>
@endsection
