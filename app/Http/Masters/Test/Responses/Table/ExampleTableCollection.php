<?php

namespace App\Http\Masters\Test\Responses\Table;

use App\Http\Masters\Test\Models\Example;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Test\Responses\Table\ExampleTableResource;

;

class ExampleTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
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
