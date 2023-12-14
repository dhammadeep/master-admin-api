<?php

namespace App\Http\Masters\Agri\Responses;



class VarietyDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'variety_id',
        'label' => 'Variety',
        'type' => 'select',
        'validators' => [
            'required' => false
        ],
        'options' => ''
    ];
}
