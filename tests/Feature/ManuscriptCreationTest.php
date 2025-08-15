<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;
use App\Models\Manuscript;
use App\Services\FundRequestService;
use App\Services\ManuscriptService;

class ManuscriptCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed request types
        RequestType::create([
            'name' => 'Research Dissemination Grant',
            'description' => 'Grant for research publication and conference presentation',
            'max_amount_masters' => 30000.00,
            'max_amount_doctoral' => 50000.00,
            'is_active' => true,
            'is_requestable' => true,
        ]);

        RequestType::create([
            'name' => 'Research Equipment Grant',
            'description' => 'Grant for research equipment and tools',
            'max_amount_masters' => 35000.00,
            'max_amount_doctoral' => 50000.00,
            'is_active' => true,
            'is_requestable' => true,
        ]);
    }

    public function test_manuscript_created_for_research_dissemination_grant()
    {
        // Create a scholar user
        $user = User::factory()->create(['role' => 'scholar']);
        $scholarProfile = ScholarProfile::factory()->create(['user_id' => $user->id]);

        // Create a Research Dissemination Grant request type
        $requestType = RequestType::where('name', 'Research Dissemination Grant')->first();

        // Create a fund request
        $fundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $requestType->id,
            'amount' => 25000.00,
            'purpose' => 'Conference presentation',
            'status' => 'Submitted',
        ]);

        // Approve the fund request (this should trigger manuscript creation)
        $fundRequestService = app(FundRequestService::class);
        $fundRequestService->approveFundRequest($fundRequest, $user->id);

        // Refresh the fund request to get the latest data
        $fundRequest->refresh();

        // Assert manuscript was created
        $this->assertNotNull($fundRequest->manuscript);
        $this->assertEquals('Final', $fundRequest->manuscript->manuscript_type);
        $this->assertEquals($scholarProfile->id, $fundRequest->manuscript->scholar_profile_id);
        $this->assertEquals($fundRequest->id, $fundRequest->manuscript->fund_request_id);
    }

    public function test_manuscript_created_for_research_equipment_grant()
    {
        // Create a scholar user
        $user = User::factory()->create(['role' => 'scholar']);
        $scholarProfile = ScholarProfile::factory()->create(['user_id' => $user->id]);

        // Create a Research Equipment Grant request type
        $requestType = RequestType::where('name', 'Research Equipment Grant')->first();

        // Create a fund request
        $fundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $requestType->id,
            'amount' => 30000.00,
            'purpose' => 'Laboratory equipment',
            'status' => 'Submitted',
        ]);

        // Approve the fund request (this should trigger manuscript creation)
        $fundRequestService = app(FundRequestService::class);
        $fundRequestService->approveFundRequest($fundRequest, $user->id);

        // Refresh the fund request to get the latest data
        $fundRequest->refresh();

        // Assert manuscript was created
        $this->assertNotNull($fundRequest->manuscript);
        $this->assertEquals('Outline', $fundRequest->manuscript->manuscript_type);
        $this->assertEquals($scholarProfile->id, $fundRequest->manuscript->scholar_profile_id);
        $this->assertEquals($fundRequest->id, $fundRequest->manuscript->fund_request_id);
    }

    public function test_no_manuscript_created_for_other_request_types()
    {
        // Create a scholar user
        $user = User::factory()->create(['role' => 'scholar']);
        $scholarProfile = ScholarProfile::factory()->create(['user_id' => $user->id]);

        // Create a different request type
        $requestType = RequestType::create([
            'name' => 'Tuition Fee',
            'description' => 'Tuition fee support',
            'max_amount_masters' => 50000.00,
            'max_amount_doctoral' => 80000.00,
            'is_active' => true,
            'is_requestable' => true,
        ]);

        // Create a fund request
        $fundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $requestType->id,
            'amount' => 40000.00,
            'purpose' => 'Tuition payment',
            'status' => 'Submitted',
        ]);

        // Approve the fund request
        $fundRequestService = app(FundRequestService::class);
        $fundRequestService->approveFundRequest($fundRequest, $user->id);

        // Refresh the fund request to get the latest data
        $fundRequest->refresh();

        // Assert no manuscript was created
        $this->assertNull($fundRequest->manuscript);
    }

    public function test_manuscript_service_creates_correct_manuscript_type()
    {
        $manuscriptService = app(ManuscriptService::class);

        // Create a scholar user
        $user = User::factory()->create(['role' => 'scholar']);
        $scholarProfile = ScholarProfile::factory()->create(['user_id' => $user->id]);

        // Test Research Dissemination Grant
        $disseminationRequestType = RequestType::where('name', 'Research Dissemination Grant')->first();
        $disseminationFundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $disseminationRequestType->id,
            'amount' => 25000.00,
            'purpose' => 'Conference presentation',
            'status' => FundRequest::STATUS_APPROVED,
        ]);

        $manuscript1 = $manuscriptService->createManuscriptFromFundRequest($disseminationFundRequest);
        $this->assertEquals('Final', $manuscript1->manuscript_type);

        // Test Research Equipment Grant
        $equipmentRequestType = RequestType::where('name', 'Research Equipment Grant')->first();
        $equipmentFundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $equipmentRequestType->id,
            'amount' => 30000.00,
            'purpose' => 'Laboratory equipment',
            'status' => FundRequest::STATUS_APPROVED,
        ]);

        $manuscript2 = $manuscriptService->createManuscriptFromFundRequest($equipmentFundRequest);
        $this->assertEquals('Outline', $manuscript2->manuscript_type);
    }

    public function test_documents_copied_from_fund_request_to_manuscript()
    {
        // Create a scholar user
        $user = User::factory()->create(['role' => 'scholar']);
        $scholarProfile = ScholarProfile::factory()->create(['user_id' => $user->id]);

        // Create a Research Dissemination Grant request type
        $requestType = RequestType::where('name', 'Research Dissemination Grant')->first();

        // Create a fund request
        $fundRequest = FundRequest::create([
            'scholar_profile_id' => $scholarProfile->id,
            'request_type_id' => $requestType->id,
            'amount' => 25000.00,
            'purpose' => 'Conference presentation',
            'status' => 'Submitted',
        ]);

        // Create some documents for the fund request
        $document1 = \App\Models\Document::create([
            'scholar_profile_id' => $scholarProfile->id,
            'fund_request_id' => $fundRequest->id,
            'file_name' => 'research_proposal.pdf',
            'file_path' => 'documents/research_proposal.pdf',
            'file_type' => 'fund_request',
            'file_size' => 1024000,
            'category' => 'fund_request',
            'title' => 'Research Proposal Document',
            'status' => 'verified',
        ]);

        $document2 = \App\Models\Document::create([
            'scholar_profile_id' => $scholarProfile->id,
            'fund_request_id' => $fundRequest->id,
            'file_name' => 'budget_breakdown.xlsx',
            'file_path' => 'documents/budget_breakdown.xlsx',
            'file_type' => 'fund_request',
            'file_size' => 512000,
            'category' => 'fund_request',
            'title' => 'Budget Breakdown',
            'status' => 'verified',
        ]);

        // Approve the fund request (this should trigger manuscript creation with document copying)
        $fundRequestService = app(\App\Services\FundRequestService::class);
        $fundRequestService->approveFundRequest($fundRequest, $user->id);

        // Refresh the fund request to get the latest data
        $fundRequest->refresh();

        // Assert manuscript was created
        $this->assertNotNull($fundRequest->manuscript);
        $manuscript = $fundRequest->manuscript;

        // Assert documents were copied to the manuscript
        $manuscriptDocuments = $manuscript->documents;
        $this->assertCount(2, $manuscriptDocuments);

        // Check that documents have correct properties
        $copiedDoc1 = $manuscriptDocuments->where('file_name', 'research_proposal.pdf')->first();
        $this->assertNotNull($copiedDoc1);
        $this->assertEquals($manuscript->id, $copiedDoc1->manuscript_id);
        $this->assertNull($copiedDoc1->fund_request_id);
        $this->assertEquals('manuscript', $copiedDoc1->category);
        $this->assertEquals('manuscript', $copiedDoc1->file_type);
        $this->assertStringContainsString('(From Fund Request)', $copiedDoc1->title);

        $copiedDoc2 = $manuscriptDocuments->where('file_name', 'budget_breakdown.xlsx')->first();
        $this->assertNotNull($copiedDoc2);
        $this->assertEquals($manuscript->id, $copiedDoc2->manuscript_id);
        $this->assertNull($copiedDoc2->fund_request_id);
        $this->assertEquals('manuscript', $copiedDoc2->category);
        $this->assertEquals('manuscript', $copiedDoc2->file_type);
        $this->assertStringContainsString('(From Fund Request)', $copiedDoc2->title);

        // Verify original fund request documents still exist
        $this->assertCount(2, $fundRequest->documents);
    }
}