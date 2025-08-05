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

        .status-active {
            background-color: #E8F5E8;
            color: #1B5E20;
        }

        .status-pending {
            background-color: #FFF3C4;
            color: #F57F17;
        }

        .status-graduated {
            background-color: #E3F2FD;
            color: #4A90E2;
        }

        .status-discontinued {
            background-color: #FFEBEE;
            color: #B71C1C;
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
    <!-- Scholar Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th width="18%">Name</th>
                    <th width="22%">Email</th>
                    <th width="20%">Course</th>
                    <th width="15%">Intended University</th>
                    <th width="8%">Status</th>
                    <th width="8%">Start Year</th>
                    <th width="9%">Expected Completion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $scholar)
                    <tr>
                        <td>
                            <strong>{{ $scholar->full_name ?? 'Unknown' }}</strong>
                        </td>
                        <td>{{ $scholar->user ? $scholar->user->email : 'N/A' }}</td>
                        <td>{{ $scholar->course ?? 'N/A' }}</td>
                        <td>{{ $scholar->intended_university ?? 'N/A' }}</td>
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
                        'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #757575; background-color: #FAFAFA; border-radius: 8px; border: 1px solid #E0E0E0;">
            No scholars found matching your criteria.
        </div>
    @endif

    <div class="copyright">
        © {{ date('Y') }} Central Luzon State University
    </div>
</body>
</html>
