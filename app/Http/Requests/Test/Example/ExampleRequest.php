<?php

namespace App\Http\Requests\Test\Example;

use Illuminate\Foundation\Http\FormRequest;

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
            'AboutMe.min' => 'You must have to enter at least 10 letters about you.',
            'AboutMe.max' => 'Please enter maximun 100 letters only.',
            'AboutMe.required' => 'Please enter about you.',
            'ProfilePhoto.required' => 'Please choose your photo.',
            'ProfilePhoto.mimes' => 'Only jpg, jpeg, png are allowed.',
            'ProfilePhoto.max' => 'File size exceeds maximum limit 4 MB.',
            'Gender' => 'Please select your gender.',
        ];
    }
}
