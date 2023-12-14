<?php

namespace App\Http\Modules\Authentication\Responses\Lists;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResponse extends JsonResource
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
            return $data->Activity->id;
        })->all();
        $mobile = $this->mobile;
        $country_code = '+91';
        $mobile = preg_replace("/^\+?{$country_code}/", '',$mobile);
        return [
            'id' => $this->id,
            'mobile' => $mobile,
            'email' => $this->email ?? '',
            'language_id' => $this->language_id,
            'activity_id' => $userAcivity,
            'status' => $this->status
        ];
    }
}
