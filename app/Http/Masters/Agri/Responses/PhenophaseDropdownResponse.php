<?php

namespace App\Http\Masters\Agri\Responses;



class PhenophaseDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'phenophase_id',
        'label' => 'Phenophase',
        'type' => 'select',
        'validators' => [
            'required' => false
        ],
        'options' => ''
    ];
}
