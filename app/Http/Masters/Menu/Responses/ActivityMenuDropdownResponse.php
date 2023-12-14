<?php

namespace App\Http\Masters\Menu\Responses;



class ActivityMenuDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'sub_menu_id',
        'label' => 'Menu',
        'type' => 'checkboxGroup',
        'validators' => [
            'required' => true
        ],
        'options' => '',
        'value' => []
    ];
}
