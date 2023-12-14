<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTableResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $userAcivity = $this->UserActivity->map(function ($data) {
            return $data->Activity->name;
        })->all();
        return [
            'id' => $this->id,
            'mobile' => $this->mobile,
            'email' => $this->email ?? '',
            'language' => $this->Language->name ?? '',
            'userActivity' => $userAcivity ?? [],
            'status' => $this->status
        ];
    }
}
