<?php

namespace App\Http\Modules\CaseValidation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WarehouseStackFormRequest extends FormRequest
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
            'stackNo' => "required",
            'noOfPackage' => 'required|integer',
            'unitPackageSize' => 'required|integer',
            'packageUomId' => 'required|integer',
            'unitPackageSizeUomId' => 'required|integer'
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
            'stackNo.required' => 'Please provide Stack No.',
            'noOfPackage.integer' => 'Please provide No. Of Package.',
            'unitPackageSize.integer' => 'Please provide Unit Package Size.',
            'packageUomId.integer' => 'Please provide Package Uom.',
            'unitPackageSizeUomId.integer' => 'Please provide Unit Package Size Uom.',
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
