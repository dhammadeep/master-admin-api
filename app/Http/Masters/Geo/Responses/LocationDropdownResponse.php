<?php

namespace App\Http\Masters\Geo\Responses;

class LocationDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
            'name' => 'location_id',
            'label' => 'Location',
            'type' => 'select',
            'validators' => [
                'required' => true
            ],
            'options' => []
    ];
}
