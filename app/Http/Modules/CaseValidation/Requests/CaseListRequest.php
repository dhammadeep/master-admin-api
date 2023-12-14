<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseListRequest extends FormRequest
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
            'pending' => '',
            'completed' => '',
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
            'pending.required' => 'Please enter pending.',
            'completed.required' => 'Please enter completed.',
        ];
    }
}
