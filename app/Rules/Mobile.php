<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Hash;
use App\Http\Modules\Authentication\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class Mobile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mobile = '+91'.request()->mobile;
        $user = User::where('mobile', $mobile)->first();
        if(empty($user)){
            $fail("{$attribute} does not exist.");
        }
        //
    }
}
