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

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-graduated {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-discontinued {
            background-color: #fee2e2;
            color: #b91c1c;
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
    <!-- Scholar Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th width="18%">Name</th>
                    <th width="22%">Email</th>
                    <th width="20%">Program</th>
                    <th width="15%">University</th>
                    <th width="8%">Status</th>
                    <th width="8%">Start Date</th>
                    <th width="9%">Expected Completion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $scholar)
                    <tr>
                        <td>
                            <strong>{{ $scholar->user ? $scholar->user->name : 'Unknown' }}</strong>
                        </td>
                        <td>{{ $scholar->user ? $scholar->user->email : 'N/A' }}</td>
                        <td>{{ $scholar->program ?? 'N/A' }}</td>
                        <td>{{ $scholar->university ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge
                                @if($scholar->status == 'Active') status-active
                                @elseif($scholar->status == 'Pending') status-pending
                                @elseif($scholar->status == 'Graduated') status-graduated
                                @elseif($scholar->status == 'Discontinued') status-discontinued
                                @endif">
                                {{ $scholar->status }}
                            </span>
                        </td>
                        <td>{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'N/A' }}</td>
                        <td>{{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #666; background-color: #f9f9f9; border-radius: 8px;">
            No scholars found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
