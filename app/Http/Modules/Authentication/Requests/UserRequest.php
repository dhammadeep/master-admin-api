<?php

namespace App\Http\Modules\Authentication\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Rules\ValidIndianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'mobile' => [
                'required',
                'numeric',
                new ValidIndianPhoneNumber,
                Rule::unique('uapp_user', 'mobile')->ignore($this->user)
            ],
            'email' => 'email',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mobile' => "+91".$this->mobile,
        ]);
    }

    /**
     * Get the validation error messages that apply to the request
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required' => 'Please enter mobile.',
            'mobile.numeric' => 'Please enter only numbers.',
            'password.required' => 'Please enter password.',
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
