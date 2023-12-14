<?php

namespace App\Http\Modules\CaseValidation\Resources\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseListTableResource extends JsonResource
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
            'id' => $this->case_id,
            'cropType' => $this->crop_type,
            'userFullName' => $this->user_full_name,
            'userMobile' => $this->user_mobile,
            'languageName' => $this->language_name,
            'regionName' => $this->region_name,
            'paymentStatus' => $this->payment_status,
            'validation' => [
                'userStatus' => $this->user_status,
                'farmStatus' => $this->farm_status,
                'caseWarehouseStatus' => $this->case_warehouse_status,
                'bankAccountStatus' => $this->bank_account_status
            ]
        ];
    }
}
