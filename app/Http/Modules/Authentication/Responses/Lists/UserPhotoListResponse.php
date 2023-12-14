<?php

namespace App\Http\Modules\Authentication\Responses\Lists;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPhotoListResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'profilePhoto' => $this->UserKyc->profile_photo ?? '',
            'storeUrl' => "user/photoUpload/$this->id"
        ];
    }
}
