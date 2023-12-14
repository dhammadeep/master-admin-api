<?php

namespace App\Http\Masters\Menu\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityMenuTableResponse extends JsonResource
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
                return $data->name;
            })->all();
        }
        // if($this->Activity){
        //     $activity = $this->Activity->groupBy(function($data) {
        //         return $data->name;
        //     });
        // }
         return [
             'id' => $this->id,
             'activity' =>$this->name,
             'submenu' => $submenus,
        ];
    }
}
