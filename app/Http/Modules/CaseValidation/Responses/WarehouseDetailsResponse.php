<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseDetailsResponse extends JsonResource
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
            'code' => [
                'value' => $this->code,
                'readonly' => true,
                'required' => false
            ],
            'name' => [
                'value' => $this->name,
                'readonly' => true,
                'required' => false
            ],
            'phone' => [
                'value' => $this->phone,
                'readonly' => true,
                'required' => false
            ],
            'warehouseLotNo' => [
                'value' => $this->warehouse_lot_no,
                'readonly' => false,
                'required' => false
            ],
            'totalBagCount' => [
                'value' => $this->total_bag_count,
                'readonly' => false,
                'required' => false
            ]
        ];
    }
}
