<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class StatusUpdateController extends BaseController
{
    public function index(Request $request)
    {
        // Example data structure for fund request status updates
        $updates = [
            [
                'id' => 1,
                'status' => 'Approved',
                'history' => [
                    [
                        'status' => 'Submitted',
                        'timestamp' => now()->subDays(5)->toDateTimeString(),
                        'notes' => 'Request submitted.'
                    ],
                    [
                        'status' => 'Under Review',
                        'timestamp' => now()->subDays(3)->toDateTimeString(),
                        'notes' => 'Reviewed by admin.'
                    ],
                    [
                        'status' => 'Approved',
                        'timestamp' => now()->subDay()->toDateTimeString(),
                        'notes' => 'Approved by admin.'
                    ],
                ]
            ],
            [
                'id' => 2,
                'status' => 'Pending',
                'history' => [
                    [
                        'status' => 'Submitted',
                        'timestamp' => now()->subDays(2)->toDateTimeString(),
                        'notes' => 'Request submitted.'
                    ],
                    [
                        'status' => 'Under Review',
                        'timestamp' => now()->subDay()->toDateTimeString(),
                        'notes' => 'Under review by admin.'
                    ],
                ]
            ]
        ];
        return response()->json($updates);
    }
}
