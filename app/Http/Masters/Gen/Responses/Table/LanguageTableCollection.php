<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\Language;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\LanguageTableResponse;

class LanguageTableCollection extends ResourceCollection
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
            'columns' => Language::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new LanguageTableResponse($data);
            })->all(),
            'filters' => Language::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'gen/language/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'gen/language/reject'
                ]
            ],
        ];
    }
}
