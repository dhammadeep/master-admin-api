<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseGeoTagDetailsResponse extends JsonResource
{

     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'caseId' => $this->case_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'rejectionReasonId' =>$this->rejection_reason_id,
            'status' =>$this->status
        ];
    }
}
