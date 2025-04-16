<?php

namespace Tests\Feature\Scholar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ScholarProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ScholarProfileTest extends TestCase
{
    use WithFaker;

    /**
     * Test scholar can view profile.
     */
    public function test_scholar_can_view_profile()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.profile'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can access profile edit form.
     */
    public function test_scholar_can_access_profile_edit()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.profile.edit'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can update profile.
     */
    public function test_scholar_can_update_profile()
    {
        // Find a scholar user with a profile
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        $profileData = [
            'university' => 'Central Luzon State University',
            'department' => 'Computer Science',
            'program' => 'Master in Information Technology',
            'student_id' => $this->faker->numerify('2023########'),
            'phone' => '09123456789',
            'address' => $this->faker->address,
        ];

        $response = $this->actingAs($user)
                         ->post(route('scholar.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify the data was updated
        $user->scholarProfile->refresh();
        $this->assertEquals($profileData['university'], $user->scholarProfile->university);
        $this->assertEquals($profileData['department'], $user->scholarProfile->department);
        $this->assertEquals($profileData['program'], $user->scholarProfile->program);
    }

    /**
     * Test scholar can access change password form.
     */
    public function test_scholar_can_access_change_password()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.password.change'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can update password.
     */
    public function test_scholar_can_update_password()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        // Store original password hash
        $originalPassword = $user->password;

        // Update password
        $response = $this->actingAs($user)
                         ->post(route('scholar.password.update'), [
                             'current_password' => 'password', // Assuming default password in test environment
                             'new_password' => 'newpassword123',
                             'new_password_confirmation' => 'newpassword123',
                         ]);

        $response->assertRedirect(route('scholar.profile'));
        $response->assertSessionHas('success');

        // Refresh user from database
        $user->refresh();

        // Verify password was changed
        $this->assertNotEquals($originalPassword, $user->password);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test scholar cannot update password with incorrect current password.
     */
    public function test_scholar_cannot_update_password_with_incorrect_current_password()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        // Store original password hash
        $originalPassword = $user->password;

        // Attempt to update password with wrong current password
        $response = $this->actingAs($user)
                         ->post(route('scholar.password.update'), [
                             'current_password' => 'wrongpassword',
                             'new_password' => 'newpassword123',
                             'new_password_confirmation' => 'newpassword123',
                         ]);

        $response->assertSessionHasErrors('current_password');

        // Refresh user from database
        $user->refresh();

        // Verify password was not changed
        $this->assertEquals($originalPassword, $user->password);
    }
}
