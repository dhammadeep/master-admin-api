<?php

namespace App\Http\Masters\Gen\Responses;



class StageDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'stage_id',
        'label' => 'Stage',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
