<?php

namespace App\Http\Modules\CaseValidation\Responses;

class BasicKycDocResponse
{

    public $caseId;
    public $widgetId;

    /**
     * Array of form information and placeholders.
     *
     * @var array
     */
    public $form = [];

    //Array of basic KYC doc widget structure
    public $docWidget = [];
}
