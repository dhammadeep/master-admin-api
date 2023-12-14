<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\Variety;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\VarietyTableResponse;

class VarietyTableCollection extends ResourceCollection
{

    private $commodityfilter;

    public function __construct($resource, array $commodityfilter) {
        parent::__construct( $resource );
        $this->commodityfilter = $commodityfilter;
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
            'columns' => Variety::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new VarietyTableResponse($data);
            })->all(),
            'filters' => Variety::getFilterFields($this->commodityfilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/Variety/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/Variety/reject'
                ]
            ],
        ];
    }
}
