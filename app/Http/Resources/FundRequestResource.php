<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class FundRequestResource extends BaseResource
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
            'request_type_id' => $this->request_type_id,
            'amount' => $this->amount,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'status_history' => $this->status_history,
            'admin_remarks' => $this->admin_remarks,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('scholarProfile')) {
            $data['scholar_profile'] = new ScholarProfileResource($this->scholarProfile);
        }

        if ($this->relationLoaded('requestType')) {
            $data['request_type'] = new RequestTypeResource($this->requestType);
        }

        if ($this->relationLoaded('disbursements')) {
            $data['disbursements'] = DisbursementResource::collection($this->disbursements);
        }

        if ($this->relationLoaded('reviewedBy')) {
            $data['reviewed_by_user'] = new UserResource($this->reviewedBy);
        }

        if ($this->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        if ($this->relationLoaded('documents')) {
            $data['documents'] = DocumentResource::collection($this->documents);
        }

        return $data;
    }
}
