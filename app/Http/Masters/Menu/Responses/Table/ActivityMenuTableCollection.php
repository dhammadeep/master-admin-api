<?php

namespace App\Http\Masters\Menu\Responses\Table;

use App\Http\Masters\Menu\Models\ActivityMenu;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Menu\Responses\Table\ActivityMenuTableResponse;

class ActivityMenuTableCollection extends ResourceCollection
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
            'columns' => ActivityMenu::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new ActivityMenuTableResponse($data);
            })->all(),
            'filters' => ActivityMenu::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/ActivityMenu/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/ActivityMenu/reject'
                ]
            ],
        ];
    }
}
