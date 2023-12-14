<?php

namespace App\Http\Modules\Gen\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuActivityTableResponse extends JsonResource
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
            $menuAcivity = $data->MenuActivity->map(function ($mdata) {
                return $mdata->Activity->name;
            })->all();
            return [
                'name'=> $data->name,
                'href' => $data->submenulink == "" ? "": $data->submenulink,
                'icon' => $data->icon == "" ? "<i class='fa-solid fa-list'></i>":$data->icon,
                'activity' => $menuAcivity,
                'order' => $data->submenu_order,
            ];
        })->all();
        $firstSubMenuLink = '';
        if(empty($subMenu)){
            $firstSubMenuLink = '/dashboard';
        }else{
            $firstSubMenuLink = head($subMenu)['href'];
        }

        return [
                'mainMenu' => $this->name,
                'href' => '',
                'icon' => $this->icon == "" ? "<i class='fa-solid fa-list'></i>":$this->icon,
                'order' => "{$this->menu_order}",
                'subMenu' => $subMenu
            ];
    }
}
