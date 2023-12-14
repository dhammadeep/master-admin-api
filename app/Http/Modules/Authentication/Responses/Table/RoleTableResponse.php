<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleTableResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->Permission){
            $permissions = $this->Permission->map(function ($data) {
                return $data->name;
            })->all();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guardName' => $this->guard_name,
            'description' => $this->description,
            'permissions' => $permissions ?? '',
            // 'status' => $this->status
        ];
    }
}
