<?php

namespace App\Http\Masters\warehouse\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class warehouseListResponse extends JsonResource
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
            'pincode' => $this->Location->pincode,
            'address' => $this->Location->address,
            'warehouse_type_id' => $this->warehouse_type_id,
            'name' => $this->name,
            'code' => $this->code,
            'phone' => $this->phone
        ];
    }
}
