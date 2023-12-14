<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantpartColorTableResponse extends JsonResource
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
            'commodity' => $this->Commodity->name,
            'phenophase' => $this->Phenophase->name,
            'name' => $this->name,
            'hexcode' => $this->hexcode,
            'weightage' => $this->weightage,
            'status' => $this->status,
        ];
    }
}
