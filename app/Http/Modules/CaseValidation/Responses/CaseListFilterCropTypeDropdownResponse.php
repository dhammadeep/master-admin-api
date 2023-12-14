<?php

namespace App\Http\Modules\CaseValidation\Responses;



class CaseListFilterCropTypeDropdownResponse {

    /**
     * Array of formFieldAtributes.
     *
     * @var array
     */
    public $formFieldAtributes = [
        'name' => 'crop_type_id',
        'label' => 'Crop Type',
       'type' => 'select',
       'validators' => [
        'required' => false
        ],
        'value' => '',
        'searchable' => 'Crop Type',
        'options' => ''
    ];
}
