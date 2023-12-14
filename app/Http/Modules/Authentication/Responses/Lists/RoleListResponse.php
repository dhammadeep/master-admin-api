<?php

namespace App\Http\Modules\Authentication\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleListResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $permissionIds = $this->Permission->map(function ($data) {
            return $data->id;
        })->all();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'permission_id' => $permissionIds,
            // 'status' => $this->status
        ];
    }
}
