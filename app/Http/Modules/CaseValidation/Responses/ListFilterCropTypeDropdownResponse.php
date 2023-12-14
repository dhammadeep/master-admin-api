<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class ListFilterCropTypeDropdownResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->crop_type_id;
        $id = $this->crop_type;
        // if(isset($this->Permission)){
        //     $id = $this->Permission->id;
        //     $name = $this->Permission->name;
        // }
        if(isset($this->address)){
            $name = $this->address;
        }
         return [
            'value' => $id,
            'label' => $name
        ];
    }
}
