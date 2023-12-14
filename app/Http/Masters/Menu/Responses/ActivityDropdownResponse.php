<?php

namespace App\Http\Masters\Menu\Responses;



class ActivityDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'activity_id',
        'label' => 'Activity',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => '',
        'value' => ''
    ];
}
