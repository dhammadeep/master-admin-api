<?php

namespace App\Http\Masters\Agri\Responses;



class ParameterDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'parameter_id',
        'label' => 'Parameter',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
