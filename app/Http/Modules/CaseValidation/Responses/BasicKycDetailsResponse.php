<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicKycDetailsResponse extends JsonResource
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
            'aadhar' => [
                'value' => isset($this->aadhar) ? $this->aadhar : '',
                'readonly' => false,
                'required' => false
            ],
            'pan' => [
                'value' => isset($this->pan) ? $this->pan : '',
                'readonly' => false,
                'required' => false
            ],
            'userFullName' => [
                'value' => isset($this->user_full_name) ? $this->user_full_name : '',
                'readonly' => false,
                'required' => false
            ],
            'gender' => [
                'value' => isset($this->gender) ? $this->gender : '',
                'readonly' => false,
                'required' => false
            ],
            'dob' => [
                'value' => isset($this->dob) ? $this->dob : '',
                'readonly' => false,
                'required' => false
            ],
            'address' => [
                'value' => isset($this->address) ? $this->address : '',
                'readonly' => false,
                'required' => false
            ],
            'pincode' => [
                'value' => isset($this->pincode) ? $this->pincode : '',
                'readonly' => false,
                'required' => true
            ]
        ];
    }
}
