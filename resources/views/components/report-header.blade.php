@props([
    'title',
    'startDate' => null,
    'endDate' => null,
    'backRoute' => 'admin.reports.index',
    'showPrintButton' => true
])

<div class="reports-header print:hidden">
    <a href="{{ route($backRoute) }}" class="reports-back-link">
        <i class="fas fa-arrow-left mr-2"></i> Back to Reports
    </a>
    <h1 class="reports-title">{{ $title }}</h1>
    @if($startDate && $endDate)
        <p class="reports-period">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
    @endif
</div>

<div class="reports-card">
    <div class="reports-card-header print:bg-white">
        <h2 class="reports-card-title">{{ $slot }}</h2>
        @if($showPrintButton)
            <div class="print:hidden">
                <button onclick="window.print()" class="reports-print-btn">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
        @endif
    </div>

    <div class="print:mt-8">
        <!-- Print Header - Only visible when printing -->
        <div class="reports-print-header print:block">
            <h1 class="reports-print-title">{{ $title }}</h1>
            @if($startDate && $endDate)
                <p class="reports-print-period">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
            @endif
            <p class="reports-print-generated">Generated on: {{ now()->format('M d, Y, h:i A') }}</p>
        </div>

        {{ $content ?? '' }}
    </div>
</div>