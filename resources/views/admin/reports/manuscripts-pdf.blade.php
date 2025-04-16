<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Reset and base styles */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Layout */
        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Headers and text */
        h1 {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #000;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 20px;
            color: #000;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .meta {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
        }

        /* Summary section */
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 10px;
            justify-content: space-between;
        }

        .summary-item {
            flex: 1;
            min-width: 120px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }

        .summary-title {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }

        .status-draft {
            background-color: #E1F5FE;
            color: #0277BD;
        }

        .status-review {
            background-color: #FFF8E1;
            color: #FF8F00;
        }

        .status-approved {
            background-color: #E8F5E9;
            color: #2E7D32;
        }

        .status-published {
            background-color: #E8F5E9;
            color: #2E7D32;
        }

        .status-rejected {
            background-color: #FFEBEE;
            color: #C62828;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Reference number */
        .reference {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        /* Extra small text */
        .text-xs {
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>{{ $title }}</h1>
            <div class="meta">
                @if($startDate && $endDate)
                    Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}<br>
                @endif
                Generated on: {{ now()->format('M d, Y, h:i A') }}
            </div>
        </header>

        <!-- Summary Section -->
        <h2>Summary</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-title">Total Manuscripts</div>
                <div class="summary-value">{{ count($data) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-title">Drafts</div>
                <div class="summary-value">{{ $data->where('status', 'Outline Submitted')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-title">Under Review</div>
                <div class="summary-value">{{ $data->where('status', 'Under Review')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-title">Published</div>
                <div class="summary-value">{{ $data->where('status', 'Published')->count() }}</div>
            </div>
        </div>

        <!-- Manuscripts Table -->
        <h2>Manuscript List</h2>
        @if(count($data) > 0)
            <table>
                <thead>
                    <tr>
                        <th width="25%">Title</th>
                        <th width="20%">Author</th>
                        <th width="15%">Type</th>
                        <th width="15%">Status</th>
                        <th width="12%">Created</th>
                        <th width="13%">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $manuscript)
                        <tr>
                            <td>
                                {{ $manuscript->title }}
                                <div class="reference">{{ $manuscript->reference_number ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <strong>{{ $manuscript->scholarProfile && $manuscript->scholarProfile->user ? $manuscript->scholarProfile->user->name : 'Unknown' }}</strong>
                                <div class="text-xs">{{ $manuscript->scholarProfile && $manuscript->scholarProfile->user ? $manuscript->scholarProfile->user->email : 'N/A' }}</div>
                            </td>
                            <td>{{ $manuscript->manuscript_type ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge
                                    @if($manuscript->status == 'Published') status-published
                                    @elseif($manuscript->status == 'Accepted') status-approved
                                    @elseif($manuscript->status == 'Revision Requested') status-rejected
                                    @elseif($manuscript->status == 'Under Review') status-review
                                    @elseif($manuscript->status == 'Draft Submitted') status-draft
                                    @elseif($manuscript->status == 'Outline Approved') status-approved
                                    @endif">
                                    {{ $manuscript->status }}
                                </span>
                            </td>
                            <td>{{ $manuscript->created_at->format('M d, Y') }}</td>
                            <td>{{ $manuscript->updated_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 20px; color: #666;">
                No manuscripts found matching your criteria.
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Generated from CLSU-ERDT Scholar Management System<br>
            © {{ date('Y') }} Central Luzon State University
        </div>
    </div>
</body>
</html>
