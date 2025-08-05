<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ScholarProfileResource extends BaseResource
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
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'contact_number' => $this->contact_number,

            'province' => $this->province,
            'country' => $this->country,
            'intended_university' => $this->intended_university,
            'department' => $this->department,
            // Program field removed - using department instead
            'intended_degree' => $this->intended_degree,
            'year_level' => $this->year_level,
            'expected_graduation' => $this->expected_graduation,
            'status' => $this->status,
            'profile_photo' => $this->profile_photo,
            'is_verified' => $this->is_verified,
            'verified_at' => $this->verified_at,
            'start_date' => $this->start_date,
            // Expected completion date field removed
            'actual_completion_date' => $this->actual_completion_date,


            // Bachelor graduation year field removed

            'enrollment_type' => $this->enrollment_type,
            'study_time' => $this->study_time,
            'scholarship_duration' => $this->scholarship_duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        if ($this->relationLoaded('fundRequests')) {
            $data['fund_requests'] = FundRequestResource::collection($this->fundRequests);
        }

        if ($this->relationLoaded('manuscripts')) {
            $data['manuscripts'] = ManuscriptResource::collection($this->manuscripts);
        }

        if ($this->relationLoaded('documents')) {
            $data['documents'] = DocumentResource::collection($this->documents);
        }

        if ($this->relationLoaded('verifiedBy')) {
            $data['verified_by'] = new UserResource($this->verifiedBy);
        }

        return $data;
    }
}
