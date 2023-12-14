<?php

namespace App\Http\Masters\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class BankBranchListResponse extends JsonResource
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
             'bank_id' => $this->bank_id,
             'name' => $this->name,
             'ifsc' => $this->ifsc
        ];
    }
}
