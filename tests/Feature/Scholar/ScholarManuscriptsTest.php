<?php

namespace Tests\Feature\Scholar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Manuscript;

class ScholarManuscriptsTest extends TestCase
{
    use WithFaker;

    /**
     * Test scholar can access manuscripts index.
     */
    public function test_scholar_can_access_manuscripts_index()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.manuscripts.index'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can access manuscript creation form.
     */
    public function test_scholar_can_access_manuscript_creation()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.manuscripts.create'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can create a manuscript.
     */
    public function test_scholar_can_create_manuscript()
    {
        // Find a scholar user with a profile
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        $manuscriptData = [
            'title' => $this->faker->sentence,
            'abstract' => $this->faker->paragraph,
            'manuscript_type' => 'journal',
            'co_authors' => $this->faker->name,
            'keywords' => $this->faker->words(3, true),
            'status' => 'draft'
        ];

        $response = $this->actingAs($user)
                         ->post(route('scholar.manuscripts.store'), $manuscriptData);

        $response->assertRedirect();

        // Verify the manuscript was created for this scholar
        $this->assertDatabaseHas('manuscripts', [
            'scholar_profile_id' => $user->scholarProfile->id,
            'title' => $manuscriptData['title'],
            'manuscript_type' => $manuscriptData['manuscript_type'],
        ]);
    }

    /**
     * Test scholar can view their own manuscript.
     */
    public function test_scholar_can_view_own_manuscript()
    {
        // Find a scholar user with manuscripts
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        // Get a manuscript belonging to this scholar
        $manuscript = Manuscript::where('scholar_profile_id', $user->scholarProfile->id)->first();

        if (!$manuscript) {
            $this->markTestSkipped('No manuscripts found for scholar');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.manuscripts.show', $manuscript->id));

        $response->assertStatus(200);
        $response->assertSee($manuscript->title);
    }

    /**
     * Test scholar cannot view another scholar's manuscript.
     */
    public function test_scholar_cannot_view_others_manuscript()
    {
        // Find two different scholar users
        $scholars = User::where('role', 'scholar')->limit(2)->get();

        if (count($scholars) < 2) {
            $this->markTestSkipped('Need at least 2 scholar users for this test');
        }

        $scholar1 = $scholars[0];
        $scholar2 = $scholars[1];

        // Get a manuscript belonging to scholar2
        $manuscript = Manuscript::where('scholar_profile_id', $scholar2->scholarProfile->id)->first();

        if (!$manuscript) {
            $this->markTestSkipped('No manuscripts found for second scholar');
        }

        // Try to access it as scholar1
        $response = $this->actingAs($scholar1)
                         ->get(route('scholar.manuscripts.show', $manuscript->id));

        $response->assertRedirect();  // Should redirect with unauthorized error
    }

    /**
     * Test scholar can submit a draft manuscript.
     */
    public function test_scholar_can_submit_manuscript()
    {
        // Find a scholar user with a draft manuscript
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        // Get a draft manuscript belonging to this scholar
        $manuscript = Manuscript::where('scholar_profile_id', $user->scholarProfile->id)
                      ->where('status', 'draft')
                      ->first();

        if (!$manuscript) {
            $this->markTestSkipped('No draft manuscripts found for scholar');
        }

        $response = $this->actingAs($user)
                         ->put(route('scholar.manuscripts.submit', $manuscript->id));

        $response->assertRedirect();

        // Verify the manuscript status was updated
        $manuscript->refresh();
        $this->assertEquals('submitted', $manuscript->status);
    }
}
