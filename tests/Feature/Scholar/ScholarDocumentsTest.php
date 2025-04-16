<?php

namespace Tests\Feature\Scholar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ScholarDocumentsTest extends TestCase
{
    use WithFaker;

    /**
     * Test scholar can access documents index.
     */
    public function test_scholar_can_access_documents_index()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.documents.index'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can access document creation form.
     */
    public function test_scholar_can_access_document_creation()
    {
        // Find a scholar user
        $user = User::where('role', 'scholar')->first();

        if (!$user) {
            $this->markTestSkipped('No scholar user found in database');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.documents.create'));

        $response->assertStatus(200);
    }

    /**
     * Test scholar can upload a document.
     */
    public function test_scholar_can_upload_document()
    {
        // Find a scholar user with a profile
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 500);

        $documentData = [
            'file' => $file,
            'title' => $this->faker->sentence,
            'category' => 'Academic Transcript',
            'description' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($user)
                         ->post(route('scholar.documents.store'), $documentData);

        $response->assertRedirect();

        // Verify the document was created for this scholar
        $this->assertDatabaseHas('documents', [
            'scholar_profile_id' => $user->scholarProfile->id,
            'title' => $documentData['title'],
            'category' => $documentData['category'],
        ]);

        // Check the file was stored
        $document = Document::where('title', $documentData['title'])->first();
        if ($document && $document->file_path) {
            $this->assertTrue(Storage::disk('public')->exists($document->file_path));
        }
    }

    /**
     * Test scholar can view their own document.
     */
    public function test_scholar_can_view_own_document()
    {
        // Find a scholar user with documents
        $user = User::where('role', 'scholar')->first();

        if (!$user || !$user->scholarProfile) {
            $this->markTestSkipped('No scholar user with profile found');
        }

        // Get a document belonging to this scholar
        $document = Document::where('scholar_profile_id', $user->scholarProfile->id)->first();

        if (!$document) {
            $this->markTestSkipped('No documents found for scholar');
        }

        $response = $this->actingAs($user)
                         ->get(route('scholar.documents.show', $document->id));

        $response->assertStatus(200);
        $response->assertSee($document->title);
    }

    /**
     * Test scholar cannot view another scholar's document.
     */
    public function test_scholar_cannot_view_others_document()
    {
        // Find two different scholar users
        $scholars = User::where('role', 'scholar')->limit(2)->get();

        if (count($scholars) < 2) {
            $this->markTestSkipped('Need at least 2 scholar users for this test');
        }

        $scholar1 = $scholars[0];
        $scholar2 = $scholars[1];

        // Get a document belonging to scholar2
        $document = Document::where('scholar_profile_id', $scholar2->scholarProfile->id)->first();

        if (!$document) {
            $this->markTestSkipped('No documents found for second scholar');
        }

        // Try to access it as scholar1
        $response = $this->actingAs($scholar1)
                         ->get(route('scholar.documents.show', $document->id));

        $response->assertRedirect();  // Should redirect with unauthorized error
    }
}
