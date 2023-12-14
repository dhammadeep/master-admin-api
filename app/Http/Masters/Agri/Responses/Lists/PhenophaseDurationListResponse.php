<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class PhenophaseDurationListResponse extends JsonResource
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
             'variety_id' => $this->variety_id,
             'phenophase_id' => $this->phenophase_id,
             'start_das' => $this->start_das,
             'end_das' => $this->end_das
        ];
    }
}
