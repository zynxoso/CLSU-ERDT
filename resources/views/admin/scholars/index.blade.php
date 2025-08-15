@extends('layouts.app')
@section('title', 'Manage Scholars')
@push('styles')
    <style>
        .nav-link{@apply transition-all duration-200 ease-in-out relative overflow-hidden;}
        .nav-link::before{content:'';@apply absolute left-0 w-1 h-full bg-green-700 transform -translate-x-full transition-transform duration-200;}
        .nav-link:hover::before,.nav-link.active::before{@apply translate-x-0;}
        .nav-link.active{@apply bg-green-50 text-green-700 font-medium;}
        .nav-link:focus{@apply outline-none ring-2 ring-green-500 ring-offset-2 ring-offset-white;}
    </style>
@endpush

@section('content')
    <livewire:admin.manage-scholars />
@endsection
