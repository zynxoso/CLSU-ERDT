<?php

namespace App\Services;

use App\Models\FundRequest;
use App\Models\Manuscript;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ManuscriptService
{
    /**
     * Create manuscript from approved fund request
     */
    public function createManuscriptFromFundRequest(FundRequest $fundRequest): ?Manuscript
    {
        // Only create manuscripts for specific fund request types
        $manuscriptTypes = [
            'Research Dissemination Grant' => 'Final',
            'Research Equipment Grant' => 'Outline'
        ];

        $requestTypeName = $fundRequest->requestType->name;
        
        if (!array_key_exists($requestTypeName, $manuscriptTypes)) {
            return null;
        }

        // Check if manuscript already exists for this fund request
        if ($fundRequest->manuscript) {
            return $fundRequest->manuscript;
        }

        // Generate reference number
        $referenceNumber = $this->generateReferenceNumber();

        // Create manuscript
        $manuscript = Manuscript::create([
            'scholar_profile_id' => $fundRequest->scholar_profile_id,
            'fund_request_id' => $fundRequest->id,
            'reference_number' => $referenceNumber,
            'title' => $this->generateManuscriptTitle($fundRequest),
            'abstract' => $this->generateManuscriptAbstract($fundRequest),
            'manuscript_type' => $manuscriptTypes[$requestTypeName],
            'status' => 'Submitted',
        ]);

        // Copy documents from fund request to manuscript
        $this->copyDocumentsFromFundRequest($fundRequest, $manuscript);

        return $manuscript;
    }

    /**
     * Generate manuscript title from fund request
     */
    private function generateManuscriptTitle(FundRequest $fundRequest): string
    {
        $purpose = $fundRequest->purpose;
        $requestType = $fundRequest->requestType->name;
        
        if ($requestType === 'Research Dissemination Grant') {
            return $purpose ?: 'Research Manuscript for Dissemination';
        } else {
            return $purpose ?: 'Research Proposal and Equipment Requirements';
        }
    }

    /**
     * Generate manuscript abstract from fund request
     */
    private function generateManuscriptAbstract(FundRequest $fundRequest): string
    {
        $details = $fundRequest->details;
        $requestType = $fundRequest->requestType->name;
        
        if ($requestType === 'Research Dissemination Grant') {
            return $details ?: 'This manuscript is submitted for research dissemination purposes. Please update with your research abstract.';
        } else {
            return $details ?: 'This research proposal outlines the equipment requirements and research methodology. Please update with detailed research proposal.';
        }
    }

    /**
     * Generate unique reference number
     */
    private function generateReferenceNumber(): string
    {
        do {
            $referenceNumber = 'MS-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Manuscript::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }

    /**
     * Check if fund request type should create manuscript
     */
    public function shouldCreateManuscript(FundRequest $fundRequest): bool
    {
        $manuscriptTypes = [
            'Research Dissemination Grant',
            'Research Equipment Grant'
        ];

        return in_array($fundRequest->requestType->name, $manuscriptTypes);
    }

    /**
     * Copy documents from fund request to manuscript
     */
    private function copyDocumentsFromFundRequest(FundRequest $fundRequest, Manuscript $manuscript): void
    {
        // Get all documents associated with the fund request
        $fundRequestDocuments = $fundRequest->documents;

        foreach ($fundRequestDocuments as $document) {
            // Create a copy of the document for the manuscript
            $manuscriptDocument = $document->replicate();
            
            // Update the document to be associated with the manuscript instead
            $manuscriptDocument->manuscript_id = $manuscript->id;
            $manuscriptDocument->fund_request_id = null; // Remove fund request association
            
            // Update category and file type to indicate it's a manuscript document
            $manuscriptDocument->category = 'manuscript';
            $manuscriptDocument->file_type = 'manuscript';
            
            // Update title to indicate it was copied from fund request
            if ($manuscriptDocument->title) {
                $manuscriptDocument->title = $manuscriptDocument->title . ' (From Fund Request)';
            } else {
                $manuscriptDocument->title = 'Document from Fund Request';
            }
            
            // Save the new document
            $manuscriptDocument->save();
        }
    }
}