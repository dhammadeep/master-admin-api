<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BasicKycFormRequest extends FormRequest
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
        // dd(request());
        return [
            'aadhar' => 'nullable|regex:/^[1-9][0-9]{11}$/',
            'pan' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'userFullName' => 'nullable|regex:/^[a-zA-Z\s\-\'\pL]+$/u',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/',
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
            'pincode.required' => 'Please provide Pincode.'
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
