<?php

namespace App\Http\Masters\Gen\Responses;



class UomDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'uom_type_id',
        'label' => 'Unit of Measurement Type',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
