<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Hash;
use App\Http\Modules\Authentication\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidIndianPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mobile = preg_match('/^\+91\d{10}$/', $value);
        if(empty($mobile)){
            $fail("{$attribute} must be a valid mobile number.");
        }
    }
}
