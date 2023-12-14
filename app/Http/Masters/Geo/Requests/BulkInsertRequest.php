<?php

namespace App\Http\Masters\Geo\Requests;

use App\Rules\Filename;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BulkInsertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', new Filename($this->table)]
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
            'file.required' => 'Please select file.',
            'file.mimes' => 'The file type must be: xlsx.',
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
            'message' => $errors[0]['errorMessage']
        ],422));
    }
}
