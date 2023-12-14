<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\State;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\StateTableResponse;

class StateTableCollection extends ResourceCollection
{
    private $countryfilter;

    public function __construct($resource, array $countryfilter) {
        parent::__construct( $resource );
        $this->countryfilter = $countryfilter;
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
            'columns' => State::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new StateTableResponse($data);
            })->all(),
            'filters' => State::getFilterFields($this->countryfilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/state/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/state/reject'
                ]
            ],
        ];
    }
}
