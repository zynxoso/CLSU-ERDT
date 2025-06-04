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
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'university' => $this->university,
            'department' => $this->department,
            'program' => $this->program,
            'degree_program' => $this->degree_program,
            'year_level' => $this->year_level,
            'expected_graduation' => $this->expected_graduation,
            'status' => $this->status,
            'scholar_id' => $this->scholar_id,
            'profile_photo' => $this->profile_photo,
            'is_verified' => $this->is_verified,
            'verified_at' => $this->verified_at,
            'start_date' => $this->start_date,
            'expected_completion_date' => $this->expected_completion_date,
            'actual_completion_date' => $this->actual_completion_date,
            'bachelor_degree' => $this->bachelor_degree,
            'bachelor_university' => $this->bachelor_university,
            'bachelor_graduation_year' => $this->bachelor_graduation_year,
            'research_area' => $this->research_area,
            'degree_level' => $this->degree_level,
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
