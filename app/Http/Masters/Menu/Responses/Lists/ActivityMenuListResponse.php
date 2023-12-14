<?php

namespace App\Http\Masters\Menu\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityMenuListResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subMenuIds = $this->map(function ($data) {
            return $data['sub_menu_id'];
        })->unique()->all();
        $activityIds = $this->map(function ($data) {
            return $data['activity_id'];
        })->unique()->first();
         return [
             'sub_menu_id' => $subMenuIds,
             'activity_id' => $activityIds,
        ];
    }
}
