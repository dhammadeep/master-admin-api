<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseGeoPlottingDetailsResponse extends JsonResource
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
            'geoPoints' => $this->polygon,
            'documentedArea' => [
                'value' => $this->documented_area_sqm,
                'readonly' => true
            ],
            'plottedArea' => [
                'value' => $this->plotted_area_sqm,
                'readonly' => true
            ],
            'rejectionReasonId' =>$this->rejection_reason_id,
            'status' =>$this->status
        ];
    }
}
