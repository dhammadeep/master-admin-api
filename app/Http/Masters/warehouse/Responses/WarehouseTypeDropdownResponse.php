<?php

namespace App\Http\Masters\warehouse\Responses;



class WarehouseTypeDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'warehouse_type_id',
        'label' => 'Warehouse',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
