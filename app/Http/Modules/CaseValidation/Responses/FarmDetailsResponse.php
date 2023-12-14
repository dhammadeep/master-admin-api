<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmDetailsResponse extends JsonResource
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
            'ownershipType' => [
                'value' => $this->ownership_type,
                'readonly' => false,
                'required' => true
            ],
            'farmName' => [
                'value' => $this->farm_name,
                'readonly' => false,
                'required' => false
            ],
            'ownerName' => [
                'value' => $this->owner_name,
                'readonly' => false,
                'required' => false
            ],
            'lessor' => [
                'value' => $this->lessor,
                'readonly' => false,
                'required' => false
            ],
            'lessees' => [
                'value' => $this->lessees,
                'readonly' => false,
                'required' => false
            ],
            'registrationNo' => [
                'value' => $this->registration_no,
                'readonly' => false,
                'required' => false
            ],
            'mutationNo' => [
                'value' => $this->mutation_no,
                'readonly' => false,
                'required' => false
            ],
            'saleDeedNo' => [
                'value' => $this->sale_deed_no,
                'readonly' => false,
                'required' => false
            ],
            'registrationDate' => [
                'value' => $this->registration_date,
                'readonly' => false,
                'required' => false
            ],
            'leasedFromDate' => [
                'value' => $this->leased_from_date,
                'readonly' => false,
                'required' => false
            ],
            'leasedTillDate' => [
                'value' => $this->leased_till_date,
                'readonly' => false,
                'required' => false
            ],
            'documentedAreaSqm' => [
                'value' => $this->documented_area_sqm,
                'readonly' => false,
                'required' => false
            ],
            'surveyNo' => [
                'value' => $this->survey_no,
                'readonly' => false,
                'required' => false
            ]
        ];
    }
}
