<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityPermissionTableResponse extends JsonResource
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
            'activity' => $this->Activity->name,
            'permission' => $this->Permission->name,
            // 'status' => $this->status,
        ];
    }
}
