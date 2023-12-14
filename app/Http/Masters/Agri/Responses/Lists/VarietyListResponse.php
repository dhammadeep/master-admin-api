<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class VarietyListResponse extends JsonResource
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
             'commodity_id' => $this->commodity_id,
             'parent_variety_id' => $this->parent_variety_id,
             'name' => $this->name
        ];
    }
}
