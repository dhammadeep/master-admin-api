<?php

namespace App\Http\Masters\Menu\Responses\Table;

use App\Http\Masters\Menu\Models\SubMenu;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Menu\Responses\Table\SubMenuTableResponse;

class SubMenuTableCollection extends ResourceCollection
{

    private $menufilter;

    public function __construct($resource, array $menufilter) {
        parent::__construct( $resource );
        $this->menufilter = $menufilter;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => SubMenu::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new SubMenuTableResponse($data);
            })->all(),
            'filters' => SubMenu::getFilterFields($this->menufilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'menu/sub-menu/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'menu/sub-menu/reject'
                ]
            ],
        ];
    }
}
