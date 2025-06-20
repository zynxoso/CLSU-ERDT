@extends('layouts.app')

@section('title', 'Fund Requests')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">My Fund Requests</h1>
            <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> New Request
            </a>
        </div>

        @livewire('scholar.fund-request-filters')