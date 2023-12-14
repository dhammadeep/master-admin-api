<?php

namespace App\Http\Modules\CaseValidation\Resources\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseIncompleteListTableResource extends JsonResource
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
            'id' => $this->case_id,
            'tenderType' => $this->tender_type,
            'userFullName' => $this->user_full_name,
            'userMobile' => $this->user_mobile,
            'languageName' => $this->language_name
        ];
    }
}
