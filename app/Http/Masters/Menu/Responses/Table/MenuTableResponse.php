<?php

namespace App\Http\Masters\Menu\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuTableResponse extends JsonResource
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
            'menulink' => $this->menulink,
            'icon' => $this->icon,
            'status' => $this->status,
        ];
    }
}
