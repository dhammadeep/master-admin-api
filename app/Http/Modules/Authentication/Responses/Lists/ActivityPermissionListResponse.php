<?php

namespace App\Http\Modules\Authentication\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityPermissionListResponse extends JsonResource
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
