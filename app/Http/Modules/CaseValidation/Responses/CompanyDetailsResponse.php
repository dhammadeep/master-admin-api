<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDetailsResponse extends JsonResource
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
            'companyName' => [
                'value' => $this->company_name,
                'readonly' => false,
                'required' => false
            ],
            'businessEntityType' => [
                'value' => $this->business_entity_type,
                'readonly' => false,
                'required' => false
            ],
            'licenseNo' => [
                'value' => $this->license_no,
                'readonly' => false,
                'required' => false
            ],
            'licenseIssueDate' => [
                'value' => $this->license_issue_date,
                'readonly' => false,
                'required' => false
            ],
            'licenseExpiryDate' => [
                'value' => $this->license_expiry_date,
                'readonly' => false,
                'required' => false
            ],
            'dateOfIncorporation' => [
                'value' => $this->date_of_incorporation,
                'readonly' => false,
                'required' => false
            ],
            'pan' => [
                'value' => $this->pan,
                'readonly' => false,
                'required' => false
            ],
            'cin' => [
                'value' => $this->cin,
                'readonly' => false,
                'required' => false
            ],
            'gstn' => [
                'value' => $this->gstn,
                'readonly' => false,
                'required' => false
            ],
            'website' => [
                'value' => $this->website,
                'readonly' => false,
                'required' => false
            ],
            'nationality' => [
                'value' => $this->nationality,
                'readonly' => false,
                'required' => false
            ],
            'constitution' => [
                'value' => $this->constitution,
                'readonly' => false,
                'required' => false
            ],
            'address' => [
                'value' => $this->address,
                'readonly' => false,
                'required' => false
            ],
            'pincode' => [
                'value' => $this->pincode,
                'readonly' => false,
                'required' => false
            ]
        ];
    }
}
