<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ManuscriptResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'scholar_profile_id' => $this->scholar_profile_id,
            'reference_number' => $this->reference_number,
            'title' => $this->title,
            'abstract' => $this->abstract,
            'manuscript_type' => $this->manuscript_type,
            'co_authors' => $this->co_authors,
            'keywords' => $this->keywords,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('scholarProfile')) {
            $data['scholar_profile'] = new ScholarProfileResource($this->scholarProfile);
        }

        if ($this->relationLoaded('documents')) {
            $data['documents'] = DocumentResource::collection($this->documents);
        }

        if ($this->relationLoaded('reviewComments')) {
            $data['review_comments'] = ReviewCommentResource::collection($this->reviewComments);
        }

        if ($this->relationLoaded('reviewedBy')) {
            $data['reviewed_by_user'] = new UserResource($this->reviewedBy);
        }

        if ($this->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        return $data;
    }
}
