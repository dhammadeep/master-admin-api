<?php

namespace App\Http\Masters\Menu\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityMenuDropdownTableResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $submenus = '';
        if($this->SubMenu){
            $submenus = $this->SubMenu->map(function ($data) {
                return [
                    'value' => $data->id,
                    'label' => $data->name
                ];
            })->all();
        }

         return [
            'mainMenu' => $this->name,
            'subMenu' => $submenus
        ];
    }
}
