<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class ParameterListResponse extends JsonResource
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
             'description' => $this->description ?? "",
             'scheme' => $this->scheme
        ];
    }
}
