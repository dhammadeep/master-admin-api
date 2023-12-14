<?php

namespace App\Http\Masters\warehouse\Responses\Table;


use App\Http\Resources\Common\PaginationResource;
use App\Http\Modules\CaseValidation\Models\Warehouse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\warehouse\Responses\Table\warehouseTableResponse;

class warehouseTableCollection extends ResourceCollection
{

    private $warehousefilter;

    public function __construct($resource, array $warehousefilter) {
        parent::__construct( $resource );
        $this->warehousefilter = $warehousefilter;
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
            'columns' => Warehouse::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new warehouseTableResponse($data);
            })->all(),
            'filters' => Warehouse::getFilterFields($this->warehousefilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'warehouse/get-approved-list/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'warehouse/get-approved-list/reject'
                ]
            ],
        ];
    }
}
