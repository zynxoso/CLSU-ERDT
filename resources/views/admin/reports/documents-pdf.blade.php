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
            color: #424242;
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
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border: 1px solid #E0E0E0;
        }

        td {
            padding: 10px;
            border: 1px solid #E0E0E0;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #FAFAFA;
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

        .status-verified {
            background-color: #E8F5E8;
            color: #1B5E20;
        }

        .status-pending {
            background-color: #FFF3C4;
            color: #F57F17;
        }

        .status-rejected {
            background-color: #FFEBEE;
            color: #B71C1C;
        }

        /* Extra small text */
        .text-xs {
            font-size: 9px;
            color: #757575;
        }

        /* Copyright text */
        .copyright {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #757575;
        }
    </style>
</head>
<body>
    <!-- Documents Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th width="20%">Document Title</th>
                    <th width="20%">Scholar</th>
                    <th width="15%">Document Type</th>
                    <th width="10%">File Type</th>
                    <th width="12%">Status</th>
                    <th width="12%">Submitted</th>
                    <th width="11%">Verified</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>
                            <strong>{{ $document->scholarProfile && $document->scholarProfile->user ? $document->scholarProfile->user->name : 'Unknown' }}</strong>
                            <div class="text-xs">{{ $document->scholarProfile && $document->scholarProfile->user ? $document->scholarProfile->user->email : 'N/A' }}</div>
                        </td>
                        <td>{{ $document->document_type }}</td>
                        <td>{{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}</td>
                        <td>
                            <span class="status-badge
                                @if($document->status == 'Verified') status-verified
                                @elseif($document->status == 'Pending') status-pending
                                @elseif($document->status == 'Rejected') status-rejected
                                @endif">
                                {{ $document->status }}
                            </span>
                        </td>
                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                        <td>{{ $document->status != 'Pending' ? $document->updated_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #757575; background-color: #FAFAFA; border-radius: 8px; border: 1px solid #E0E0E0;">
            No documents found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
