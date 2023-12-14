<?php

namespace App\Http\Resources\Geographical\Country\Table;

use App\Models\Geographical\Country;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Geographical\Country\Table\CountryTableResource;

class CountryTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => Country::getTableFields(),
            'data' => $this->collection->map(function ($data) {
                return new CountryTableResource($data);
            })->all(),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
