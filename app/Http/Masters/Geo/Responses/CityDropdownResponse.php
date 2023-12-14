<?php

namespace App\Http\Masters\Geo\Responses;



class CityDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
            'name' => 'district_id',
            'label' => 'District',
            'type' => 'select',
            'validators' => [
                'required' => true
            ],
            'options' => []
    ];
}
