<?php

namespace App\Http\Masters\Test\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExampleRequest extends FormRequest
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
            'Name' => 'required|min:2|max:50',
            'AboutMe' => 'required|min:10|max:100',
            'ProfilePhoto' => 'required_without:ProfilePhoto',
            'Gender' => 'required',
            'CountryID' => 'required',
            'StateID' => 'required',
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
            'Name.required' => 'Please enter name.',
            'Name.min' => 'You must have to enter at least 5 letters.',
            'Name.min' => 'You must have to enter at least 5 letters.',
            'CountryID.required' => 'Please select country.',
            'AboutMe.max' => 'Please enter maximun 100 letters only.',
            'AboutMe.required' => 'Please enter about you.',
            'ProfilePhoto.required' => 'Please choose your photo.',
            'ProfilePhoto.mimes' => 'Only jpg, jpeg, png are allowed.',
            'ProfilePhoto.max' => 'File size exceeds maximum limit 4 MB.',
            'Gender' => 'Please select your gender.',
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
            'errors' => $errors,
            'status' => 422,
            'message' => 'fail'
        ]));
    }
}
