<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\PlantpartColor;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\PlantpartColorTableResponse;

class PlantpartColorTableCollection extends ResourceCollection
{

    private $filter;

    public function __construct($resource, array $filter) {
        parent::__construct( $resource );
        $this->filter = $filter;
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
            'columns' => PlantpartColor::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new PlantpartColorTableResponse($data);
            })->all(),
            'filters' => PlantpartColor::getFilterFields($this->filter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/plantpart-color/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/plantpart-color/reject'
                ]
            ],
        ];
    }
}
