<?php

namespace App\Http\Masters\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class StageVideoListResponse extends JsonResource
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
             'stage_id' => $this->stage_id,
             'link' => $this->link,
             'description' => $this->description ?? "",
        ];
    }
}
