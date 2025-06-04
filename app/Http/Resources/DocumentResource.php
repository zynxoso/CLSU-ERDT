<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class DocumentResource extends BaseResource
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
            'fund_request_id' => $this->fund_request_id,
            'manuscript_id' => $this->manuscript_id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'category' => $this->category,
            'is_verified' => $this->is_verified,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'description' => $this->description,
            'status' => $this->status,
            'security_scanned' => $this->security_scanned,
            'security_scanned_at' => $this->security_scanned_at,
            'security_scan_result' => $this->security_scan_result,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('scholarProfile')) {
            $data['scholar_profile'] = new ScholarProfileResource($this->scholarProfile);
        }

        if ($this->relationLoaded('fundRequest')) {
            $data['fund_request'] = new FundRequestResource($this->fundRequest);
        }

        if ($this->relationLoaded('manuscript')) {
            $data['manuscript'] = new ManuscriptResource($this->manuscript);
        }

        if ($this->relationLoaded('verifiedBy')) {
            $data['verified_by_user'] = new UserResource($this->verifiedBy);
        }

        if ($this->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        return $data;
    }
}
