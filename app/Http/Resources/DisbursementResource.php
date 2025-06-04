<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class DisbursementResource extends BaseResource
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
            'fund_request_id' => $this->fund_request_id,
            'reference_number' => $this->reference_number,
            'amount' => $this->amount,
            'disbursement_date' => $this->disbursement_date,
            'payment_method' => $this->payment_method,
            'payment_details' => $this->payment_details,
            'status' => $this->status,
            'notes' => $this->notes,
            'processed_by' => $this->processed_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('fundRequest')) {
            $data['fund_request'] = new FundRequestResource($this->fundRequest);
        }

        if ($this->relationLoaded('processedBy')) {
            $data['processed_by_user'] = new UserResource($this->processedBy);
        }

        return $data;
    }
}
