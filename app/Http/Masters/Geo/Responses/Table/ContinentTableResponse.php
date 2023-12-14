<?php

namespace App\Http\Masters\Geo\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class ContinentTableResponse extends JsonResource
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
            'code' => $this->code,
            'status' => $this->status,
        ];
    }
}
