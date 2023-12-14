<?php

namespace App\Http\Masters\Menu\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class SubMenuTableResponse extends JsonResource
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
            'menu' => $this->Menu->name ?? "",
            'name' => $this->name,
            'subMenuLink' => $this->submenulink,
            'icon' => $this->icon,
            'status' => $this->status,
        ];
    }
}
