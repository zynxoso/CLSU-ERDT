<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RequestTypeResource extends BaseResource
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
            'name' => $this->name,
            'description' => $this->description,
            'required_documents' => $this->required_documents,
            'max_amount' => $this->max_amount,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('fundRequests')) {
            $data['fund_requests'] = FundRequestResource::collection($this->fundRequests);
        }

        return $data;
    }
}
