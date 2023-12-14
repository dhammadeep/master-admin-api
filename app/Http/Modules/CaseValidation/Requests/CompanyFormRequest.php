<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyFormRequest extends FormRequest
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
            'companyName' => 'nullable|string',
            'businessEntityType' => 'nullable|string',
            'licenseNo' => 'nullable|string',
            'licenseIssueDate' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'licenseExpiryDate' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'dateOfIncorporation' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
            'pan' => 'nullable',
            'cin' => 'nullable|regex:/^([LUu]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/',
            'gstn' => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'website' => "nullable",
            'nationality' => 'nullable|string',
            'constitution' => 'nullable|string',
            'address' => 'nullable|string',
            'pincode' => 'required|regex:/^[0-9]\d*$/',
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
            'pincode.required' => 'Please provide Pincode.',
            'pincode.integer' => 'Pincode should be Number.',
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
