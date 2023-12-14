<?php

namespace App\Http\Masters\Common\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class DropdownTableResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->name;
        $id = $this->id;
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
