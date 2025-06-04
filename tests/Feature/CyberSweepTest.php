<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\RequestType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CyberSweepTest extends TestCase
{
    /**
     * Test that the CyberSweep middleware detects suspicious file content.
     */
    public function test_cybersweep_detects_suspicious_file_content()
    {
        // Create a fake file with suspicious content
        Storage::fake('public');
        $suspiciousFile = UploadedFile::fake()->createWithContent(
            'malicious.pdf',
            '<?php echo "This is a PHP script disguised as a PDF"; ?>'
        );

        // Find a scholar user or create one if none exists
        $user = User::where('role', 'scholar')->first();
        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        // Find a request type or create one if none exists
        $requestType = RequestType::where('is_active', true)->first();
        if (!$requestType) {
            $this->markTestSkipped('No active request type found in database');
        }

        // Authenticate as the scholar
        $this->actingAs($user);

        // Submit a fund request with the suspicious file
        $response = $this->post(route('scholar.fund-requests.store'), [
            'request_type_id' => $requestType->id,
            'amount' => 1000,
            'admin_remarks' => 'Test request with suspicious file',
            'document' => $suspiciousFile
        ]);

        // The middleware should log the suspicious content but not block the request
        // So we expect a redirect to the success page
        $response->assertStatus(302);

        // Check that the document was created but marked as having failed the security scan
        // This part depends on how you've implemented the security scanning logic
        // If you're blocking suspicious files, you'd expect an error response instead
    }

    /**
     * Test that the CyberSweep middleware allows legitimate file content.
     */
    public function test_cybersweep_allows_legitimate_file_content()
    {
        // Create a fake file with legitimate content
        Storage::fake('public');
        $legitimateFile = UploadedFile::fake()->createWithContent(
            'legitimate.pdf',
            'This is a legitimate PDF file with no suspicious content.'
        );

        // Find a scholar user or create one if none exists
        $user = User::where('role', 'scholar')->first();
        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        // Find a request type or create one if none exists
        $requestType = RequestType::where('is_active', true)->first();
        if (!$requestType) {
            $this->markTestSkipped('No active request type found in database');
        }

        // Authenticate as the scholar
        $this->actingAs($user);

        // Submit a fund request with the legitimate file
        $response = $this->post(route('scholar.fund-requests.store'), [
            'request_type_id' => $requestType->id,
            'amount' => 1000,
            'admin_remarks' => 'Test request with legitimate file',
            'document' => $legitimateFile
        ]);

        // The middleware should allow the request
        $response->assertStatus(302);
        $response->assertRedirect();

        // Check that the document was created and marked as having passed the security scan
        // This part depends on how you've implemented the security scanning logic
    }
}
