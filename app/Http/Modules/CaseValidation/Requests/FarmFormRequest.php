<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FarmFormRequest extends FormRequest
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
     *
     */
    public function rules()
    {
        return [
            'ownershipType' => 'nullable|in:OWNED,LEASED',
            'userFullName' => 'nullable|regex:/^[a-zA-Z\s\-\'\pL]+$/u',
            'ownerName' => 'nullable|regex:/^[a-zA-Z\s\-\'\pL]+$/u',
            'farmName' => 'nullable|string',
            'lessor' => 'nullable|regex:/^[a-zA-Z\s\-\'\pL]+$/u',
            'lessees' => 'nullable|regex:/^[a-zA-Z\s\-\'\pL]+$/u',
            'registrationNo' => 'nullable|string',
            'mutationNo' => 'nullable|string',
            'saleDeedNo' => 'nullable|string',
            'registrationDate' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'documentedAreaSqm' => 'nullable|numeric',
            'leasedFromDate' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'leasedTillDate' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'surveyNo' => 'nullable|string'
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
            'caseId.integer' => 'Case Id should be Number.',
        ];
    }

     /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors()->messages())->map(function (mixed $item, mixed $key) {
            return [
                'name' => $key,
                "errorMessage" => $item[0]
            ];
        })->values();

        throw new HttpResponseException(response()->json([
            'status' => 422,
            'message' => 'fail',
            'data' => [
                'formData' => [
                    'errors' => $errors
                ]
            ]
        ],422));
    }
}
