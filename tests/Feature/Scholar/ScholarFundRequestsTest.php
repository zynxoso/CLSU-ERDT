<?php

namespace Tests\Feature\Scholar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\FundRequest;
use App\Models\RequestType;

class ScholarFundRequestsTest extends TestCase
{
    use WithFaker;

    /**
     * Test scholar can access fund requests index.
     */
    public function test_scholar_can_access_fund_requests_index()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.fund-requests'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can access fund request creation form.
     */
    public function test_scholar_can_access_fund_request_creation()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.fund-requests.create'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can create a fund request.
     */
    public function test_scholar_can_create_fund_request()
    {
        // Find a scholar user with a profile
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        // Check if we have request types
        $requestType = RequestType::first();

        if (!$requestType) {
            $this->markTestSkipped('No request types found in database');
        }

        $fundRequestData = [
            'request_type_id' => $requestType->id,
            'amount' => $this->faker->numberBetween(1000, 10000),
            'purpose' => $this->faker->sentence,
            'status' => 'Draft'
        ];

        $response = $this->actingAs($user)
                         ->post(route('scholar.fund-requests.store'), $fundRequestData);

        $response->assertRedirect();

        // Verify the fund request was created for this scholar
        $this->assertDatabaseHas('fund_requests', [
            'scholar_profile_id' => $user->scholarProfile->id,
            'amount' => $fundRequestData['amount'],
            'purpose' => $fundRequestData['purpose']
        ]);
    }

    /**
     * Test scholar can view their own fund request.
     */
    public function test_scholar_can_view_own_fund_request()
    {
        // Find a scholar user with fund requests
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        // Get a fund request belonging to this scholar
        $fundRequest = FundRequest::where('scholar_profile_id', $user->scholarProfile->id)->first();

        if (!$fundRequest) {
            $this->markTestSkipped('No fund requests found for scholar');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.fund-requests.show', $fundRequest->id));

        $response->assertStatus(200);
        $response->assertSee($fundRequest->purpose);
    }

    /**
     * Test scholar cannot view another scholar's fund request.
     */
    public function test_scholar_cannot_view_others_fund_request()
    {
        // Find two different scholar users
        $scholars = User::where('role', 'scholar')->limit(2)->get();

        if (count($scholars) < 2) {
            $this->markTestSkipped('Need at least 2 scholar users for this test');
        }

        $scholar1 = $scholars[0];
        $scholar2 = $scholars[1];

        // Get a fund request belonging to scholar2
        $fundRequest = FundRequest::where('scholar_profile_id', $scholar2->scholarProfile->id)->first();

        if (!$fundRequest) {
            $this->markTestSkipped('No fund requests found for second scholar');
        }

        // Try to access it as scholar1
        $response = $this->actingAs($scholar1)
                         ->get(route('scholar.fund-requests.show', $fundRequest->id));

        $response->assertRedirect();  // Should redirect with unauthorized error
    }
}
