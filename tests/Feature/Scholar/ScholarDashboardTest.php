<?php

namespace Tests\Feature\Scholar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ScholarProfile;

class ScholarDashboardTest extends TestCase
{
    use WithFaker;

    /**
     * Test scholar dashboard access.
     */
    public function test_scholar_can_access_dashboard()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('scholar.dashboard');
    }

    /**
     * Test admin cannot access scholar dashboard.
     */
    public function test_admin_cannot_access_scholar_dashboard()
    {
        // Find an admin user
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->markTestSkipped('No admin user found in database');
        }

        $response = $this->actingAs($admin)
                         ->get(route('scholar.dashboard'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test profile section on dashboard.
     */
    public function test_scholar_dashboard_shows_profile_info()
    {
        // Find a scholar user with a profile
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.dashboard'));

        $response->assertStatus(200);
        $response->assertSee($user->scholarProfile->intended_university);
        $response->assertSee($user->scholarProfile->department);
    }

    /**
     * Test fund request section on dashboard.
     */
    public function test_scholar_dashboard_shows_fund_requests()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Fund Request Summary');
    }
}
