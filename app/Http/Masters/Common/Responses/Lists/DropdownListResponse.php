<?php

namespace App\Http\Masters\Common\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class DropdownListResponse extends JsonResource
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
        ];
    }
}
