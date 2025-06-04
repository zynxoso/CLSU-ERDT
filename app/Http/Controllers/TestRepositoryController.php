<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\FundRequestRepository;
use Illuminate\Http\Request;

class TestRepositoryController extends Controller
{
    protected $userRepository;
    protected $fundRequestRepository;

    /**
     * TestRepositoryController constructor.
     *
     * @param UserRepository $userRepository
     * @param FundRequestRepository $fundRequestRepository
     */
    public function __construct(
        UserRepository $userRepository,
        FundRequestRepository $fundRequestRepository
    ) {
        $this->userRepository = $userRepository;
        $this->fundRequestRepository = $fundRequestRepository;
    }

    /**
     * Test the user repository
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testUserRepository()
    {
        // Get all users
        $users = $this->userRepository->all();

        // Get users by role
        $scholars = $this->userRepository->findByRole('scholar');

        return response()->json([
            'all_users_count' => $users->count(),
            'scholars_count' => $scholars->count(),
        ]);
    }

    /**
     * Test the fund request repository
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testFundRequestRepository()
    {
        // Get all fund requests
        $fundRequests = $this->fundRequestRepository->all();

        // Get pending fund requests
        $pendingRequests = $this->fundRequestRepository->getPendingRequests();

        return response()->json([
            'all_requests_count' => $fundRequests->count(),
            'pending_requests_count' => $pendingRequests->count(),
        ]);
    }
}
