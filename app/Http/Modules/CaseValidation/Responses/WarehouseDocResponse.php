<?php

namespace App\Http\Modules\CaseValidation\Responses;

class WarehouseDocResponse
{

    public $caseId;
    public $widgetId;

    /**
     * Array of form information and placeholders.
     *
     * @var array
     */
    public $form = [];

    public $stackDetails = [];

    //Array of basic KYC doc widget structure
    public $docWidget = [];
}
