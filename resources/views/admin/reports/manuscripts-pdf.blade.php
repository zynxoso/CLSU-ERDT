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

        /* Enhanced table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 11px;
        }

        th {
            background-color: #2563eb;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }

        .status-draft {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-review {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-published {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
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

        /* Copyright text */
        .copyright {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Manuscripts Table -->
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
        <div style="text-align: center; padding: 20px; color: #666; background-color: #f9f9f9; border-radius: 8px;">
            No manuscripts found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
