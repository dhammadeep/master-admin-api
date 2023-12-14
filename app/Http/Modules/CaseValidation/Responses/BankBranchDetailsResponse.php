<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class BankBranchDetailsResponse extends JsonResource
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
            'bankBranchId' => $this->id,
            'branchName' => $this->branch_name,
            'ifsc' => $this->ifsc,
            'bankName' => $this->bank_name,
            'state' => $this->state_name,
            'city' => $this->city_name,
            'address' => $this->address,
            'pincode' => $this->pincode
        ];
    }
}
