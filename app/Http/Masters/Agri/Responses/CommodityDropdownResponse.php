<?php

namespace App\Http\Masters\Agri\Responses;



class CommodityDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'commodity_id',
        'label' => 'Commodity',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
