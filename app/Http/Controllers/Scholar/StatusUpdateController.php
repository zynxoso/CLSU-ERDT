<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Para sa mga updates ng scholar
 */
class StatusUpdateController extends Controller
{
    /**
     * Kunin ang mga bagong updates
     */
    public function index(Request $request)
    {
        // Kunin ang user na naka-login
        $scholar = Auth::user();

        // Check kung may scholar
        if (!$scholar || !$scholar->scholarProfile) {
            return ApiResponse::error('Unauthorized', 401);
        }

        // Kunin ang mga fund request
        $fundRequests = FundRequest::withBasicRelations()
            ->where('scholar_profile_id', $scholar->scholarProfile->id)
            ->orderBy('created_at', 'desc') // Bago muna
            ->take(5) // 5 lang
            ->get()
            ->map(function ($request) {
                // Gawing array
                return [
                    'type' => 'fund_request',
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                    'notes' => $request->notes
                ];
            });

        // Kunin ang mga manuscript
        $manuscripts = Manuscript::withBasicRelations()
            ->where('scholar_profile_id', $scholar->scholarProfile->id)
            ->orderBy('created_at', 'desc') // Bago muna
            ->take(5) // 5 lang
            ->get()
            ->map(function ($manuscript) {
                // Gawing array
                return [
                    'type' => 'manuscript',
                    'status' => $manuscript->status,
                    'created_at' => $manuscript->created_at,
                    'updated_at' => $manuscript->updated_at,
                    'notes' => $manuscript->notes
                ];
            });

        // Pagsamahin lahat
        $updates = $fundRequests->concat($manuscripts) // Sama-sama
            ->sortByDesc('updated_at') // Ayusin
            ->values() // Reset
            ->take(10); // 10 lang

        // Ibalik ang result
        return ApiResponse::success([
            'updates' => $updates
        ]);
    }
}
