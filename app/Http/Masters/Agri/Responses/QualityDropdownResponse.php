<?php

namespace App\Http\Masters\Agri\Responses;



class QualityDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'quality_band_id',
        'label' => 'Quality Band',
        'type' => 'select',
        'validators' => [
            'required' => false
        ],
        'options' => ''
    ];
}
