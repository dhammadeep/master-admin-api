<?php

namespace App\Http\Modules\Cases\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseBriefDetailResponseDto extends JsonResource
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
            'cropType' => $this->crop_type,
            'userFullName' => $this->user_full_name ? $this->user_full_name  : 'New',
            'userMobile' => $this->user_mobile,
            'commodity' => $this->commodity_name,
            'state' => $this->state,
            'validation' => [
                'userStatus' =>   $this->user_status,
                'farmStatus' => $this->farm_status,
                'caseWarehouseStatus' => $this->case_warehouse_status,
                'companyStatus' => $this->company_status,
                'bankAccountStatus' => $this->bank_account_status
            ]
        ];
    }
}
