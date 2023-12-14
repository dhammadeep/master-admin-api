<?php

namespace App\Http\Modules\DocWidgetStructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocWidgetRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'caseId' => 'required|numeric',
            'widgetId' => 'required|numeric',
        ];
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
            'widgetId.required' => 'Please provide Widget Id.',
            'caseId.numeric' => 'Case Id should be Number.',
            'widgetId.numeric' => 'Widget Id should be Number.',
        ];
    }
}
