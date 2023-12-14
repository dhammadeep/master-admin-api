<?php

namespace App\Http\Modules\Authentication\Responses\Lists;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileListResponse extends JsonResource
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
            'mobileNo' => $this->mobile,
            'email' => $this->email ?? '',
            'fullName' => $this->UserKyc->full_name ?? '',
            'dob' => $this->UserKyc->dob ?? '',
            'gender' => $this->UserKyc->gender ?? '',
            'activities' => $userAcivity,
            'storeUrl' => "user/profileUpdate/$this->id",
            'changePasswordUrl' => "user/changePassword/$this->id"
        ];
    }
}
