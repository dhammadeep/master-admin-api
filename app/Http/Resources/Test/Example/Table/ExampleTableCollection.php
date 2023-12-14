<?php

namespace App\Http\Resources\Test\Example\Table;

use App\Models\Test\Example;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Test\Example\Table\ExampleTableResource;

class ExampleTableCollection extends ResourceCollection
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
            'columns' => Example::getTableFields(),
            'data' => $this->collection->map(function ($data) {
                return new ExampleTableResource($data);
            })->all(),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
