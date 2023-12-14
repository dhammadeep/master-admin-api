<?php

namespace App\Http\Masters\Gen\Responses;



class RejectionReasonTypeDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'rejection_reason_type_id',
        'label' => 'Rejection Reason Type',
        'type' => 'select',
        'validators' => [
            'required' => true
        ],
        'options' => ''
    ];
}
