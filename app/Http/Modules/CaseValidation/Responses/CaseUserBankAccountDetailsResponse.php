<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseUserBankAccountDetailsResponse extends JsonResource
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
            'accountName' => [
                'value' => $this->account_name,
                'readonly' => false,
                'required' => true
            ],
            'accountNumber' => [
                'value' => $this->account_number,
                'readonly' => false,
                'required' => true
            ],
            'ifsc' => [
                'value' => $this->ifsc,
                'readonly' => false,
                'required' => true
            ],
            'bankName' => [
                'value' => $this->bank_name,
                'readonly' => true
            ],
            'branchName' => [
                'value' => $this->branch_name,
                'readonly' => true
            ],
            'address' => [
                'value' => $this->address,
                'readonly' => true
            ],
            'pincode' => [
                'value' => $this->pincode,
                'readonly' => true
            ]
        ];
    }
}
