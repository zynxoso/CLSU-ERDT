<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Reset and base styles */
        body {
            font-family: theme(fontFamily.pdf);
            font-size: 12px;
            line-height: 1.4;
            color: rgb(64 64 64);
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
            background-color: rgb(34 197 94);
            color: rgb(255 255 255);
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

        .status-approved {
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
            color: rgb(115 115 115);
        }

        /* Copyright text */
        .copyright {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: rgb(115 115 115);
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
                                @elseif(in_array($fund->status, ['Submitted', 'Under Review'])) status-pending
                                @elseif($fund->status == 'Rejected') status-rejected
                                @endif">
                                {{ $fund->status }}
                            </span>
                        </td>
                        <td>{{ $fund->created_at->format('M d, Y') }}</td>
                        <td>{{ !in_array($fund->status, ['Submitted', 'Under Review']) ? $fund->updated_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: rgb(115 115 115); background-color: #FAFAFA; border-radius: 8px; border: 1px solid #E0E0E0;">
            No fund requests found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
