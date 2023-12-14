<?php

namespace App\Http\Masters\Menu\Responses\Table;

use App\Http\Masters\Menu\Models\Menu;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Menu\Responses\Table\MenuTableResponse;

class MenuTableCollection extends ResourceCollection
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
            'columns' => Menu::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new MenuTableResponse($data);
            })->all(),
            'filters' => Menu::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'menu/menu/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'menu/menu/reject'
                ]
            ],
        ];
    }
}
