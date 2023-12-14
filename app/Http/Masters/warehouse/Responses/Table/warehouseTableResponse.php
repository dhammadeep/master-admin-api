<?php

namespace App\Http\Masters\warehouse\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class warehouseTableResponse extends JsonResource
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
            'id' => $this->id,
            'address' => $this->Location->address,
            'pincode' => $this->Location->pincode,
            'warehouseType' => $this->WarehouseType->name,
            'name' => $this->name,
            'code' => $this->code,
            'phone' => $this->phone,
            'status' => $this->status
        ];
    }
}
