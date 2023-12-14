<?php

namespace App\Http\Modules\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ActivityPermissionRequest extends FormRequest
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
            'activity_id' => 'required',
            'permission_id' => 'required'
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
            'activity_id.required' => 'Please enter select activity.',
            'permission_id.required' => 'Please enter select permission.'
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
