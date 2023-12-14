<?php

namespace App\Http\Masters\Gen\Responses;



class BankDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'bank_id',
        'label' => 'Bank',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
