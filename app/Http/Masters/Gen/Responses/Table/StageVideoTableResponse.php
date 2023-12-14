<?php

namespace App\Http\Masters\Gen\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class StageVideoTableResponse extends JsonResource
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
            'stage' => $this->Stage->name,
            'title' => $this->name,
            'description' => $this->description ?? "",
            'video' => $this->link,
            'status' => $this->status,
        ];
    }
}
