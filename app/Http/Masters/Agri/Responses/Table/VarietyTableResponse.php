<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class VarietyTableResponse extends JsonResource
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
            'commodity' => $this->Commodity->name ?? '',
            'parentVariety' => $this->Variety->name ?? '',
            'name' => $this->name,
            'status' => $this->status
        ];
    }
}
