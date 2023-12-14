<?php

namespace App\Http\Modules\Gen\Responses\Table;

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
        $subMenu = $this->SubMenu->map(function ($data) {
            return [
                'id' => $data->id,
                'name'=> $data->name,
                'order' => $data->submenu_order
            ];
        })->all();

         return [
            'id' => $this->id,
            'mainMenu' => $this->name,
            'order' => $this->menu_order,
            'subMenu' => $subMenu
        ];
    }
}
