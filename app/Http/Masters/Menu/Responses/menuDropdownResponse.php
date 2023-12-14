<?php

namespace App\Http\Masters\Menu\Responses;



class menuDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'menu_id',
        'label' => 'Menu',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
