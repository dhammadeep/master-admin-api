<?php

namespace App\Http\Masters\Warehouse\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseTypeTableResponse extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
