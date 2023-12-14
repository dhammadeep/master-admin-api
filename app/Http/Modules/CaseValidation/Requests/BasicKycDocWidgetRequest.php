<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Support\Facades\Validator;

class BasicKycDocWidgetRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'caseId' => 'required|integer',
            'widgetId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return null; // Validation passed
         //return $data; // Validation passed
    }

    /**
     * Get the validation error messages that apply to the request
     *
     * @return array
     */
    public function messages()
    {
        return [
            'caseId.required' => 'Please provide Case Id.',
            'caseId.numeric' => 'Case Id should be Number.',
            'widgetId.required' => 'Please provide Widget Id.',
            'widgetId.numeric' => 'Widget Id should be Number.',
        ];
    }
}
