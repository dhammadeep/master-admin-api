<?php

namespace App\Http\Masters\Menu\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class SubMenuListResponse extends JsonResource
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
             'menu_id' => $this->menu_id ?? "",
             'name' => $this->name,
             'submenulink' => $this->submenulink,
             'icon' => $this->icon ?? "<i class='fa-solid fa-circle'></i>"
        ];
    }
}
