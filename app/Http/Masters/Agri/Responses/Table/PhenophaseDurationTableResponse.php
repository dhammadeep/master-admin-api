<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class PhenophaseDurationTableResponse extends JsonResource
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
            'variety' => $this->Variety->name,
            'phenophase' => $this->Phenophase->name,
            'startDas' => $this->start_das,
            'endDas' => $this->end_das,
            'status' => $this->status,
        ];
    }
}
