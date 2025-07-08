<?php



namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusUpdateController extends Controller
{
    public function index(Request $request)
    {
        $scholar = Auth::user();

        if (!$scholar || !$scholar->scholarProfile) {
            return ApiResponse::error('Unauthorized', 401);
        }

        // Get recent fund requests
        $fundRequests = FundRequest::where('scholar_profile_id', $scholar->scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'fund_request',
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                    'notes' => $request->notes
                ];
            });

        // Get recent manuscripts
        $manuscripts = Manuscript::where('scholar_profile_id', $scholar->scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($manuscript) {
                return [
                    'type' => 'manuscript',
                    'status' => $manuscript->status,
                    'created_at' => $manuscript->created_at,
                    'updated_at' => $manuscript->updated_at,
                    'notes' => $manuscript->notes
                ];
            });

        // Combine and sort by most recent
        $updates = $fundRequests->concat($manuscripts)
            ->sortByDesc('updated_at')
            ->values()
            ->take(10);

        return ApiResponse::success([
            'updates' => $updates
        ]);
    }
}
