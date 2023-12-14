<?php

namespace App\Http\Masters\Geo\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class ContinentListResponse extends JsonResource
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
             'code' => $this->code
        ];
    }
}
