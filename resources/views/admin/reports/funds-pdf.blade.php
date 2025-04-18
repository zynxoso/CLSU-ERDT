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

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
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
    <!-- Funds Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th width="12%">Reference</th>
                    <th width="20%">Scholar</th>
                    <th width="20%">Purpose</th>
                    <th width="12%">Amount</th>
                    <th width="12%">Status</th>
                    <th width="12%">Submitted</th>
                    <th width="12%">Processed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $fund)
                    <tr>
                        <td>{{ $fund->reference_number ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $fund->user->name ?? 'Unknown' }}</strong>
                            <div class="text-xs">{{ $fund->user->email ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $fund->purpose }}</td>
                        <td>₱{{ number_format($fund->amount, 2) }}</td>
                        <td>
                            <span class="status-badge
                                @if($fund->status == 'Approved') status-approved
                                @elseif($fund->status == 'Pending') status-pending
                                @elseif($fund->status == 'Rejected') status-rejected
                                @endif">
                                {{ $fund->status }}
                            </span>
                        </td>
                        <td>{{ $fund->created_at->format('M d, Y') }}</td>
                        <td>{{ $fund->status != 'Pending' ? $fund->updated_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #666; background-color: #f9f9f9; border-radius: 8px;">
            No fund requests found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
