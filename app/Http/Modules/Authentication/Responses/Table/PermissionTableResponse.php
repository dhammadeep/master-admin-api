<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionTableResponse extends JsonResource
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
            'guardName' => $this->guard_name,
            // 'status' => $this->status,
        ];
    }
}
