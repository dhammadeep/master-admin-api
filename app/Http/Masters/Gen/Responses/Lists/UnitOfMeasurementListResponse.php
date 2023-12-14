<?php

namespace App\Http\Masters\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitOfMeasurementListResponse extends JsonResource
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
             'name' => $this->name,
             'uom_type_id' => $this->uom_type_id
        ];
    }
}
