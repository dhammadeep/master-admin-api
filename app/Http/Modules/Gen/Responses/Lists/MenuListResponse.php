<?php

namespace App\Http\Modules\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuListResponse extends JsonResource
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
        ];
    }
}
