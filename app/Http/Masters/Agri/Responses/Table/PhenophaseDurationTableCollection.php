<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\PhenophaseDuration;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\PhenophaseDurationTableResponse;

class PhenophaseDurationTableCollection extends ResourceCollection
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
            'columns' => PhenophaseDuration::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new PhenophaseDurationTableResponse($data);
            })->all(),
            'filters' => PhenophaseDuration::getFilterFields($this->filter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/phenophase-duration/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/phenophase-duration/reject'
                ]
            ],
        ];
    }
}
