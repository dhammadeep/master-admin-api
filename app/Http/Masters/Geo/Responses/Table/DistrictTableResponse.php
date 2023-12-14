<?php

namespace App\Http\Masters\Geo\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictTableResponse extends JsonResource
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
            'state' => $this->State->name,
            'status' => $this->status
        ];
    }
}
