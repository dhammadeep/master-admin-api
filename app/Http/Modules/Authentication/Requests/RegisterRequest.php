<?php

namespace App\Http\Modules\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'password' => 'required',
            'email' => 'required|email',
            'role' => 'required|exists:auth_activity,name',
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
            'name.required' => 'Please enter name.',
            'mobile.required' => 'Please enter mobile.',
            'mobile.numeric' => 'Please enter only numbers.',
            'mobile.digits' => 'Please enter only 10 digits.',
            'password.required' => 'Please enter password.',
            'email.required' => 'Please enter email.',
            'role.required' => 'Please enter role.',
            'role.exists' => 'Please enter valid role.',
        ];
    }
}
