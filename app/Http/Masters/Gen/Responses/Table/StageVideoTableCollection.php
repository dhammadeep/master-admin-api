<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\StageVideo;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\StageVideoTableResponse;

class StageVideoTableCollection extends ResourceCollection
{

    private $stagefilter;

    public function __construct($resource, array $stagefilter) {
        parent::__construct( $resource );
        $this->stagefilter = $stagefilter;
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
            'columns' => StageVideo::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new StageVideoTableResponse($data);
            })->all(),
            'filters' => StageVideo::getFilterFields($this->stagefilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'gen/stage-video/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'gen/stage-video/reject'
                ]
            ],
        ];
    }
}
